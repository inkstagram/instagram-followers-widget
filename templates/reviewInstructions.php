<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">	
	<div class="instagram_followers_activate instagram_followers_review">
		<div class="instagram_followers_bg">
			<div class="instagram_followers_button instagram_followers_review_button" onclick="displayInstagramFollowersReviewInstructions();">
				Review Followers Widget
			</div>
			<div class="instagram_followers_description">
				You've been using our widget for a while now, do you want to review it?						
			</div>
			<span class="instagram_followers_hide_review">
				<a href="javascript:hideDisplayInstagramFollowersReview();">Never show this message again</a>						
			</span>	
		</div>
	</div>
</div>

<div id="instagram_followers_review_instructions" style="display: none;">
	Show instructions here
</div>

<script>
	function hideDisplayInstagramFollowersReview() {
		var href = location.href;
		if (href.indexOf('?') == -1) {
			href += '?';
		}
		
		href += '&instagram_followers_disable_message=1';
		
		location.href = href;
	}
	
	function displayInstagramFollowersReviewInstructions() {
		window.open('https://wordpress.org/support/view/plugin-reviews/instagram-followers');
		
		hideDisplayInstagramFollowersReview();
	}	
</script>