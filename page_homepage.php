<?php
/**
 * Template Name: Homepage
 */

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$post = new TimberPost();
$context['post'] = $post;
$maintenance = get_field('maintenance_mode','option');
if ($maintenance === true ) {
	$templates = array( 'maintenance.twig' );
}
else {
	$templates = array( 'home.twig' );
}
Timber::render( $templates, $context );