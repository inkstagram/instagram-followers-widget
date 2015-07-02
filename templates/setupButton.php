<?php
	if ($instance['db_id']) {
		if ($details && $details->token && $details->token !== '' && $details->error_detected !== 1) {
			?>
			<p>
				To configure this widget click on the <b>Configure Widget</b> button.	
			</p>
			
			<input type="button" value="Configure Widget" onclick="openFollowersSetup<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary" id="followersSetupButton<?php print $instance['db_id'] ?>">
			
			<div id="hiddenFollowersFields<?php print $instance['db_id'] ?>" style="display: none;">
				<input type="hidden" name="title"		value="<?php print htmlspecialchars($details->title) ?>">
				<input type="hidden" name="username"	value="<?php print htmlspecialchars($details->username) ?>">
				<input type="hidden" name="user"		value="<?php print htmlspecialchars($details->user_id) ?>">
				<input type="hidden" name="responsive"	value="<?php print htmlspecialchars($details->responsive) ?>">
				<input type="hidden" name="sharing"		value="<?php print htmlspecialchars($details->sharing) ?>">
				<input type="hidden" name="verbose"		value="<?php print htmlspecialchars($details->verbose) ?>">
				<input type="hidden" name="powered"		value="<?php print htmlspecialchars($details->powered) ?>">
				<input type="hidden" name="cache_time"	value="<?php print htmlspecialchars($details->cache_timeout) ?>">
			</div>						
			
			<?php
				$customTitle = 'Instagram Followers Widget';
				
				if ($details->username !== '' && $details->username) {
					$customTitle = 'Instagram Followers: ' . $details->username;
				}
			?>
			
			<div id="followersSetupForm<?php print $instance['db_id'] ?>" style="display: none;">
				<?php require('formHeader.php') ?>
				<div class="widget-content instagram-followers-widget-admin-form" id="formFollowers<?php print $instance['db_id'] ?>">
					<ul class="wp-tab-bar">
						<li data-tab="content" class="tabber active" id="contentTab">
							<a href="#" onclick="javascript:switchFollowersTab(this);" data-tab="content" class="tabber active">
								Content
							</a>							
						</li>
						<li data-tab="display" class="tabber" id="displayTab">
							<a href="#" onclick="javascript:switchFollowersTab(this);" data-tab="display" class="tabber">
								Display
							</a>
						</li>
						<li data-tab="settings" class="tabber" id="settingsTab">
							<a href="#" onclick="javascript:switchFollowersTab(this);" data-tab="settings" class="tabber">
								Settings
							</a>
						</li>
						<li data-tab="help" class="tabber">
							<a href="#" onclick="javascript:switchFollowersTab(this);" data-tab="help" class="tabber">
									Help &amp; Support
							</a>
						</li>
					</ul>
					<div class="tabs-panel tabber active" data-tab="content">
						<p>
							<span class="errorMessage">
								<span class="block-arrow"></span>
								You must enter a title for your widget. If you do not want your widget title to be visible then you must customize your local CSS to hide it.
							</span>
							
							<label>
								Title
								<span class="help-icon dashicons dashicons-info">
									<span class="block">
										<span class="block-arrow"></span>
										This title will appear above your Instagram Followers widget.
									</span>
								</span>	
							</label>	
							<input type="text" name="title" id="title" value="<?php print htmlspecialchars($details->title) ?>" class="widefat">
						</p>
						
						<div id="anotherUser">
				            <p>
				              <span class="errorMessage">
					                <span class="block-arrow"></span>
					                You must search for and select a user if you want to show their followers stream.
				              </span>
				          
				              <label>
					                Instagram account to use
					                <span class="help-icon dashicons dashicons-info">
						                  <span class="block">
							                    <span class="block-arrow"></span>
							                    Search for an Instagram user to display in your widget. Start typing the username and then select one of the users listed in the drop down box.
							                 </span>
					                </span>
				              </label>
				              <input type="hidden" name="user" id="otherUserId" placeholder="Start typing a username to search" value="<?php print $details->user_id ?>">
				              <input class="widefat" type="text" name="username" id="otherUser" placeholder="Start typing a username to search" value="<?php print htmlspecialchars($details->username) ?>" autocomplete="off">
							  <div class="wpfollowers_widget_loader"></div>
					        </p>
				            <div id="otherUserResults"></div>
				    	</div>
						
						<input type="button" class="button button-primary widget-control-save right" onclick="saveFollowersPlugin<?php print $instance['db_id'] ?>(true);" value="Save">
					</div>
					<div class="tabs-panel tabber" data-tab="display">
						<p>
							<label>
								Enable social sharing icons
								<span class="help-icon dashicons dashicons-info">
									<span class="block">
										<span class="block-arrow"></span>
										Enable or disable the social sharing icons on the widget.
									</span>
								</span>
							</label>
							<select name="sharing" class="widefat">
								<option value="1" <?php if ($details->sharing === '1') { echo "SELECTED"; } ?>>Yes</option>
								<option value="0" <?php if ($details->sharing === '0') { echo "SELECTED"; } ?>>No</option>
							</select>
						</p>
						<p>
							<label>
								Responsive
								<span class="help-icon dashicons dashicons-info">
									<span class="block">
										<span class="block-arrow"></span>
										Either enable or disable responsive mode to have the widget automatically resize itself.
									</span>
								</span>
							</label>
							<select name="responsive" class="widefat">
								<option value="1" <?php if ($details->responsive === '1') { echo "SELECTED"; } ?>>Yes</option>
								<option value="0" <?php if ($details->responsive === '0') { echo "SELECTED"; } ?>>No</option>
							</select>
						</p>
						<p>
							<label>
								Show credits
								<span class="help-icon dashicons dashicons-info">
									<span class="block">
										<span class="block-arrow"></span>
										Show the "Powered by INK361" footer on your widget.
									</span>
								</span>
							</label>	
							<select name="powered" class="widefat">
								<option value="0" <?php if ($details->powered === '0') { echo "SELECTED"; } ?>>No</option>
								<option value="1" <?php if ($details->powered === '1') { echo "SELECTED"; } ?>>Yes</option>
							</select>
						</p>
						
						<input type="button" class="button button-primary widget-control-save right" onclick="saveFollowersPlugin<?php print $instance['db_id'] ?>(true);" value="Save">
					</div>
					<div class="tabs-panel tabber" data-tab="settings">
						<p>
							<label>
								Show warnings
								<span class="help-icon dashicons dashicons-info">
									<span class="block">
										<span class="block-arrow"></span>
										If you want the system to display warnings when something has gone wrong turn this on, if you want the plugin to be silent then leave this turned off.
									</span>
								</span>
							</label>							
							<select name="verbose" class="widefat">
								<option value="1" <?php if ($details->verbose === '1') { echo "SELECTED"; } ?>>Yes</option>
								<option value="0" <?php if ($details->verbose === '0') { echo "SELECTED"; } ?>>No</option>
							</select>	
						</p>
						
						<p>
							<span class="errorMessage">
					            <span class="block-arrow"></span>
					            Your cache duration needs to be a whole number that is greater than zero. A good value is <b>300</b>.
				            </span>
							
							<label>
								Cache Duration (seconds)
								<span class="help-icon dashicons dashicons-info">
									<span class="block">
										<span class="block-arrow"></span>
										Enter the amount of time the system should cache the results from Instagram. Entering a higher cache timeout will help busy websites to handle more traffic on the pages with the widget embedded.
									</span>
								</span>
							</label>
							
							<input type="text" name="cache_time" value="<?php print htmlspecialchars($details->cache_timeout) ?>" class="widefat">
						</p>
						
						<input type="button" class="button button-primary widget-control-save right" onclick="saveFollowersPlugin<?php print $instance['db_id'] ?>(true);" value="Save">
					</div>
					<div class="tabs-panel tabber" data-tab="help">
						<div class="linkBar">
				            <b>More Help</b>
				            
				            <ul>
				              <li>
				                <a href="http://wordpress.ink361.com/help/followers/configuring" target="_blank">Configuration help &raquo;</a>
				              </li>
				              <li>
				                <a href="http://wordpress.ink361.com/help/followers/faq" target="_blank">FAQ &raquo;</a>
				              </li>
				              <li class="break"></li>
				              <li>
				                <a href="http://wordpress.ink361.com" target="_blank">Visit main website &raquo;</a>
				              </li>
				              <li>
				                <a href="http://ink361.com" target="_blank">INK361.com</a>
				              </li>
				            </ul>
				          </div>
				          
				          <div class="contactMessage">
				            <p>
				              <b>Got an issue?</b>
				            </p>
				          
				            <p>
				              The INK361 team is here to help!
				            </p>
				            
				            <p>
				              Let us know about your issue by contacting us <a href="mailto:support@ink361.com">via email</a>.
				            </p>
				            
				            <p>
				              Alternatively, you may find your answer in the links to the right.
				            </p>
				          </div>
				        </div>
					</div>
					<?php require('formFooter.php') ?>
				</div>								
			<?php			
		} else {
			?>
			<p>
				<?php if ($details->error_detected === 1) { ?>
					To re-authenticate your widget with Instagram click the <b>Connect To Instagram</b> button. During this process you will be redirected to Instagram to authenticate your widget with the Instagram API.			
				<?php } else { ?>
					To start the Instagram authentication process please click the <b>Connect To Instagram</b> button. During this process you will be redirected to Instagram to authenticate your widget with the Instagram API.
				<?php } ?>
			</p>
			
			<input type="button" value="Connect To Instagram" onclick="openFollowersTokenConnect<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary" id="followersTokenButton<?php print $instance['db_id'] ?>">
			
			<input type="hidden" name="instance_token" id="followersInstanceToken<?php print $instance['db_id'] ?>">
			
			<?php include('message.php') ?>
						
			<?php
		}
		?>
		<script>
			function customiseFollowersTitle<?php print $instance['db_id'] ?>(title) {
		      try {
		        var elem  = jQuery('#followersSetupButton<?php print $instance['db_id'] ?>');
		        elem.parent().parent().parent().parent().find('h4').html(title);
		      } catch(e) {
		        
		      }
		    }
			
		    function saveFollowersPlugin<?php print $instance['db_id'] ?>(close) {            
		      if (copyFollowersFields<?php print $instance['db_id'] ?>()) {
		        //jQuery('#followersSetupButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();
		        if (close) {
		          jQuery('.lboxWrapper').remove();
		        }
		      }
		    }
		
		    function openFollowersTokenConnect<?php print $instance['db_id'] ?>() {   
		      location.href='https://api.instagram.com/oauth/authorize/?client_id=fda05624fb064c7ba5d8d8f18e05e4ca&response_type=code&redirect_uri=' + encodeURIComponent('http://wordpress.ink361.com/setup?loc=' + (location.href.split('#')[0].split('?')[0] + '?followerswidget=<?php print $instance['db_id'] ?>')) + '&scope=basic';
		    }   
				
		    function openFollowersSetup<?php print $instance['db_id'] ?>() {      
		      lightbox({
		        content : window.setupFollowers<?php print $instance['db_id'] ?>,
		        frameCls : '',
		        closeCallback: function() {
		          if (confirm('Would you like to save your changes?')) {
		            saveFollowersPlugin<?php print $instance['db_id'] ?>();
		          }
		        }      
		      });
		      configureFollowersForm();
		    
		      jQuery('.instagram-followers-widget-admin-form input, .instagram-followers-widget-admin-form select').change(function() {
		        configureFollowersForm();
		      });
		
		      //our dropdown search
		      jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUser').keyup(function(event) {
			  	clearTimeout(window.searchTimeout);
		        window.searchTimeout = setTimeout(function() {
		          searchUserHandler<?php print $instance['db_id'] ?>();
		        }, 200);
		      });
		      jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUser').blur(function(event) {
		        setTimeout(function() {
		          jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserResults').removeClass('visible');
		        }, 250);
		      });
		    }
		  
		    function copyFollowersFields<?php print $instance['db_id'] ?>(self) {
		      var error = false;
		      var fields = [];
		      var tabs = [];
		      
		      var data = {
		        title			: jQuery('#formFollowers<?php print $instance['db_id'] ?> input[name=title]').val(),	
				user			: jQuery('#formFollowers<?php print $instance['db_id'] ?> input[name=user]').val(),	      
				cache_duration	: jQuery('#formFollowers<?php print $instance['db_id'] ?> input[name=cache_time]').val(),
		      };        
		      
		      //check always entered values
		      if (data.title.replace(' ', '') == '') {
		        error = true;
		        fields.push(jQuery('#formFollowers<?php print $instance['db_id'] ?> input[name=title]'));
		        tabs.push(jQuery('#formFollowers<?php print $instance['db_id'] ?> #contentTab'));
		      }
		      		    
		     
		      //user needs to be a number
		      if (data.user.replace(' ', '') == '') {
		        error = true;
		        fields.push(jQuery('#formFollowers<?php print $instance['db_id'] ?> input[name=user]'));
		        tabs.push(jQuery('#formFollowers<?php print $instance['db_id'] ?> #contentTab'));
		      }
			  
			  if (data.cache_duration.replace(' ', '') == '' || !(data.cache_duration === parseInt(data.cache_duration, 10) + '') || 0 >= parseInt(data.cache_duration, 10)) {
		        error = true;
		        fields.push(jQuery('#formFollowers<?php print $instance['db_id'] ?> input[name=cache_time]'));
		        tabs.push(jQuery('#formFollowers<?php print $instance['db_id'] ?> #settingsTab'));
		      }
		
		      //clear all formattings
		      jQuery('#formFollowers<?php print $instance['db_id'] ?> p').removeClass('error');
		      jQuery('#formFollowers<?php print $instance['db_id'] ?> .tabber').removeClass('error');
		      
		      if (error) {
		        for (var i = 0; fields.length > i; i++) {
		          jQuery(fields[i]).parent().addClass('error');
		        }
		        
		        for (var i = 0; tabs.length > i; i++) {
		          jQuery(tabs[i]).addClass('error');
		        }
		      } else {    
		        jQuery('#hiddenFollowersFields<?php print $instance['db_id'] ?>').html('');
		        
		        var elems = jQuery('#formFollowers<?php print $instance['db_id'] ?>').find('input');
		      
		        for (var i = 0; elems.length > i; i++) {
		          jQuery('#hiddenFollowersFields<?php print $instance['db_id'] ?>').append(elems[i]);
		        }
		
		        var elems = jQuery('#formFollowers<?php print $instance['db_id'] ?>').find('select');
		    
		        for (var i = 0; elems.length > i; i++) {
		          jQuery('#hiddenFollowersFields<?php print $instance['db_id'] ?>').append(elems[i]);
		        }
		      }
		      
		      return !error;
		    }
		  
		    jQuery(document).ready(function() {      
		      if (!window.switchFollowersTab) {
		        window.switchFollowersTab = function(obj) {
		          var tab = jQuery(obj).attr('data-tab');
		        
		          jQuery('.tabber').removeClass('active');
		        
		          jQuery('[data-tab="' + tab + '"]').addClass('active');
		        }
		      }
		    
		      if (!window.configureFollowersForm) {
		        window.configureFollowersForm = function() {
		        
		        }
		      }
		  
		      window.setupFollowers<?php print $instance['db_id'] ?> = jQuery('#followersSetupForm<?php print $instance['db_id'] ?>').html();
		      
		      jQuery('#followersSetupForm<?php print $instance['db_id'] ?>').html('');
		  
		      var token = '<?php print $instance['db_id'] ?>';
		      if (location.href.replace('followerswidget=' + token, '') != location.href) {
		        setTimeout(function() {
		          var parts = location.search.replace('?', '').replace('#', '').split('&');
		        
		          for (var i = 0; parts.length > i; i++) {
		            var p = parts[i].split('=');
		          
		            if (p[0] == 'token') {
		              var access_token = p[1];
		            
		              jQuery('#followersInstanceToken<?php print $instance['db_id'] ?>').val(access_token);            
		              jQuery('#followersTokenButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();
		
		              setTimeout(function() {
		                location.href = location.href.split('#')[0].split('?')[0] + '?openFollowersWidget=' + this;
		              }.bind('<?php print $this->id ?>'), 100);
		            }
		          }
		        }, 100);
		      }
		    
		      if (location.search != '' && location.search.replace('openFollowersWidget', '') != location.search) {      
		        setTimeout(function() {
		          var parts = location.search.replace('?', '').replace('#', '').split('&');
		          for (var i = 0; parts.length > i; i++) {
		            var p = parts[i].split('=');
		            
		            if (p[0] == 'openFollowersWidget') {
		              var widgetId = p[1];
		            
		              var elems = jQuery('.widget');
		              for (var j = 0; elems.length > j; j++) {
		                if (jQuery(elems[j]).attr('id').indexOf(widgetId) > 0) {
		                  jQuery(elems[j]).addClass('open');
		                  jQuery(elems[j]).find('.widget-inside').show();
		                }
		              }
		            }
		          }
		        }, 100);
		      }
		    
		      if (!window.searchUserHandler<?php print $instance['db_id'] ?>) {
		        window.searchUserHandler<?php print $instance['db_id'] ?> = function() {
		          var keywords = jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUser').val();
		                 
		          if (keywords.length > 2) {
					jQuery('#formFollowers<?php print $instance['db_id'] ?> #anotherUser').addClass('wploading');
					  
		            jQuery.ajax({
		              url	: 'https://api.instagram.com/v1/users/search',
		              jsonp 	: "callback",
		              dataType	: "jsonp",
		              data 	: {
		                access_token : '<?php print $details->token ?>', 
		                q : keywords
		              },
		              success	: function(response) {
		                //reset the id
		                jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserId').val('');
		                                   
		                if (response && response.data && response.data.length > 0) {
		                  var html = '';
		                  for (var i = 0; i < response.data.length; i++) {
		                    html += '<div class="result" data-id="' + response.data[i].id + '" data-name="' + response.data[i].username + '">' + response.data[i].username + '</div>';
		                  }
		                  jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserResults').html(html);
		                       
		                  jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserResults').find('.result').click(function() {
		                    jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUser').val(jQuery(this).attr('data-name'));
		                    jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserId').val(jQuery(this).attr('data-id'));
		                  }); 
		                  
		                  jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserResults').addClass('visible');
		                } else {
		                  //show no users
		                  noUsersFound<?php print $instance['db_id'] ?>();
		                }   
						
						jQuery('#formFollowers<?php print $instance['db_id'] ?> #anotherUser').removeClass('wploading');           
		              }
		            });
		          }    
		        }
		      }
		    
		      if (!window.noUsersFound<?php print $instance['db_id'] ?>) {
		        window.noUsersFound<?php print $instance['db_id'] ?> = function() {
		          jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserResults').html('<div class="noResults">Nobody found</div>');
		          jQuery('#formFollowers<?php print $instance['db_id'] ?> #otherUserResults').addClass('visible');                         
		        }
		      }
			  
			   //customise with our title
      		   customiseFollowersTitle<?php print $instance['db_id'] ?>('<?php print $customTitle ?>');         
		    });
  		</script>
		<?php
	} else {
		?>
		<p id="wpfollowers-widget-__i__" class="pDetect">
			Please wait while we create a database entry for this widget, your page may refresh during this process.	
		</p>

		<script>
			jQuery(document).ready(function() {
				var elems = jQuery('.pDetect');
				
				for (var i = 0; i < elems.length; i++) {
					if (jQuery(elems[i]).attr('id').replace('__i__', '') == jQuery(elems[i]).attr('id')) {
						setTimeout(function() {
							location.href = location.href.split('#')[0].split('?')[0] + '?openFollowersWidget=' + jQuery(this).attr('id');
						}.bind(elems[i]), 1000);
					}	
				}
			});
		</script>
		<?php		
	}
?>
