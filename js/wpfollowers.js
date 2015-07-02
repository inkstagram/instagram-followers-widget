jQuery(document).ready(function() {
	setTimeout(animateFollowers, 5000);
});

function animateFollowers() {
	jQuery('.wpfollowers-wrapper').each(function() {
		//get the first elem		
		jQuery(this).find('.wpfollowers-entry').animate({
			top: '-=79'
		}, 300, function() {
			
		});
		
		//shift the first to the end
		setTimeout(function() {
			var first = jQuery(this).find('.wpfollowers-entry').first();
			jQuery(first).parent().append(jQuery(first));
			
			jQuery(this).find('.wpfollowers-entry').each(function() {
				jQuery(this).animate({ top : '+=79'}, 0);
			});				
		}.bind(this), 500);
	});
	
	setTimeout(animateFollowers, 5000);
}