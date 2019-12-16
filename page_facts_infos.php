<?php
/**
 * Template Name: Facts and Infos
 */

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$context['post'] = $post;

Timber::render( array( 'facts_infos.twig'), $context );

