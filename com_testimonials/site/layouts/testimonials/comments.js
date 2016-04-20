jQuery(document).ready(function($){
	/* bind submit form event */
	$('.testimonials-list').on('submit', '.comment form', function(e){
		e.preventDefault();
		var form = $(this);
		$.post( form.attr('action'), form.serialize()+'&ajax=1')
		.done(function( data ) {
			data = $.parseJSON(data);
			var html = template_default.replace('{{testimonial}}',data.testimonial).replace('{{comment}}',data.id).replace('{{text}}',data.text);
			if(form.closest('.initial-comment').length){
				/* add to end */
				var scroll_to = form.closest('.initial-comment').next('.comments').append('<div class="comment">'+html+'</div>');
				/* open up comments */
				if(form.closest('.initial-comment').next('.comments').find('.comment').length > 1){
					form.closest('.initial-comment').find('.show-comments:not(.hidden)').click();
				}else{
					form.closest('.initial-comment').find('.show-comments').toggleClass('hidden');
					form.closest('.initial-comment').find('.show-comments').click();
				}
				/* remove form */
				form.closest('.testimonial').find('.show-comments .count').html(form.closest('.testimonial').find('.comments .comment').length);
				form.closest('.comment').remove();
				/* scroll to added comment */
				$('html, body').animate({
					scrollTop: scroll_to.offset().top
				}, 800);
			}else{
				/* just place instead of form */
				form.closest('.comment').html(html);
			}
		}).fail(function( data ) {
			console.log(data);
		});
		return false;
	});
	/* bind show comments event */
	$('.testimonials-list').on('click', '.show-comments', function(e){
		e.preventDefault();
		var comments_block = $(this).closest('.comment').next('.comments');
		comments_block.toggleClass('in');
		$(this).parent().find('.toggle-comments').toggleClass('hidden');
		return false;
	});
	/* bind hide comments event */
	$('.testimonials-list').on('click', '.hide-comments', function(e){
		e.preventDefault();
		var comments_block = $(this).closest('.comment').next('.comments');
		comments_block.toggleClass('in');
		$(this).parent().find('.toggle-comments').toggleClass('hidden');
		return false;
	});
	/* bind add form event */
	$('.testimonials-list').on('click', '.add-reply', function(e){
		e.preventDefault();
		$(this).closest('.testimonials-list').find('.comment > form').parent().remove();
		var comment_block = $(this).closest('.comment');
		$.get($(this).attr('href'),{tmpl:"component"},function( data ) {
			$($(data).find('.comment').parent().html()).insertAfter(comment_block.find('>div:first-child'));
		});
		$(this).closest('.testimonials-list').find('.add-reply').removeClass('hidden');
		$(this).addClass('hidden');
		return false;
	});
	/* bind delete event */
	$('.testimonials-list').on('click', '.delete-reply', function(e){
		e.preventDefault();
		var elem = $(this);
		$.get($(this).attr('href'),{id:$(this).data('id'), task:"comment.delete", ajax:"true"},function( data ) {
			elem.closest('.comment').remove();
		});
		return false;
	});
});