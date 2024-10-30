<?php

$inc_kodeks = get_template_directory() . '/inc/kodeks.php';
if (!file_exists($inc_kodeks)) {

    /* Enable / disable Dashboard */
    if ((!isset($kodeks_options['setting_dashboard']) || $kodeks_options['setting_dashboard'] == 1) && !function_exists( 'kodeks_add_dashboard_widgets' )) {
        // add new dashboard widgets
        function kodeks_add_dashboard_widgets() {
            wp_add_dashboard_widget( 'dashboard_welcome', 'Velkommen', 'kodeks_add_welcome_widget' );
        }

        add_action( 'wp_dashboard_setup', 'kodeks_add_dashboard_widgets' );

        function kodeks_add_welcome_widget(){ ?>
<h1>Hei<?php $current_user = wp_get_current_user();
                                             if ($current_user->user_firstname != '') { 
                                                 echo ' ';
                                                 echo $current_user->user_firstname; echo ','; } else {echo '!';}?></h1>
<p>Vi jobber kontinuerlig for at du skal kunne redigere dine nettsider så enkelt som mulig. Ved hjelp av Wordpress har dere total kontroll over alle deler av deres websider. Du kan selv oppdatere tekst, bilder og dokumenter på alle sider.</p>
<p>Har du spørsmål til løsningen eller ønsker oppdateringer på siden er du velkommen til å ta kontakt med oss.</p>
<p>Epost: <strong><a href="mailto:hjelp@kodeks.no">hjelp@kodeks.no</a></strong><br />
    Telefon:<span class="icon"></span> <strong><a href="tel:+4721000101">+47 21 00 01 01</a></strong> <br/> </p>

<?php
                                             global $kodeks_options;
                                             if (!isset($kodeks_options['setting_news']) || $kodeks_options['setting_news'] == 1) {?>
<hr/>
<h2>Nytt fra Kodeks</h2>
<?php // Get RSS Feed(s)
                                                 include_once( ABSPATH . WPINC . '/feed.php' );

                                                 // Get a SimplePie feed object from the specified feed source.
                                                 $rss = fetch_feed( 'https://kodeks.dev.kodeks.no/feed/' );

                                                 $maxitems = 0;

                                                 if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

                                                 // Figure out how many total items there are, but limit it to 5.
                                                 $maxitems = $rss->get_item_quantity( 3 );

                                                 // Build an array of all the items, starting with element 0 (first element).
                                                 $rss_items = $rss->get_items( 0, $maxitems );

                                                 endif;
?>

<ul>
    <?php if ( $maxitems == 0 ) : ?>
    <li><?php _e( 'Fant ingen nyheter.', 'my-text-domain' ); ?></li>
    <?php else : ?>
    <?php // Loop through each feed item and display each item as a hyperlink. ?>
    <?php foreach ( $rss_items as $item ) : ?>
    <li>
        - <a target="_blank" href="<?php echo esc_url( $item->get_permalink() ); ?>">
        <?php echo esc_html( $item->get_title() ); ?>
        </a>
    </li>
    <?php endforeach; ?>
    <?php endif; ?>
</ul>
<?php
                                             }
                                            }
    }

    /* Enable / disable front page widgets */
    if ((!isset($kodeks_options['setting_fp_widget_disable']) || $kodeks_options['setting_fp_widget_disable'] == 1) && !function_exists( 'kodeks_remove_dashboard_meta' ) ) {
        function kodeks_remove_dashboard_meta() {
            remove_action('welcome_panel', 'wp_welcome_panel');
            remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
            remove_meta_box( 'jetpack_summary_widget', 'dashboard', 'normal' );
            remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' );
            remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
            remove_meta_box( 'aaaa_webappick_latest_news_dashboard_widget', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );

        }
        add_action( 'admin_init', 'kodeks_remove_dashboard_meta' );
    }

    /* Enable / disable branding */
    if (!isset($kodeks_options['setting_branding']) || $kodeks_options['setting_branding'] == 1 && !function_exists( 'kodeks_admin_theme_style' )) {
        /* Admin page style */
        function kodeks_admin_theme_style() {
            wp_enqueue_style('my-admin-style', plugin_dir_url( __FILE__ ) . 'css/admin.css');
        }
        add_action('admin_enqueue_scripts', 'kodeks_admin_theme_style');

        /* Edit footer-text */
        function wpse_edit_footer() {
            add_filter( 'admin_footer_text', 'wpse_edit_text', 11 );
        }

        function wpse_edit_text($content) {
            return "WordPress levert av <a href='https://kodeks.no' target='_blank'>Kodeks AS</a>";
        }
        add_action( 'admin_init', 'wpse_edit_footer' );

        /* Custom logo */
        function my_login_logo() { ?>
<style>
    #login h1 a, .login h1 a {
        background-image: url(https://kodeks.no/media/dist/images/K.png);
        height:140px;
        width:140px;
        background-size: 140px 140px;
        background-repeat: no-repeat;
        padding-bottom: 30px;
    }
    #wpadminbar {
        background:#186962;
    }
</style>
<?php }
        add_action( 'login_enqueue_scripts', 'my_login_logo' );

        // Endre link
        function my_login_logo_url() {
            return home_url();
        }
        add_filter( 'login_headerurl', 'my_login_logo_url' );

        function my_login_logo_url_title() {
            return 'Kodeks';
        }
        add_filter( 'login_headertext', 'my_login_logo_url_title' );

        // Legge til CSS
        function my_login_stylesheet() {
            wp_enqueue_style('custom-login', content_url() . '/plugins/kodeks-dashboard/css/admin.css');
        }
        add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

        // Legge til hjelpetekst login page
        function smallenvelop_login_message( $message ) {
            if ( empty($message) ){
                return "<p class='kodeksInfo'><strong>Kodeks kundesenter: 21 00 01 01 — <a href='mailto:hjelp@kodeks.no'>hjelp@kodeks.no</a></strong></p>";
            } else {
                return $message;
            }
        }
        add_filter( 'login_message', 'smallenvelop_login_message' );
    }

    /* Enable / disable acl */
    if ((!isset($kodeks_options['setting_acl']) || $kodeks_options['setting_acl'] == 1) && !function_exists( 'isa_editor_manage_users' )) {
        /*
	 * Let Editors manage users, and run this only once.
	 */
        function isa_editor_manage_users() {

            if ( get_option( 'isa_add_cap_editor_once' ) != 'done' ) {

                // let editor manage users

                $edit_editor = get_role('editor'); // Get the user role
                $edit_editor->add_cap('edit_users');
                $edit_editor->add_cap('list_users');
                $edit_editor->add_cap('promote_users');
                $edit_editor->add_cap('create_users');
                $edit_editor->add_cap('add_users');
                $edit_editor->add_cap('delete_users');
                $edit_editor->remove_cap( 'unfiltered_html' );

                update_option( 'isa_add_cap_editor_once', 'done' );
            }

        }
        add_action( 'init', 'isa_editor_manage_users' );

        //prevent editor from deleting, editing, or creating an administrator
        // only needed if the editor was given right to edit users

        class ISA_User_Caps {

            // Add our filters
            function __construct() {
                add_filter( 'editable_roles', array(&$this, 'editable_roles'));
                add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
            }
            // Remove 'Administrator' from the list of roles if the current user is not an admin
            function editable_roles( $roles ){
                if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
                    unset( $roles['administrator']);
                }
                return $roles;
            }
            // If someone is trying to edit or delete an
            // admin and that user isn't an admin, don't allow it
            function map_meta_cap( $caps, $cap, $user_id, $args ){
                switch( $cap ){
                    case 'edit_user':
                    case 'remove_user':
                    case 'promote_user':
                        if( isset($args[0]) && $args[0] == $user_id )
                            break;
                        elseif( !isset($args[0]) )
                            $caps[] = 'do_not_allow';
                        $other = new WP_User( absint($args[0]) );
                        if( $other->has_cap( 'administrator' ) ){
                            if(!current_user_can('administrator')){
                                $caps[] = 'do_not_allow';
                            }
                        }
                        break;
                    case 'delete_user':
                    case 'delete_users':
                        if( !isset($args[0]) )
                            break;
                        $other = new WP_User( absint($args[0]) );
                        if( $other->has_cap( 'administrator' ) ){
                            if(!current_user_can('administrator')){
                                $caps[] = 'do_not_allow';
                            }
                        }
                        break;
                    default:
                        break;
                }
                return $caps;
            }

        }

        $isa_user_caps = new ISA_User_Caps();
        // Hide all administrators from user list.
        add_action('pre_user_query','isa_pre_user_query');
        function isa_pre_user_query($user_search) {

            $user = wp_get_current_user();

            if ( ! current_user_can( 'manage_options' ) ) {

                global $wpdb;

                $user_search->query_where = 
                    str_replace('WHERE 1=1', 
                                "WHERE 1=1 AND {$wpdb->users}.ID IN (
			SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
			WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
			AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
                                $user_search->query_where
                               );
            }
        }
    }

    /* Enable / disable emoji */
    if ((!isset($kodeks_options['setting_emoji']) || $kodeks_options['setting_emoji'] == 1) && !function_exists( 'disable_wp_emojicons' ) ) {
        add_filter( 'emoji_svg_url', '__return_false' );
        add_action( 'init', 'disable_wp_emojicons' );

        function disable_wp_emojicons() {
            // all actions related to emojis
            remove_action( 'admin_print_styles', 'print_emoji_styles' );
            remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
            remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
            remove_action( 'wp_print_styles', 'print_emoji_styles' );
            remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
            remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
            remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
            // filter to remove TinyMCE emojis
            add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
        }

        function disable_emojicons_tinymce( $plugins ) {
            if ( is_array( $plugins ) ) {
                return array_diff( $plugins, array( 'wpemoji' ) );
            } else {
                return array();
            }
        }
    }

    /* Enable / disable rest API */
    if (!isset($kodeks_options['setting_api']) || $kodeks_options['setting_api'] == 1) {

        remove_action ('wp_head', 'rsd_link');
        remove_action( 'wp_head', 'wlwmanifest_link');
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
        remove_action('wp_head', 'rest_output_link_wp_head', 10);

        // Sikkerhet - slå av xml-rpc
        // Disable use XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');

        // Disable X-Pingback to header
        if ( !function_exists( 'disable_x_pingback' )){
            add_filter( 'wp_headers', 'disable_x_pingback' );
            function disable_x_pingback( $headers ) {
                unset( $headers['X-Pingback'] );
                return $headers;
            }
        }

        // Turn off oEmbed auto discovery.
        add_filter( 'embed_oembed_discover', '__return_false' );

        // Don't filter oEmbed results.
        remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

        // Remove oEmbed discovery links.
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

        // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );

        /**
	*    Disables WordPress Rest API for external requests
	*/
        // function restrict_rest_api_to_localhost() {
        //     $whitelist = array('127.0.0.1', "::1");
        //
        //     if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        //         die('REST API is disabled.');
        //     }
        // }
        // add_action( 'rest_api_init', 'restrict_rest_api_to_localhost', 1 );
    }

    /* Enable / disable admin-bar */
    if ((!isset($kodeks_options['setting_adminbar']) || $kodeks_options['setting_adminbar'] == 1) && !function_exists( 'remove_admin_bar' ) ) {
        add_filter('show_admin_bar', 'remove_admin_bar');
        function remove_admin_bar() {
            return false;
        }
    }

    /* Enable / disable widgets */
    if ((!isset($kodeks_options['setting_widget']) || $kodeks_options['setting_widget'] == 0) && !function_exists( 'kodeks_widgets_init' )) {
        /**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
        function kodeks_widgets_init() {
            register_sidebar( array(
                'name'          => esc_html__( 'Widgets', 'kodeks' ),
                'id'            => 'widgets',
                'description'   => esc_html__( 'Add widgets here.', 'kodeks' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );
        }
        add_action( 'widgets_init', 'kodeks_widgets_init' );
    }

    /* Gutenberg */

    if ((!isset($kodeks_options['setting_gutenberg']) || $kodeks_options['setting_gutenberg'] == 1) && !function_exists( 'custom_theme_assets' )) {
        add_action( 'enqueue_block_assets', function() {
            // Overwrite Core block styles with empty styles.
            wp_deregister_style( 'wp-block-library' );
            wp_register_style( 'wp-block-library', '' );

            // Overwrite Core theme styles with empty styles.
            wp_deregister_style( 'wp-block-library-theme' );
            wp_register_style( 'wp-block-library-theme', '' );
        }, 10 );

        function custom_theme_assets() {
            wp_dequeue_style( 'wp-block-library' );
        }

        add_action( 'wp_enqueue_scripts', 'custom_theme_assets', 100 );
    }


    /* Enable / disable css */
    if ((!isset($kodeks_options['setting_css']) || $kodeks_options['setting_css'] == 1) && !function_exists( 'prefix_remove_css_section' )) {

        /**
 * Remove the additional CSS section, introduced in 4.7, from the Customizer.
 * @param $wp_customize WP_Customize_Manager
 */
        add_action( 'customize_register', 'prefix_remove_css_section', 15 );
        function prefix_remove_css_section( $wp_customize ) {
            $wp_customize->remove_section( 'custom_css' );
        }
    }

    /* Default */

    // Fjerne static_front_page

    if ( !function_exists( 'prefix_remove_front' )){
        add_action( 'customize_register', 'prefix_remove_front', 15 );
        function prefix_remove_front( $wp_customize ) {
            $wp_customize->remove_section( 'static_front_page' );
        }
    }


    // Fjerne theme-picker
    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

    // Custom logo
    add_theme_support('custom-logo');

    // Slå av editering via admin
    define('DISALLOW_FILE_EDIT', true);


    // remove version from head
    remove_action('wp_head', 'wp_generator');

    // remove version from rss
    add_filter('the_generator', '__return_empty_string');

    // remove version from scripts and styles
    if ( ! function_exists( 'shapeSpace_remove_version_scripts_styles' )){
        function shapeSpace_remove_version_scripts_styles($src) {
            if (strpos($src, 'ver=')) {
                $src = remove_query_arg('ver', $src);
            }
            return $src;
        }
        add_filter('style_loader_src', 'shapeSpace_remove_version_scripts_styles', 9999);
        add_filter('script_loader_src', 'shapeSpace_remove_version_scripts_styles', 9999);
    }


    // Media uploader - enable SVG
    if ( !function_exists( 'cc_mime_types' )){
        function cc_mime_types($mimes) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }
        add_filter('upload_mimes', 'cc_mime_types');
    }

    // Move Yoast to bottom
    if ( !function_exists( 'yoasttobottom' )){
        function yoasttobottom() {
            return 'low';
        }
        add_filter( 'wpseo_metabox_prio', 'yoasttobottom');
    }

    // Remove All Yoast HTML Comments
    // https://gist.github.com/paulcollett/4c81c4f6eb85334ba076
    add_action('wp_head',function() { ob_start(function($o) {
        return preg_replace('/^\n?<!--.*?[Y]oast.*?-->\n?$/mi','',$o);
    }); },~PHP_INT_MAX);


    // STOP TINYMCE CLASSES
    if ( !function_exists( 'customize_tinymce' )){
        add_filter('tiny_mce_before_init', 'customize_tinymce');

        function customize_tinymce($in) {
            $in['paste_preprocess'] = "function(pl,o){ o.content = o.content.replace(/p class=\"p[0-9]+\"/g,'p'); o.content = o.content.replace(/span class=\"s[0-9]+\"/g,'span'); }";
            return $in;
        }
    }

    /* Remove edit-button */
    add_filter( 'edit_post_link', '__return_false' );

    // block WP enum scans
    // https://m0n.co/enum
    if (!is_admin()) {
        // default URL format
        if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
        add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
    }

    if ( ! function_exists( 'shapeSpace_check_enum' )){
        function shapeSpace_check_enum($redirect, $request) {
            // permalink URL format
            if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
            else return $redirect;
        }
    }

    if ( ! function_exists( 'do_stuff' )){
        add_action('init','do_stuff');
        function do_stuff(){
            $current_user = wp_get_current_user();
            // Disable W3TC footer comment for everyone but Admins (single site & network mode)
            if ( !current_user_can( 'activate_plugins' ) ) {
                add_filter( 'w3tc_can_print_comment', '__return_false', 10, 1 );
            }

        }
    }

    // Remove type="javascript/css"

    add_action( 'template_redirect', function(){
        ob_start( function( $buffer ){
            $buffer = str_replace( array( 'type="text/javascript"', "type='text/javascript'" ), '', $buffer );

            // Also works with other attributes...
            $buffer = str_replace( array( 'type="text/css"', "type='text/css'" ), '', $buffer );
            $buffer = str_replace( array( 'frameborder="0"', "frameborder='0'" ), '', $buffer );
            $buffer = str_replace( array( 'scrolling="no"', "scrolling='no'" ), '', $buffer );

            return $buffer;
        });
    });

    // remove links/menus from the admin bar
    if ( ! function_exists( 'mytheme_admin_bar_render' )){
        function mytheme_admin_bar_render() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
            $wp_admin_bar->remove_menu('updates');
        }
        add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );
    }
    if ( ! function_exists( 'jp_wpseo_fat_slicer' )){
        function jp_wpseo_fat_slicer() {

            // Only do this for users who don't have Editor capabilities
            if ( ! current_user_can( 'edit_others_posts' ) ) {
                add_action( 'add_meta_boxes', 'jp_remove_yoast_metabox', 99 );
                add_filter( 'manage_edit-post_columns', 'jp_remove_columns' );
                add_filter( 'manage_edit-page_columns', 'jp_remove_columns' );
                // add_filter( 'manage_edit-CPTNAME_columns', 'jp_remove_columns' ); // Replace CPTNAME with your custom post type name, for example "restaurants".
            }
        }
        add_action( 'init', 'jp_wpseo_fat_slicer' );
    }


    /**
 * Removes the WordPress SEO meta box from posts and pages
 *
 * @since 1.0.0
 * @uses remove_meta_box()
 */
    if ( ! function_exists( 'jp_remove_yoast_metabox' )){
        function jp_remove_yoast_metabox() {

            $post_types = array( 'page', 'post' ); // add any custom post types here

            foreach( $post_types as $post_type ) {
                remove_meta_box( 'wpseo_meta', $post_type, 'normal' );
            }
        }
    }


    /**
 * Removes the SEO item from the admin bar
 *
 * @since 1.0.0
 * @uses remove_menu
 */
    if ( ! function_exists( 'jp_admin_bar_seo_cleanup' )){
        function jp_admin_bar_seo_cleanup() {

            global $wp_admin_bar;
            $wp_admin_bar->remove_menu( 'wpseo-menu' );
        }
    }



    /**
 * Removes the extra columns on the post/page listing screens.
 *
 * @since 1.0.0
 */
    if ( ! function_exists( 'jp_remove_columns' )){
        function jp_remove_columns( $columns ) {

            unset( $columns['wpseo-score'] );
            unset( $columns['wpseo-title'] );
            unset( $columns['wpseo-metadesc'] );
            unset( $columns['wpseo-focuskw'] );

            return $columns;
        }
    }

    /** Hide update nag
 */
    if ( ! function_exists( 'hide_update_notice_to_all_but_admin_users' )){
        function hide_update_notice_to_all_but_admin_users()
        {
            if (!current_user_can('update_core')) {
                remove_action( 'admin_notices', 'update_nag',      3  );
                remove_action( 'admin_notices', 'maintenance_nag', 10 );
            }
        }
        add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );
    }

    /* Show current teplate --- echo get_current_template( true ); */

    if ( ! function_exists( 'var_template_include' )){
        add_filter( 'template_include', 'var_template_include', 1000 );
        function var_template_include( $t ){
            $GLOBALS['current_theme_template'] = basename($t);
            return $t;
        }
    }

    if ( !function_exists( 'get_current_template' )){
        function get_current_template( $echo = false ) {
            if( !isset( $GLOBALS['current_theme_template'] ) )
                return false;
            if( $echo )
                echo $GLOBALS['current_theme_template'];
            else
                return $GLOBALS['current_theme_template'];
        }
    }

    if ( !function_exists( 'is_post_type' )){
        function is_post_type($type){
            global $wp_query;
            if($type == get_post_type($wp_query->post->ID)) return true;
            return false;
        } 
    }

    // Add options page

    if( function_exists('acf_add_options_page') ) {

        acf_add_options_page();

    }

    if ( ! function_exists( 'kodeks_setup' ) ) :
    /**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
    function kodeks_setup() {
        /*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Kodeks, use a find and replace
	 * to change 'kodeks' to the name of your theme in all the template files.
	 */
        load_theme_textdomain( 'kodeks', get_template_directory() . '/languages' );

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

        /*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );
    }
    endif;
    add_action( 'after_setup_theme', 'kodeks_setup' );


    /**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
    if ( ! function_exists( 'kodeks_content_width' )){
        function kodeks_content_width() {
            $GLOBALS['content_width'] = apply_filters( 'kodeks_content_width', 640 );
        }
        add_action( 'after_setup_theme', 'kodeks_content_width', 0 );
    }

    /**
 * Enqueue scripts and styles.
 */
    if ( ! function_exists( 'kodeks_scripts' )){
        function kodeks_scripts() {
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }
        }
        add_action( 'wp_enqueue_scripts', 'kodeks_scripts' );
    };

    // post thumbnails support
    add_theme_support( 'post-thumbnails' );

    // Make editors able to edit privacy page

    add_action('map_meta_cap', 'custom_manage_privacy_options', 1, 4);
    function custom_manage_privacy_options($caps, $cap, $user_id, $args)
    {
        if ('manage_privacy_options' === $cap) {
            $manage_name = is_multisite() ? 'manage_network' : 'manage_options';
            $caps = array_diff($caps, [ $manage_name ]);
        }
        return $caps;
    }

    add_filter( 'Yoast\WP\ACF\refresh_rate', function () {
        // Refresh rates in milliseconds
        return 20000;
    });


    /**
 * Extend WordPress search to include custom fields
 *
 * https://adambalee.com
 */

    /**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */

    $search_plugin = false;
    if(in_array('acf-better-search/acf-better-search.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $search_plugin = true;
    };

    if ( !function_exists( 'kodeks_search_join' ) && $search_plugin == false ) {

        function kodeks_search_join( $join ) {
            global $wpdb;

            if ( is_search() ) {  
                $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
            }

            return $join;
        }
        add_filter('posts_join', 'kodeks_search_join' );

    }

    /**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */

    if ( ! function_exists( 'kodeks_search_where' ) && $search_plugin == false ) {
        function kodeks_search_where( $where ) {
            global $pagenow, $wpdb;

            if ( is_search() ) {
                $where = preg_replace(
                    "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
                    "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
            }

            return $where;
        }
        add_filter( 'posts_where', 'kodeks_search_where' );
    }

    /**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
    if ( !function_exists( 'kodeks_search_distinct' ) && $search_plugin == false ) {
        function kodeks_search_distinct( $where ) {
            global $wpdb;

            if ( is_search() ) {
                return "DISTINCT";
            }

            return $where;
        }
        add_filter( 'posts_distinct', 'kodeks_search_distinct' );
    }

}
?>