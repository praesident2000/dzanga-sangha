<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();

$args = array(
	'post_type' => 'dzanga_story',
	'orderby' => 'date',
	'order' => 'ASC',
	'posts_per_page' => 999,
	'paged' => $paged
);
$context['stories'] = new Timber\PostQuery( $args );

$maintenance = get_field('maintenance_mode','option');
if ($maintenance === true ) {
	$templates = array( 'maintenance.twig' );
}
else {
	$templates = array( 'index.twig' );
}
Timber::render( $templates, $context );
