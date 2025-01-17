<?php
/**
 * Blogi functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package Blogi
 */

if ( ! function_exists( 'blogi_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function blogi_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Blogi, use a find and replace
		 * to change 'blogi' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'blogi', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'blogi' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support(
			'post-formats',
			array(
				'image',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'blogi_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		add_theme_support(
			'custom-logo',
			array(
				'height' => 100,
				'width'  => 100,
			)
		);

		add_image_size( 'blogi-post-thumb', 870, 300, true );
	}
endif; // blogi_setup.
add_action( 'after_setup_theme', 'blogi_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blogi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'blogi_content_width', 640 );
}
add_action( 'after_setup_theme', 'blogi_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogi_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'blogi' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'blogi_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function blogi_scripts() {
	$theme_info = wp_get_theme();
	wp_enqueue_style( 'blogi-google-font', blogi_fonts_url(), false, $theme_info->get( 'Version' ) );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), '3.3.7' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', array(), '4.6.3' );
	wp_enqueue_style( 'blogi-style', get_stylesheet_uri(), array( 'bootstrap', 'font-awesome' ), $theme_info->get( 'Version' ) );

	wp_enqueue_script( 'jquery-theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'blogi-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'blogi-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blogi_scripts' );

if ( ! function_exists( 'blogi_fonts_url' ) ) :
	/**
	 * Register Google fonts for Blogi.
	 *
	 * Create your own blogi_fonts_url() function to override in a child theme.
	 *
	 * @since Blogi 1.0.1
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function blogi_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'cyrillic,latin-ext';

		$fonts[] = 'Lora:400,400i,700,700i';

		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family' => rawurlencode( implode( '|', $fonts ) ),
					'subset' => rawurlencode( $subsets ),
				),
				'https://fonts.googleapis.com/css'
			);
		}

		return $fonts_url;
	}
endif;

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer Options
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
