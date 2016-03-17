var template = "<div><p>{{text}}</p><a class=\"label label-default abs-pos add-reply\" href=\"/index.php/testimonials-list?view=comment&amp;testimonial={{testimonial}}&amp;comment={{comment}}\">Add reply</a></div>";
jQuery(document).ready(function($){
	$('.testimonials-list').on('submit', '.comment form', function(e){
		e.preventDefault();
		var form = $(this);
		$.post( form.attr('action'), form.serialize()+'&ajax=1')
		.done(function( data ) {
			data = $.parseJSON(data);
			var html = template.replace('{{testimonial}}',data.testimonial).replace('{{comment}}',data.id).replace('{{text}}',data.text);
			form.closest('.comment').html(html);
		}).fail(function( data ) {
			console.log(data);
		});
		return false;
	});
	$('.testimonials-list').on('click', '.toggle-comments', function(e){
		e.preventDefault();
		var comments_block = $(this).closest('.comment').next('.comments');
		comments_block.toggleClass('in');
		$(this).addClass('hidden');
		return false;
	});
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
});