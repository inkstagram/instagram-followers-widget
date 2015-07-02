<div class="wpfollowers_error">
  <b>Widget Error!</b>
  
  <p>
    There is a problem with this widget!
  </p>
  
  <p class="wpfollowers_error_description">
    <?php
      if ($details->error_detected == 1) {
    ?>
      Your access token has expired, or is no longer valid. You must re-authenticate with Instagram for this widget to start functioning correctly again.
    <?php
      } else if ($details->error_detected == 2) {
    ?>
      You have reached your hourly API request limit! Try increasing your cache duration from <b><?php print $details->cache_timeout ?></b> to <b><?php print $details->cache_timeout * 2 ?></b>.
      <br><br>
      <em>If you have already updated your cache duration and this message is still visible, please wait for <?php print $details->cache_timeout ?> seconds before adjusting your cache duration again.</em>
    <?php
      } else {
    ?>
      An unknown error occurred, please make sure that your Wordpress installation can access remote resources.
    <?php
      }
    ?>
  </p>
</div>
