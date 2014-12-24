<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */

$content_width = 590;

// Setup Clean Home
add_action( 'after_setup_theme', 'cleanhome_theme_setup' );

function cleanhome_theme_setup() {

	// Theme options
	require( get_template_directory() . '/inc/theme-options.php' );

	// Feed
	add_theme_support( 'automatic-feed-links' );

	// Background
	add_theme_support( 'custom-background' );

	// Header Image and Text
	$options = cleanhome_get_theme_options();
	$color_scheme = $options['color_scheme'];
	switch ( $color_scheme ) {
		case 'dark' :
			$header_color = 'ad5a00';
			break;
		case 'snowy' :
			$header_color = '086581';
			break;
		case 'sunny' :
			$header_color = '815303';
			break;
		default:
			$header_color = 'ca1e00';
			break;
	}

	add_theme_support( 'custom-header', apply_filters( 'cleanhome_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => $header_color,
		'width'                  => 900,
		'height'                 => 200,
		'flex-height'            => true,
		'wp-head-callback'       => 'cleanhome_header_style',
		'admin-head-callback'    => 'cleanhome_admin_header_style',
		'admin-preview-callback' => 'cleanhome_admin_header_image',
	) ) );

	add_action( 'init', 'cleanhome_register_menus' );

	add_action( 'widgets_init', 'cleanhome_widgets_init' );

	add_action( 'wp_enqueue_scripts', 'cleanhome_color_registrar' );

	load_theme_textdomain( 'cleanhome', get_template_directory() . '/languages' );
}

/**
 * Enqueue scripts and styles
 */
function cleanhome_scripts() {
	global $wp_styles;

	wp_enqueue_style( 'cleanhome', get_stylesheet_uri() );

	wp_enqueue_style( 'cleanhome-ie', get_template_directory_uri() . '/ie.css', array( 'cleanhome' ) );
	$wp_styles->add_data( 'cleanhome-ie', 'conditional', 'IE' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'cleanhome_scripts' );

function cleanhome_header_style() {
	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#logo h1,
		#logo h2 {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#logo h1 a,
		#logo h2 {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}

// Admin Header
function cleanhome_admin_header_style() {
	?><style type="text/css">
		.appearance_page_custom-header #headimg {
			background: rgba(255, 255, 255, 0.88);
			border: none;
			border-radius: 6px;
			padding: 30px;
			max-width: 900px;
		}
		#headimg h1 {
			margin: 0;
		}
		a#name {
			color: #CA1E00;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 60px;
			font-weight: 600;
			line-height: 22px;
			letter-spacing: -2px;
			text-decoration: none;
			text-rendering: optimizelegibility;
		}
		#desc {
			color: #000;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 18px;
			font-weight: 200;
			padding: 12px 4px 34px 0;
		}
		<?php
		$options = cleanhome_get_theme_options();
		$color_scheme = $options['color_scheme'];

		if ( 'dark' == $color_scheme ) {
			?>
			.appearance_page_custom-header #headimg {
				background: #161616;
			}
			a#name {
				color: #ad5a00;
			}
			#desc {
				color: #777;
			}
			#headimg img {
				border-top: 6px solid #333;
			}
			<?php
		}
		elseif ( 'snowy' == $color_scheme ) {
			?>
			.appearance_page_custom-header #headimg {
				background: rgba(255, 255, 255, 0.7);
			}
			a#name {
				color: #086581;
			}
			#desc {
				color: #263E46;
			}
			#headimg img {
				border-top: 6px solid #333;
			}
			<?php
		}
		elseif ( 'sunny' == $color_scheme ) {
			?>
			.appearance_page_custom-header #headimg {
				background: rgba(255, 255, 255, 0.7);
			}
			a#name {
				color: #815303;
			}
			#desc {
				color: #4B3E27;
			}
			#headimg img {
				border-top: 6px solid #333;
			}
			<?php
		}
		if ( HEADER_TEXTCOLOR != get_header_textcolor() ) {
			?>
			a#name, desc {
				color: #<?php echo get_header_textcolor(); ?>;
			}
			<?php
		}
		if ( 'blank' == get_header_textcolor() ) {
			?>
			a#name, desc {
				display: none;
			}
			<?php
		}
		?>
	</style><?php
}

function cleanhome_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }


// Header navigation menu
function cleanhome_register_menus() {
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'cleanhome' ),
	) );
}

// Menu Fallback
function cleanhome_page_menu() { // fallback for primary navigation ?>
<ul>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }

// Widgets
function cleanhome_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'cleanhome' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'cleanhome' ),
		'before_widget' => '<div id="%1$s" class="widget block %2$s sidebar-box">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	register_sidebar( array(
		'name' => __( 'Header', 'cleanhome' ),
		'id' => 'header',
		'description' => __( 'Header widget area with big font size.', 'cleanhome' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

// Returns the current Clean Home theme options, with default values for fallback
function cleanhome_get_theme_options() {
	$defaults = array(
		'color_scheme' => 'light',
	);
	$options = get_option( 'cleanhome_theme_options', $defaults );

	return $options;
}

// Register our color schemes and add them to the queue
function cleanhome_color_registrar() {
	$options = cleanhome_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme ) {
		wp_register_style( 'dark', get_template_directory_uri() . '/colors/dark.css', null, null );
		wp_enqueue_style( 'dark' );
	}
	if ( 'snowy' == $color_scheme ) {
		wp_register_style( 'snowy', get_template_directory_uri() . '/colors/snowy.css', null, null );
		wp_enqueue_style( 'snowy' );
	}
	if ( 'sunny' == $color_scheme ) {
		wp_register_style( 'sunny', get_template_directory_uri() . '/colors/sunny.css', null, null );
		wp_enqueue_style( 'sunny' );
	}
}

/**
 * Adds custom classes to the array of body classes.
 */
function cleanhome_body_classes( $classes ) {
	$options = cleanhome_get_theme_options();
	$color_scheme = $options['color_scheme'];
	// Add color scheme class.
	switch ( $color_scheme ) {
		case 'dark' :
			$classes[] = 'color-dark';
			break;
		case 'snowy' :
			$classes[] = 'color-snowy';
			break;
		case 'sunny' :
			$classes[] = 'color-sunny';
			break;
		default :
			$classes[] = 'color-light';
			break;
	}
	return $classes;
}
add_filter( 'body_class', 'cleanhome_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Clean Home 1.2.0
 */
function cleanhome_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'cleanhome' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'cleanhome_wp_title', 10, 2 );

/**
 * Load Jetpack compatibility file.
 */
if ( file_exists( get_template_directory() . '/inc/jetpack.php' ) )
	require get_template_directory() . '/inc/jetpack.php';