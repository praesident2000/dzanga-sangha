<?php

defined( 'ABSPATH' ) or die(); // Avoid direct file request

if ( ! class_exists( 'Timber' ) ) {

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

if ( class_exists( 'Timber' ) ){
    Timber::$cache = false;
}

require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'dzanga_sangha_register_required_plugins' );

function dzanga_sangha_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug. See http://tgmpluginactivation.com/
	 */
	$plugins = array(

		array(
			'name'      => 'Advanced Custom Fields PRO',
			'slug'      => 'advanced-custom-fields-pro',
			'required'  => true,
		),
        
        array(
            'name'      => 'Timber',
            'slug'      => 'timber-library',
            'required'  => true,
        ),

        array(
            'name'      => 'Dzanga Sangha Plugin',
            'slug'      => 'dzanga-sangha-plugin',
            'required'  => true,
        ),
    );
    
	$config = array(
		'id'           => 'dzanga-sangha',         // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

// Timber::$cache = true;
/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {

	public static $translations;

	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'change_post_types' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_menus' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'init', array( $this, 'dzanga_asset_taxonomy') );
		add_action( 'wp_enqueue_scripts', array($this, 'deregister_scripts'), 100 );
		add_action( 'wp_print_styles', array($this, 'wps_deregister_styles'), 100 );

        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'menus' );
        add_theme_support( 'html5', ['search-form', 'gallery', 'caption'] );

		add_image_size( 'small', 320, 320, true );
		add_image_size( 'huge', 1600, 1600, true );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );

		parent::__construct();

		self::$translations = [
            'search' => __( 'Search', 'dzanga-sangha-theme' ),
            'language' => __( 'Language', 'dzanga-sangha-theme' ),
            'spread_the_word' => __( 'Spread The Word', 'dzanga-sangha-theme' ),
            'join' => __( 'Join', 'dzanga-sangha-theme' ),
            'load_more_results' => __( 'Load more results', 'dzanga-sangha-theme' ),
            'stories' => __( 'Stories', 'dzanga-sangha-theme' ),
            'load_more_stories' => __( 'Load more stories', 'dzanga-sangha-theme' ),
            'interested_in' => __( "I'm interested in", 'dzanga-sangha-theme' ),
            'topic' => __( "Topic", 'dzanga-sangha-theme' ),
            'all_topics' => __( "All Topics", 'dzanga-sangha-theme' ),
            'download' => __( "Download", 'dzanga-sangha-theme' ),
        ];
	}

	/** This is where you can change custom post types. */
	public function change_post_types() {
		$get_post_type = get_post_type_object('post');
		$labels = $get_post_type->labels;
		$labels->name = 'News';
		$labels->singular_name = 'News';
		$labels->add_new = 'Add News';
		$labels->add_new_item = 'Add News';
		$labels->edit_item = 'Edit News';
		$labels->new_item = 'News';
		$labels->view_item = 'View News';
		$labels->search_items = 'Search News';
		$labels->not_found = 'No News found';
		$labels->not_found_in_trash = 'No News found in Trash';
		$labels->all_items = 'All News';
		$labels->menu_name = 'News';
		$labels->name_admin_bar = 'News';
	}

	/** This is where you can register custom post types. */
	public function register_post_types() {
		register_post_type( 'dzanga_story', [
            'labels' => [
                'name' => __( 'Stories' ),
                'singular_name' => __( 'Story' ),
                'add_new_item' => __( 'Add New Story' ),
                'edit_item' => __( 'Edit Story' ),
                'new_item' => __( 'New Story' ),
                'view_item' => __( 'View Story' ),
                'all_items' => __( 'All Stories' ),
                'search_items' => __( 'Search Stories' ),
                'not_found' => __( 'No Story found.' ),
                'not_found_in_trash' => __( 'No Story found in Trash.' ),
            ],
            'menu_icon' => 'dashicons-admin-page',
            'menu_position' => 20,
            'supports' => ['title', 'custom-fields', 'revisions'],
            'public' => true,
            'has_archive' => true,
            'show_in_nav_menus' => true,
            'rewrite' => ['slug' => 'stories', 'with_front' => false],
            'taxonomies' => ['category'],
		] );
		
		register_post_type( 'dzanga_partner', [
            'labels' => [
                'name' => __( 'Partners & Sponsors' ),
                'singular_name' => __( 'Partner' ),
                'add_new_item' => __( 'Add New Partner' ),
                'edit_item' => __( 'Edit Partner' ),
                'new_item' => __( 'New Partner' ),
                'view_item' => __( 'View Partner' ),
                'all_items' => __( 'All Partners' ),
                'search_items' => __( 'Search Partners' ),
                'not_found' => __( 'No Partner found.' ),
                'not_found_in_trash' => __( 'No Partner found in Trash.' ),
            ],
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 21,
            'supports' => ['title', 'custom-fields', 'revisions'],
            'public' => true,
            'show_ui' => true,
            'has_archive' => false,
            'show_in_nav_menus' => true,
            'rewrite' => ['slug' => 'partners', 'with_front' => false],
		] );
		
		register_post_type( 'dzanga_activities', [
            'labels' => [
                'name' => __( 'Overlays' ),
                'singular_name' => __( 'Overlay' ),
                'add_new_item' => __( 'Add New Overlay' ),
                'edit_item' => __( 'Edit Overlay' ),
                'new_item' => __( 'New Overlay' ),
                'view_item' => __( 'View Overlay' ),
                'all_items' => __( 'All Overlays' ),
                'search_items' => __( 'Search Overlays' ),
                'not_found' => __( 'No Overlay found.' ),
                'not_found_in_trash' => __( 'No Overlay found in Trash.' ),
            ],
            'menu_icon' => 'dashicons-admin-comments',
            'menu_position' => 22,
            'supports' => ['title', 'custom-fields', 'revisions'],
            'public' => true,
            'has_archive' => false,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'overlays', 'with_front' => false],
		] );
        
        register_post_type( 'dzanga_assets', [
            'labels' => [
                'name' => __( 'Assets' ),
                'singular_name' => __( 'Assets' ),
                'add_new_item' => __( 'Add New Assets' ),
                'edit_item' => __( 'Edit Asset' ),
                'new_item' => __( 'New Asset' ),
                'view_item' => __( 'View Asset' ),
                'all_items' => __( 'All Assets' ),
                'search_items' => __( 'Search Assets' ),
                'not_found' => __( 'No Asset found.' ),
                'not_found_in_trash' => __( 'No Asset found in Trash.' ),
            ],
            'menu_icon' => 'dashicons-format-gallery',
            'menu_position' => 23,
            'supports' => ['title', 'custom-fields', 'revisions'],
            'public' => true,
            'has_archive' => true,
            'show_in_nav_menus' => true,
            'rewrite' => ['slug' => 'assets', 'with_front' => false],
		] );
    }
    
    public function dzanga_asset_taxonomy() {	
    
        register_taxonomy('types',array('dzanga_assets'), array(
            'hierarchical' => true,
            'labels' => [
                'name' => _x( 'Types', 'taxonomy general name' ),
                'singular_name' => _x( 'Type', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Types' ),
                'all_items' => __( 'All Types' ),
                'parent_item' => __( 'Parent Type' ),
                'parent_item_colon' => __( 'Parent Type:' ),
                'edit_item' => __( 'Edit Type' ), 
                'update_item' => __( 'Update Type' ),
                'add_new_item' => __( 'Add New Type' ),
                'new_item_name' => __( 'New Type Name' ),
                'menu_name' => __( 'Types' ),
            ],
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'type' ),
        ));
    }

    public function register_menus()
    {
        register_nav_menu( 'main-menu', __( 'Main Navigation', 'dzanga-sangha-theme' ) );
        register_nav_menu( 'meta-menu', __( 'Meta Navigation', 'dzanga-sangha-theme' ) );
        register_nav_menu( 'legal-menu', __( 'Legal Navigation', 'dzanga-sangha-theme' ) );
        register_nav_menu( 'language-menu', __( 'Language Menu', 'dzanga-sangha-theme' ) );
    }
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
        $context['notes'] = 'These values are available everytime you call Timber::get_context();';
        $context['options'] = get_fields('options');
        $context['column_layout_classes'] = get_field('column_layout_classes','option');
        $context['main_menu']  = new TimberMenu( 'main-menu' );
        $context['meta_menu']  = new TimberMenu( 'meta-menu' );
        $context['legal_menu'] = new TimberMenu( 'legal-menu' );
        $context['site'] = $this;
        $context['current_language'] = apply_filters( 'wpml_current_language', null );
        $context['languages'] = apply_filters( 'wpml_active_languages', null );
		return $context;
	}


	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addGlobal( 'translations', self::$translations );
		return $twig;
	}

	public function deregister_scripts()
    {
        $calendar_page = new TimberPost(get_field('calendar_page', 'option'));
        $slug = $calendar_page->post_name;

        if(!is_page($slug)){
            wp_deregister_script( 'jquery' );
            wp_dequeue_style( 'wpsbc-style' );
        };

        // We do not use widgets. Deregister react and react-dom from the real-media-library plugin.
        wp_deregister_script( 'react' );
        wp_deregister_script( 'react-dom' );

        // No Embeds by default
        wp_deregister_script( 'wp-embed' );
    }

	public function wps_deregister_styles() {

		// Remove the unused Gutenberg styles
	    wp_dequeue_style( 'wp-block-library' );
	    wp_deregister_style( 'wp-block-library' );
        wp_deregister_style( 'dashicons' ); 
	}
}

new StarterSite();


/**
 * Hide WordPress core features to replace them with ACF
 */
function remove_wordpress_features()
{
	remove_post_type_support( 'page', 'editor' );
	remove_post_type_support( 'page', 'thumbnail' );
	remove_post_type_support( 'post', 'editor' );
	remove_post_type_support( 'post', 'thumbnail' );
	remove_post_type_support( 'post', 'tags' );
}
add_action( 'admin_init', 'remove_wordpress_features' );

/**
 * Disable comment features and hide the comments backend
 */

function remove_admin_menus() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_admin_menus' );

function remove_comment_support() {
   remove_post_type_support( 'post', 'comments' );
   remove_post_type_support( 'page', 'comments' );
}
add_action('init', 'remove_comment_support', 100);

function remove_comments_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'remove_comments_admin_bar' );

function remove_dashboard_meta() {

	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );   // Incoming Links
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );          // Plugins
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );        // Quick Press
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );      // Recent Drafts
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );            // WordPress blog
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );          // Other WordPress News    
    remove_action( 'welcome_panel', 'wp_welcome_panel' );                   // Remove WordPress Welcome Panel

}
add_action( 'admin_init', 'remove_dashboard_meta' );
 
if( function_exists('acf_add_options_page') ) {
 	
	// add parent
   $parent = acf_add_options_page(array(
	   'page_title' 	=> 'Theme Settings',
	   'menu_title' 	=> 'Theme Settings',
	   'menu_slug' 	    => 'theme-settings',
	   'redirect' 		=> false
   ));
   
   
   // add sub page
   acf_add_options_sub_page(array(
	   'page_title' 	=> 'Admin Settings',
	   'menu_title' 	=> 'Admin',
	   'parent_slug' 	=> $parent['menu_slug'],
   ));

    // add sub page
    acf_add_options_sub_page(array(
        'page_title' 	=> 'Newsletter Settings',
        'menu_title' 	=> 'Newsletter',
        'parent_slug' 	=> $parent['menu_slug'],
    ));
   
}

add_action('admin_enqueue_scripts', 'admin_style');
function admin_style() {
    if ( get_field('backend_enable','options')) {
        wp_enqueue_style('admin-styles', get_template_directory_uri().'/dist/admin.css');
        // wp_enqueue_script('admin-scripts', get_template_directory_uri().'/dist/admin.min.js', array('acf-input'));
    }
}

add_action('wp_ajax_storyfilter', 'story_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_storyfilter', 'story_filter_function');
 
function story_filter_function(){
	$args = array(
		'orderby' => 'date', // we will sort posts by date
		'order'	=> $_POST['date'] // ASC or DESC
	);
 
	// for taxonomies / categories
	if( isset( $_POST['categoryfilter'] ) )
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => $_POST['categoryfilter']
			)
		);
 
	$query = new WP_Query( $args );
 
	if( $query->have_posts() ) :
		while( $query->have_posts() ): $query->the_post();
			echo '<h2>' . $query->post->post_title . '</h2>';
		endwhile;
		wp_reset_postdata();
	else :
		echo 'No posts found';
	endif;
 
	die();
}