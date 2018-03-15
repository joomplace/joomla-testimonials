(function($) {
    $(document).ready(function(){
            /*add class to bottom tags */
            $(".label").click(function(){    
            if ($(this).hasClass("label-success")) {
                $(this).removeClass("label-success");
				var selectedTags = $('#jform_tags').val();
				delete selectedTags[selectedTags.indexOf($(this).attr('tag'))];
				$('#jform_tags').val(selectedTags);
            }
            else {
                $(this).addClass("label-success");
				var selectedTags = $('#jform_tags').val();
				if(selectedTags){
					selectedTags.push($(this).attr('tag'));
					$('#jform_tags').val(selectedTags);
				}else $('#jform_tags').val($(this).attr('tag'));
			}
		});
        $(".testim-more").toggle(function(){
		   $(".testim-notrequired").slideDown(500);
	       $("#testim-more-label").removeClass('icon-caret-right');
	       $("#testim-more-label").addClass('icon-caret-down');
        }, function(){
		   $(".testim-notrequired").slideUp(500);            
	       $("#testim-more-label").removeClass('icon-caret-down');
	       $("#testim-more-label").addClass('icon-caret-right');
        });
	} );           
})(jQuery);