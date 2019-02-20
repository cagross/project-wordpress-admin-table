<?php
// --------------------------- Campaign Monitor API Keys ----------------------
define('CM_CLIENT_ID', 'fea00c1dedfab1cf2ab82199b10c3b6a');
define('CM_API_KEY', '31c65c39644a18ea0b045af3496ae67ba0bd215dc27932b7');

// --------------------------- Settings Here For Now! -------------------------
define('CM_LOGIN_URL', 'http://newsletter.butleradamshardwoods.com/');
define('SITE_URL', 'https://contacts.butleradamshardwoods.com/');
define('LOGIN_LOGO', get_stylesheet_directory_uri() . '/images/butler-adams-hardwoods.png');
define('LOGIN_LOGO_HEIGHT', '65px');
define('LOGIN_LOGO_WIDTH', '320px');
define('LOGIN_LINK_COLOR', '#F8E3C2');
define('LOGIN_BG_IMAGE', get_stylesheet_directory_uri() . '/images/butler-adamns-login-background.jpg');

// load parent and child css
function enqueue_parent_styles() {
    $parent_theme_version = wp_get_theme( get_template() )->get( 'Version' );
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', '', $parent_theme_version );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles', PHP_INT_MAX );

// inject css for /wp-admin
add_action('admin_head', 'my_custom_css');
function my_custom_css() {
  echo '<style>
    .update-nag { display:none; } 
  </style>';
}
?>