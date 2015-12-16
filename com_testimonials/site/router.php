<?php
/**
 * Testimonials Component for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined( '_JEXEC' ) or die;

function TestimonialsBuildRoute( &$query ) {
	$segments = array();

	if (isset($query['view']) && isset($query['tmpl']) && isset($query['id'])) {
		$segments[] = $query['id'].'-edit-topic';
		unset($query['view']);
		unset($query['tmpl']);
		unset($query['id']);
	}
	if (isset($query['tmpl']) && isset($query['id']) && isset($query['task'])) {
		if ($query['task'] == 'topic.state') {
			$segments[] = $query['id'].'-topic-state';
		}
		unset($query['task']);
		unset($query['tmpl']);
		unset($query['id']);
	}
	if (isset($query['view']) && isset($query['tmpl'])) {
		$segments[] = 'add-testimonial';
		unset($query['view']);
		unset($query['tmpl']);
	}
	if (isset($query['tmpl']) && isset($query['id'])) {
		$segments[] = $query['id'].'-read-topic';
	    unset($query['tmpl']);
	    unset($query['id']);
	}
	if (isset($query['task']) && isset($query['id'])) {
		if ($query['task'] == 'topic.state') {
			$segments[] = $query['id'].'-topic-state';
		} elseif ($query['task'] == 'topic.delete') {
			$segments[] = $query['id'].'-topic-delete';
		} elseif ($query['task'] == 'topic.approve') {
			$segments[] = $query['id'].'-topic-approve';
		}
		unset($query['task']);
		unset($query['id']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'].'-topic-page';
		unset($query['id']);
	}
	/* deprecated? SEO content duplicates // fixed with "noindex,follow" robots, but still can cause troubles for ArtioSef for example */
	/*
	if (isset($query['anc'])) {
		$segments[] = $query['anc'].'-more-about';
		unset($query['anc']);
	}
	*/
	/* --------------- */
	/* will terminate duplicating links */
	if (isset($query['start'])) {
		unset($query['anc']);
	}

    return $segments;
}

function TestimonialsParseRoute($segments) {
	$segment = explode(':', $segments[0]);
	$vars = array();

	switch ($segments[0]) {
		case 'add:testimonial':
			$vars['view'] = 'form';
			$vars['tmpl'] = 'component';
			break;
	}
	switch ($segment[1]) {
		case 'edit-topic':
			$vars['view'] = 'form';
			$vars['tmpl'] = 'component';
			$vars['id'] = $segment[0];
			break;
		case 'topic-page':
			$vars['id'] = $segment[0];
			break;
		/* deprecated? SEO content duplicates // fixed with "noindex,follow" robots, but still can cause troubles for ArtioSef for example */
		case 'more-about':
			$vars['anc'] = $segment[0];
			break;
		/* --------------- */
		case 'read-topic':
			$vars['tmpl'] = 'component';
			$vars['id'] = $segment[0];
			break;
		case 'topic-state':
			$vars['task'] = 'topic.state';
			$vars['id'] = $segment[0];
			break;
		case 'topic-delete':
			$vars['task'] = 'topic.delete';
			$vars['id'] = $segment[0];
			break;
		case 'topic-approve':
			$vars['task'] = 'topic.approve';
			$vars['id'] = $segment[0];
			break;
	}

    return $vars;
}
