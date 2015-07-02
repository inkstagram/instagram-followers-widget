<?php if ($settings->sharing === '1') { ?>
	<div class="wpfollowers_social">
		<a class="wpfollowers_facebook" href="javascript:wpfollowers_fbshare('http://ink361.com/app/users/ig-<?php echo $settings->user_id ?>/<?php echo $settings->username ?>/followers');"></a>
		<a class="wpfollowers_twitter" href="javascript:wpfollowers_twtshare('http://ink361.com/app/users/ig-<?php echo $settings->user_id ?>/<?php echo $settings->username ?>/followers');"></a>
	</div>

	<script>
		if (!window.wpfollowers_fbshare) {
			window.wpfollowers_fbshare = function(page) {
        		window.open('https://www.facebook.com/sharer/sharer.php?u=' + escape(page), 'Share on Facebook', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=626, height=436, top=' + ((screen.height / 2) - 313) + ', left=' + ((screen.width/2) - 218));
			}	
		}	
		
		if (!window.wpfollowers_twtshare) {
			window.wpfollowers_twtshare = function(page) {      
	        	window.open('https://www.twitter.com/share?text=' + escape('Sharing these my followers with you!') + '&via=ink361&url=' + escape(page), 'Share on Twitter', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=626, height=436, top=' + ((screen.height / 2) - 313) + ', left=' + ((screen.width/2) - 218));
			}				
		}		
	</script>
<?php } ?>
<b class="wpfollowers-header"><?php echo $settings->title ?></b>
<div class="wpfollowers-wrapper">
	<div class="wpfollowers-line"></div>
	<?php
		foreach ($users as $user) {
	?>
		<div class="wpfollowers-entry">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="wpfollowers-avatar">
						<a href="http://ink361.com/app/users/ig-<?php echo $user['id'] ?>/<?php echo $user['username'] ?>/photos">
							<img src="<?php echo $user['profile_picture'] ?>">
						</a>						
					</td>
					<td class="wpfollowers-message">
						<div class="wpfollowers-inner">
							<span>
								<a href="http://ink361.com/app/users/ig-<?php echo $user['id'] ?>/<?php echo $user['username'] ?>/photos">@<?php echo $user['username'] ?></a>
							</span> started following 
							<span>
								<a href="http://ink361.com/app/users/ig-<?php echo $user['id'] ?>/<?php echo $user['username'] ?>/photos">@<?php echo $settings->username ?></a>
							</span>
						</div>		
					</td>
				</tr>
			</table>			
		</div>
	<?php		
		}	
	?>
</div>
<?php if ($settings->powered === '1') { ?>
	<div class="wpfollowers_powered">
		Powered by <a href="http://ink361.com" target="_blank" alt="INK361 Instagram Viewer & Statistics" title="INK361 Instagram Viewer & Statistics">INK361</a>	
	</div>
<?php } ?>