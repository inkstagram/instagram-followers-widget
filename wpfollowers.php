<?php
/*
	Plugin Name: Instagram Followers
	Plugin URI: http://wordpress.ord/extend/plugins/instagram-followers
	Description: Realtime Instagram followers widget
	Version: 1.0.5
	Author: jbenders
	Author URI: http://ink361.com/
*/

if (!defined('INSTAGRAM_FOLLOWERS_PLUGIN_URL')) {
	define('INSTAGRAM_FOLLOWERS_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
}

function wp_instagram_followers_admin_register_head() {
	$siteurl = get_option('siteurl');	
}

add_action('admin_head', 'wp_instagram_followers_admin_register_head');
add_action('widgets_init', 'load_wp_instagram_followers');
add_action('admin_notices', 'wpinstagram_followers_show_instructions');

function load_wp_instagram_followers() {
	register_widget('WPInstagramFollowersWidget');
}

function wpinstagram_followers_show_instructions() {
	global $wpdb;
	
	$results = $wpdb->get_results("SELECT * FROM igfollowers_widget");
	
	if (sizeof($results) == 0) {	
		$url = plugins_url('wpfollowers-admin.css', __FILE__); 
		wp_enqueue_style('wpfollowers-admin.css', $url);
		wp_enqueue_script("jquery");
		wp_enqueue_script("lightbox", plugin_dir_url(__FILE__)."js/lightbox.js", Array('jquery'), null);
		
		require(plugin_dir_path(__FILE__) . 'templates/setupInstructions.php');		
	} else {
		$settings = $wpdb->get_results("SELECT * FROM igfollowers_global_settings WHERE name='firstRun' and value <= DATE_SUB(now(), INTERVAL 7 DAY)");
		
		if (sizeof($settings) == 0) {
			#has it been set yet?
			$check = $wpdb->get_results("SELECT * FROM igfollowers_global_settings WHERE name='firstRun'");
			if (sizeof($check) == 0) {				
				#create it
				$wpdb->get_results("INSERT INTO igfollowers_global_settings (name, value) VALUES ('firstRun', NOW())");
			}
		} else {
			#have we been disabled?
			$disabled = $wpdb->get_results("SELECT * FROM igfollowers_global_settings WHERE name='disabledMessage'");
			
			if (sizeof($disabled) == 0) {	
				#have we received a request to remove the message?								
				if (isset($_POST['instagram_followers_disable_message']) || isset($_GET['instagram_followers_disable_message'])) {
					$wpdb->get_results("INSERT INTO igfollowers_global_settings (name, value) VALUES ('disabledMessage', '1')");
				} else {						
					#need to show header
					$url = plugins_url('wpfollowers-admin.css', __FILE__); 
					wp_enqueue_style('wpfollowers-admin.css', $url);
					wp_enqueue_script("jquery");
					wp_enqueue_script("lightbox", plugin_dir_url(__FILE__)."js/lightbox.js", Array('jquery'), null);
					
					require(plugin_dir_path(__FILE__) . 'templates/reviewInstructions.php');					
				}				
			}	
		}
	}
}

class WPInstagramFollowersWidget extends WP_Widget {
	function WPInstagramFollowersWidget($args=array()) {
		$widget_ops = array('description' => __('Display Instagram Follower stream', 'wpfollowers'));
		$control_ops = array('id_base' => 'wpfollowers-widget');
		
		$this->wpfollowers_path = plugin_dir_url(__FILE__);
		$this->WP_Widget('wpfollowers-widget', __('Instagram Followers Widget', 'wpfollowers'), $widget_ops, $control_ops);
		
		if (is_admin()) {
			$this->handleTables();		
		}
		
		if (is_active_widget('', '', 'wpfollowers-widget') && !is_admin()) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('wpfollowers', $this->wpfollowers_path . '/js/wpfollowers.js', Array('jquery'), null);
			wp_enqueue_style('wpfollowers', $this->wpfollowers_path.'wpfollowers.css', Array(), '0.5');			
		}
	}
	
	function widget($args, $instance) {
		extract($args);
		
		if ($instance['db_id']) {
			global $wpdb;
			
			$details = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->_tablePrefix() . "widget WHERE uid=%s", $instance['db_id']));
			
			if (sizeof($details) > 0) {
				$details = $details[0];
				
				if ($details->cache_time !== NULL && $details->cache_time !== '') {
					$ts = $details->cache_time;
					$time = New DateTime("@$ts");				
					
					$fromDB = $wpdb->get_results("SELECT NOW() as dbtime");
					$now = DateTime::createFromFormat('Y-m-d H:i:s', $fromDB[0]->dbtime);
					$interval = new DateInterval('PT' . $this->_defaultCacheTime() . 'S');
					
					if ($details->cache_timeout !== NULL && $details->cache_timeout !== '') {
						$interval = new DateInterval('PT' . $details->cache_timeout .'S');	
					}					
					
					if ($time->add($interval) > $now) {
						if ($details->cache !== NULL && $details->cache !== '') {
							$cached = json_decode($details->result_cache, true);
							
							if (is_array($cached) && sizeof($cached) > 0) {
								$this->_display_results($cached, $details, true);
								return;
							}
						}						
					}
				}
				
				if ($details->token && $details->token !== '') {
					$url = 'https://api.instagram.com/v1/users/' . $details->user_id . '/followed-by?count=25&access_token=' . $details->token;
					$response = wp_remote_get($url, array('sslverify' => apply_filters('https_local_ssl_verify', false)));
					if (!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
						$data = json_decode($response['body'], true);
						if ($data['meta']['code'] === 200) {
							$this->_display_results($data['data'], $details, false);
						}						
					} else {
						$this->_handle_error_response($response, $details);
						return;					
					}				
				}			
			}
		}
	}
	
	function _handle_error_response($response, $settings) {		
		$code = -9999;
		$error = "An unknown error occured.";
		
		if (is_wp_error($response)) {
			$error = "An unknown error occurred, please make sure that your Wordpress installation can access remote resources.";
		} else {
			if (array_key_exists('response', $response)) {
				if (array_key_exists('code', $response['response'])) {
			 		$code = $response['response']['code'];
				}
			}
			if (array_key_exists('meta', $response) && array_key_exists('error_message', $response['meta'])) {
				$error = $response['meta']['error_message'];
			}
			if (array_key_exists('error_message', $response)) {
				$error = $response['error_message'];
			}
		}

		#standard messages
		if ($code === 400) {
			$error = 'Your access token has expired! Please login to your administration to reset your token.';
			$code = 1;
		}
		
		if ($code === 429) {
			$error = 'You have reached your API request limit, consider adjusting your cache timeout value in your administration.';
			$code = 2;
		}		
		
		#set it in our db entry
		global $wpdb;
		$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget SET error_detected=%s WHERE uid=%s", $code, $settings->uid));
		
		if ($settings->verbose === '1') {
			require(plugin_dir_path(__FILE__) . "templates/errorFrontend.php");						
		} else {
			error_log($error);
		}
	}
	
	function _defaultCacheTime($args=array()) {
		extract($args);
		
		#5 minutes by default
		return 300;
	}
	
	function _display_results($users, $settings, $fromCache) {
		if (!$fromCache) {
			global $wpdb;
			
			$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget SET cache=%s, cache_time=NOW() WHERE uid=%s", json_encode($users), $settings->uid));
			
			if ($settings->error_detected !== 0) {
				$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget SET error_detected=0 WHERE uid=%s", $settings->uid));
			}
		}
		
		require(plugin_dir_path(__FILE__) . 'templates/main.php');
		
		if (sizeof($users) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function form($instance) {
		$url = plugins_url('wpfollowers-admin.css', __FILE__);
		wp_enqueue_style('wpfollowers-admin', $url);		
		wp_enqueue_script('jquery');
		wp_enqueue_script('lightbox', plugin_dir_url(__FILE__) . 'js/lightbox.js', Array('jquery'), null);
		
		$details = NULL;
		
		if ($instance['db_id']) {
				global $wpdb;
				
				$details = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->_tablePrefix() . "widget WHERE uid=%s", $instance['db_id']));
				
				if (sizeof($details) > 0) {
					$details = $details[0];
					
					$details = $this->_confirmDefaults($details);										
					
					if ($details->error_detected > 0) {
						require(plugin_dir_path(__FILE__) . 'templates/errorBackend.php');
					}					
				}		
		}
		
		require(plugin_dir_path(__FILE__) . 'templates/setupButton.php');
		
		return;		
	}
	
	function update($new_instance, $old_instance) {
		$instance = $new_instance;
		global $wpdb;
				
		if (!$old_instance['db_id']) {
			$wpdb->get_results($wpdb->prepare("INSERT INTO " . $this->_tablePrefix() . "widget (localid, setup, last_modified) VALUES (%s, 0, NOW())", $this->id));
			
			$result = $wpdb->get_results("SELECT last_insert_id() as uid");
						
			$instance['db_id'] = $result[0]->uid;			
		} else {
			$instance['db_id'] = $old_instance['db_id'];			
		}
		
		if ($_POST['instance_token']) {
			$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget set error_detected=0, setup=1, token=%s, last_modified=NOW() WHERE uid=%s", sanitize_text_field($_POST['instance_token']), $instance['db_id']));
		}
		
		if ($_POST['title']) {
			$settings = array(
				'title'		=> sanitize_text_field(stripslashes($_POST['title'])),
				'username'	=> sanitize_text_field(stripslashes($_POST['username'])),
				'user_id'	=> sanitize_text_field($_POST['user']),
				'responsive'=> sanitize_text_field($_POST['responsive']),
				'sharing'	=> sanitize_text_field($_POST['sharing']),				
				'verbose'	=> sanitize_text_field($_POST['verbose']),
				'powered'	=> sanitize_text_field($_POST['powered']),
				'cache_time'=> sanitize_text_field($_POST['cache_time']),
			);
			
			$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget SET title=%s,
																							  username=%s,
																							  user_id=%s,
																							  responsive=%s,
																							  sharing=%s,
																							  verbose=%s,
																							  powered=%s,
																							  error_detected=0,
																							  cache_timeout=%s,
																							  last_modified=NOW() WHERE uid=%s", $settings['title'], $settings['username'], $settings['user_id'],
																							  $settings['responsive'], $settings['sharing'], $settings['verbose'],
																							  $settings['powered'], $settings['cache_time'], $instance['db_id']));
		}
		
		return $instance;
	}
	
	function _tablePrefix($args=array()) {
		extract($args);
		
		return 'igfollowers_';	
	}
	
	function _tableDescription($args=array()) {
		extract($args);
		
		return array(			
			$this->_tablePrefix() . 'widget' => array(
				'uid' 	=> array(
					'type' 	=> 'int(11)',
					'null' 	=> false,
					'pk' 	=> true,
					'auto'	=> true,
				),
				'localid' => array(
					'type' 	=> 'varchar(255)',
					'null' 	=> false,
				),
				'token'	=> array(
					'type' 	=> 'varchar(255)',
					'null'	=> true,
				),
				'setup' => array(
					'type'	=> 'int(1)',
					'null'	=> false,
				),
				'title' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,	
				),
				'error_detected' => array(
					'type'	=> 'int(1)',
					'null'	=> true,
				),
				'last_modified' => array(
					'type' 	=> 'datetime',
					'null'	=> true,
				),
				'username' => array(
					'type'	=> 'varchar(255)',	
					'null' => true,
				),
				'user_id' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,
				),
				'cache'		=> array(
					'type'	=> 'text',
					'null'	=> true,	
				),			
				'cache_timeout'	=> array(
					'type'	=> 'int(6)',
					'null'	=> true,	
				),
				'cache_time'	=> array(
					'type'	=> 'int(9)',
					'null'	=> true,	
				),
				'responsive'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,	
				),
				'powered'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,	
				),
				'sharing'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,					
				),
				'verbose'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,
				),							
			),
			$this->_tablePrefix() . 'global_settings' => array(
				'uid'	=> array(
					'type' 	=> 'int(11)',
					'null' 	=> false,
					'pk' 	=> true,
					'auto'	=> true,
				),
				'name'	=> array(
					'type'	=> 'varchar(255)',
					'null'	=> true,
				),
				'value'	=> array(
					'type'	=> 'text',
					'null'	=> true,	
				),
			),
		);
	}
	
	function _describeTable($name) {
		global $wpdb;
		
		$ret = array();		
		$result = $wpdb->get_results("DESC $name");
		
		if (sizeof($result) == 0) {
			return NULL;
		} else {
			foreach ($result as $column) {
				$fields = array();

				#type
				$fields['type'] = strtolower($column->Type);
				#null				
				if (strtolower($column->Null) === 'no') {
					$fields['null'] = false;
				} else {
					$fields['null'] = true;
				}
				#pk
				if (strtolower($column->Key) === 'pri') {
					$fields['pk'] = true;
				} else {
					$fields['pk'] = false;
				}
				#auto
				if (strtolower($columns->Extra) === 'auto_increment') {
					$fields['auto'] = true;
				} else {
					$fields['auto'] = false;
				}

				$ret[$column->Field] = $fields;
			}
		}				
		
		return $ret;
	}
	
	function handleTables($args=array()) {
		global $wpdb;
		
		extract($args);
		
		$tables = $this->_tableDescription();
		
		foreach ($tables as $name => $description) {
			$currentTable = $this->_describeTable($name);		
			
			if (is_null($currentTable)) {
				#make the table!
				$query = "CREATE TABLE $name (";
				
				foreach ($description as $columnName => $columnDetails) {
					$query .= " $columnName ";
					if ($columnDetails['type']) {
						$query .= $columnDetails['type'] . ' ';
					} else {
						$query .= ' varchar(255) ';
					}
					
					if ($columnDetails['null']) {
						$query .= ' NULL ';
					} else {
						$query .= ' NOT NULL ';
					}
					
					if ($columnDetails['auto']) {
						$query .= ' auto_increment ';
					}
					
					if ($columnDetails['pk']) {
						$query .= ' primary key ';
					}
					
					$query .= ', ';
				}
				
				$query = substr($query, 0, -2);
				$query .= ")";
				$result = $wpdb->get_results($query);
			} else {
				#compare the columns to see if we need to add one
				foreach ($description as $columnName => $columnDetails) {
					$found = false;
					foreach ($currentTable as $currentName => $currentDetails) {
						if ($currentName === $columnName) {
							$found = true;
						}
					}
					
					if ($found === false) {
						$query = "ALTER TABLE $name ADD COLUMN ";
						
						$query .= " $columnName ";
						if ($columnDetails['type']) {
							$query .= $columnDetails['type'] . ' ';
						} else {
							$query .= ' varchar(255) ';
						}
					
						if ($columnDetails['null']) {
							$query .= ' NULL ';
						} else {
							$query .= ' NOT NULL ';
						}
					
						if ($columnDetails['auto']) {
							$query .= ' auto_increment ';
						}
						
						if ($columnDetails['pk']) {
							$query .= ' primary key ';
						}
						
						$result = $wpdb->get_results($query);
					}
				}
			}
		}
	}
	
	function _confirmDefaults($settings) {		
		if ($settings->title === NULL || $settings->title === '') {
			$settings->title = 'My Followers';
		}
		
		if ($settings->username === NULL) {
			$settings->username = '';
		}
						
		if ($settings->user_id === NULL) {
			$settings->user_id = '';		
		}				
		
		if ($settings->responsive === NULL || $settings->responsive === '') {
			$settings->response = '1';
		}
		
		if ($settings->powered === NULL || $settings->powered === '') {
			$settings->powered = '0';
		}
		
		if ($settings->sharing === NULL || $settings->sharing === '') {
			$settings->sharing = '1';
		}
		
		if ($settings->verbose === NULL || $settings->verbose === '') {
			$settings->verbose = '1';			
		}
		
		if ($settings->cache_timeout === NULL || $settings->cache_timeout === 0  || $settings->cache_timeout === '') {
			$settings->cache_timeout = $this->_defaultCacheTime();			
		}
		
		return $settings;
	}
}

?>
