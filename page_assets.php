<?php
/**
 * Template Name: Assets Archive
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$args_assets = array(
	'post_type' => 'dzanga_assets',
	'orderby' => 'date',
	'order' => 'DESC',
);

$args_taxonomy = array(
	'taxonomy' => 'types',
);

$context['assets'] = new Timber\PostQuery( $args_assets );
Timber::render( array( 'assets.twig'), $context );

