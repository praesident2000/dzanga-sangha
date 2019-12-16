<?php
/**
 * Template Name: News and Stories
 */

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$context['post'] = $post;

global $paged;
if (!isset($paged) || !$paged){
	$paged = 1;
}
$args_stories = array(
	'post_type' => 'dzanga_story',
	'orderby' => 'date',
	'order' => 'DESC',
	'posts_per_page' => 8,
	'paged' => $paged
);

$args_news = array(
	'post_type' => 'post',
	'orderby' => 'date',
	'order' => 'DESC',
	'posts_per_page' => 4,
	'paged' => $paged
);

$categories = get_categories();

$context['categories'] = $categories;
$context['stories'] = new Timber\PostQuery( $args_stories );
$context['news'] = new Timber\PostQuery( $args_news );
Timber::render( array( 'news_stories.twig'), $context );

