<?php
/* --------------------------- Redirects ------------------------- */

// restrict access for editors
function restrict_menus() {
   if (!current_user_can('manage_options') && is_user_logged_in()) {
        $path = get_site_url();
        $screen = get_current_screen();
        $base   = $screen->id;

        if( 'profile' == $base || 'edit-contact' == $base  || 'contact' == $base || 'edit-category' == $base ) {
            // only load these pages
        } else {
            wp_redirect($path.'/wp-admin/edit.php?post_type=contact');
        }
    }
}
add_action( 'current_screen', 'restrict_menus' );



/* --------------------------- Login Page ------------------------- */

// adding the login form custom css
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url('<?php echo LOGIN_LOGO; ?>');
    		height:<?php echo LOGIN_LOGO_HEIGHT; ?>;
    		width:<?php echo LOGIN_LOGO_WIDTH; ?>;
    		background-size: <?php echo LOGIN_LOGO_WIDTH . ' ' . LOGIN_LOGO_HEIGHT; ?>;
    		background-repeat: no-repeat;
        	padding-bottom: 10px;
        }

        .login #login #nav a, .login #login #backtoblog a {
            color: <?php echo LOGIN_LINK_COLOR; ?>;
        }

        body.login {
            background-image: url('<?php echo LOGIN_BG_IMAGE; ?>');
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// change the link in on the logo
function my_login_logo_url() {
    return SITE_URL;
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

// hide back to blog link on login form
function custom_login_css() {
    echo '<style type="text/css">#backtoblog { display:none; }</style>';
}
add_action('login_head', 'custom_login_css');



/* ------------------ Contacts Post Type ---------------- */

// creating the contact post type
function create_post_type() {
	$labels = array(
            'name'              => _x( 'Contacts', 'post type general name' ),
            'singular_name'     => _x( 'Contact', 'post type singular name' ),
            'add_new'           => __( 'Add New' ),
            'add_new_item'      => __( 'Add New' ),
            'edit_item'         => __( 'Edit Contact' ),
            'new_item'          => __( 'New Contact' ),
            'view_item'         => __( 'View Contact' ),
            'search_items'      => __( 'Search Contacts' ),
            'not_found'         => __( 'No Contacts found' ),
            'not_found_in_trash'=> __( 'No Contacts found in Trash' ),
            'parent_item_colon' => __( '' ),
            'menu_name'         => __( 'Contacts' )
        );

        $taxonomies = array( 'category' );

        $post_type_args = array(
            'labels'            => $labels,
            'singular_label'    => __('Contact'),
            'public'            => true,
            'show_ui'           => true,
            'publicly_queryable'=> true,
            'query_var'         => true,
            'capability_type'   => 'post',
            'has_archive'       => false,
            'hierarchical'      => true,
            'supports'          => $supports,
            'menu_position'     => 5,
            'taxonomies'      	=> $taxonomies,
			'show_in_nav_menus'	=> true,
			'rewrite'			=> false,
			'menu_icon'         => 'dashicons-groups'
         );
    register_post_type('contact',$post_type_args);
}
add_action( 'init', 'create_post_type' );

// rename categories to groups to represent email groups
function rename_categories() {
    global $wp_taxonomies;
    $labels = &$wp_taxonomies['category']->labels;
    $labels->name = 'Groups';
    $labels->singular_name = 'Group';
    $labels->add_new = 'Add Group';
    $labels->add_new_item = 'Add Group';
    $labels->edit_item = 'Edit Group';
    $labels->new_item = 'Group';
    $labels->view_item = 'View Group';
    $labels->search_items = 'Search Groups';
    $labels->not_found = 'No Groups found';
    $labels->not_found_in_trash = 'No Groups found in Trash';
    $labels->all_items = 'All Groups';
    $labels->menu_name = 'Groups';
    $labels->name_admin_bar = 'Groups';
}
add_action( 'init', 'rename_categories' );



/* ------------------ Edit Contact Page Visual Edits---------------- */

// remove parent drop down from the edit contact page
function my_admin_add_js() {
	$screen = get_current_screen();
	if ($screen->id == 'contact') {
		echo "<script>document.getElementById('newcategory_parent').remove()</script>
		      <script>document.getElementById('titlediv').remove(); </script>";
		      wp_enqueue_script('inputmask', get_template_directory_uri() . '/js/jquery.inputmask.bundle.min.js', array('jquery'));
              wp_enqueue_script('admin', get_template_directory_uri() . '/js/admin.js', array('jquery'));
	}
}
add_action('admin_footer', 'my_admin_add_js');

// remove parent drop down from the groups and categories page
function my_remove_parent_category() {
    // don't run if not group
    if ('category' != $_GET['taxonomy'])
        return;

    $parent = 'parent()';
    if ( isset( $_GET['action'] ) )
        $parent = 'parent().parent()';
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                $('label[for=parent]').<?php echo $parent; ?>.remove();
            });
        </script>
    <?php
}
add_action( 'admin_head-edit-tags.php', 'my_remove_parent_category' );



/* ------------------ Edit Contacts Page Functional Edits ---------------- */

// set the value of the hidden_post_id field to the current post id (need this for query)
function set_hidden_post_id_value($value, $post_id, $field) {
    return $post_id;
}
add_filter('acf/load_value/name=hidden_post_id', 'set_hidden_post_id_value', 10, 3);

// hiding the hidden post id field and some contents from publish meta box
function hide_hidden_post_id() {
    ?>
    <style type="text/css">
        #acf-hidden_post_id, #acf-hidden_categories, #minor-publishing {
            display: none;
        }
    </style>
    <?php
}
add_action('admin_head', 'hide_hidden_post_id');

// starting js validate email field is unique
function contacts_form_logic() {
    $screen = get_current_screen();
	if ($screen->id == 'contact') {
    ?>
    <script type='text/javascript'>
        // setting event listener
        function set_contacts_form_logic() {
            document.getElementById('acf-field-email').addEventListener("blur", contacts_form_logic);
        }


        // ajax call goes here
        function contacts_form_logic() {
            var email = document.getElementById('acf-field-email').value;
            var post_id = document.getElementById('acf-field-hidden_post_id').value;
            if (email !== "") {
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        'action': 'unique_email',
                        'email':   email,
                        'post_id': post_id
                    },
                    success: function(data){
                        //hide error message if email is unique
                        if (data == "0") {
                            jQuery('#acf-field-email-error').hide();
                        } else {
                            if (jQuery('#acf-field-email-error').length == 0) {
                                jQuery("#acf-email").append( "<div id='acf-field-email-error' style='color: red;'>Email already exists for another contact.</div>" );
                            } else {
                                jQuery('#acf-field-email-error').show();
                            }
                        }
                        //console.log(data);
                    }
                });
            } else {
                if (jQuery('#acf-field-email-error').length !== 0) {
                     jQuery('#acf-field-email-error').hide();
                }
            }
        }
        window.onload = set_contacts_form_logic;
    </script>
    <?php
        wp_enqueue_script('inputmask', get_template_directory_uri() . '/js/jquery.inputmask.bundle.min.js', array('jquery'));
	}
}
add_action('admin_enqueue_scripts', 'contacts_form_logic');

// function called by ajax to check for unique email
function unique_email(){
    $email = $_POST['email'];
    $post_id = $_POST['post_id'];
    $query = query_posts(array(
        'post_type'     => 'contact',
        'post__not_in'  => array($post_id),
        'posts_per_page'=> 1,
        'meta_query'    => array(
            array(
                'key'     => 'email',
                'value'   => $email
            )
        )
    ));
    if (count($query)) {
        echo "1";
    } else {
        echo "0";
    }
    die();
}
add_action('wp_ajax_unique_email', 'unique_email');

// set contact title to last, first / last / first / company or Unnamed if no name
function save_title() {
    $contact_title = "";
    if ($_POST['post_type'] == 'contact') {
        if (!empty($_POST['fields']['field_5a0b3ecc0e0d0']) && !empty($_POST['fields']['field_5a0b3e670e0cf'])) {
            // set to "Last, First"
            $contact_title = $_POST['fields']['field_5a0b3ecc0e0d0'].', '.$_POST['fields']['field_5a0b3e670e0cf'];
        }
        else if (empty($_POST['fields']['field_5a0b3e670e0cf']) && !empty($_POST['fields']['field_5a0b3ecc0e0d0'])){
            // set to "Last"
            $contact_title = $_POST['fields']['field_5a0b3ecc0e0d0'];
        }
        else if (empty($_POST['fields']['field_5a0b3ecc0e0d0']) && !empty($_POST['fields']['field_5a0b3e670e0cf'])){
            // set to "First"
            $contact_title = $_POST['fields']['field_5a0b3e670e0cf'];
        }
        else if (empty($_POST['fields']['field_5a0b3ecc0e0d0']) && empty($_POST['fields']['field_5a0b3e670e0cf']) && !empty($_POST['fields']['field_5a13796241897'])) {
            // set to "Company"
            $contact_title = $_POST['fields']['field_5a13796241897'];
        }
        else {
            $contact_title = "Unnamed";
        }
    }
    return $contact_title;
}
add_filter('title_save_pre', 'save_title');


/* ------------------ Contacts Table ---------------- */

// make sortable by title, email and company
function set_custom_contact_sortable_columns( $columns ) {
    $columns['title'] = 'title';
    $columns['email'] = 'email';
    $columns['company'] = 'company';

    return $columns;
}
add_filter( 'manage_edit-contact_sortable_columns', 'set_custom_contact_sortable_columns' );

// make sorting work with Advanced Custom Fields meta data
function mycpt_custom_orderby( $query ) {
    if ( ! is_admin() )
        return;
    
    $orderby = $query->get( 'orderby');
    
    if ( 'email' == $orderby ) {
        $query->set( 'meta_key', 'email' );
        $query->set( 'orderby', 'meta_value' );
    }
    else if( 'company' == $orderby ) {
        $query->set( 'meta_key', 'company');
        $query->set( 'orderby', 'meta_value');
    } else {
        $query->set( 'orderby', 'title');
    }
}
add_action( 'pre_get_posts', 'mycpt_custom_orderby' );

// customizing the columns on the contact post table
function my_edit_contact_columns( $columns ) {
    $new_columns = array(
        'cb'        => __( 'cb' ),
		'title' 	=> __( 'Contact' ),
		'company'   => __( 'Company' ),
		'email'		=> __( 'Email'),
		'phone'     => __( 'Phone' )
	);
    $new_columns['categories'] = __( 'Groups' );

	return $new_columns;
}
add_filter( 'manage_contact_posts_columns', 'my_edit_contact_columns' ) ;

// This really caused more issues than it fixed. Review with Matt and remove?
//
// // if the title is empty, use the custom meta as the title
// add_filter( 'the_title', 'my_custom_title', 10, 2 );
// function my_custom_title($title, $id) {
//     $first = get_post_meta($id, 'first_name', true);
//     $last = get_post_meta($id, 'last_name', true);
//     $company = get_post_meta($id, 'company', true);
//     $new_title = 'Unnamed';
//     if (!empty($last) && !empty($first)) {
//         $new_title = $last . ', ' . $first;
//     } else if (empty($last) && !empty($first)) {
//         $new_title = $first;
//     } else if (!empty($last) && empty($first)) {
//         $new_title = $last;
//     } else if (!empty($company)) { 
//         $new_title = $company;
//     } else if (!empty($title)) {
//         $new_title = $title;
//     }
//     return $new_title;
// }

// add content into customized columns on the contact post table
function my_manage_contact_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'email' :
		    $email = get_post_meta( $post_id, 'email', true );

			// if no email is found, output a default message
			if ( empty( $email ) )
				echo __( '----' );
			else
				echo __( $email );
			break;

		case 'phone' :
		    if (get_post_meta( $post_id, 'mobile_phone', true ) != "") {
		        $phone = get_post_meta( $post_id, 'mobile_phone', true );
		    } else if (get_post_meta( $post_id, 'home_phone', true ) != "") {
		        $phone = get_post_meta( $post_id, 'home_phone', true );
		    } else if (get_post_meta( $post_id, 'business_phone', true ) != "") {
		        $phone = get_post_meta( $post_id, 'business_phone', true );
		    } else {
		        $phone = get_post_meta( $post_id, 'other_phone', true );
		    }

			// if no phone # is found, output a default message
			if ( empty( $phone ) )
				echo __( '----' );
			else
				echo __( $phone );
			break;

        case 'company' :
		    $company = get_post_meta( $post_id, 'company', true );

			// if no company is found, output a default message
			if ( empty( $company ) )
				echo __( '----' );
			else
				echo __( $company );
			break;

		default :
			break;
	}
}
add_action( 'manage_contact_posts_custom_column', 'my_manage_contact_columns', 10, 2 );

// removing quick edit and view
function my_disable_quick_edit( $actions = array(), $post = null ) {
    unset( $actions['view'] );
    unset( $actions['inline hide-if-no-js'] );

    return $actions;
}
add_filter( 'page_row_actions', 'my_disable_quick_edit', 10, 2 );

// remove date filter from top of table
function remove_date_drop(){
$screen = get_current_screen();
    if ( 'contact' == $screen->post_type ){
        add_filter('months_dropdown_results', '__return_empty_array');
    }
}
add_action('admin_head', 'remove_date_drop');

// make email, phone number, and name searchable
function contacts_search_query( $query ) {
    $custom_fields = array(
        // put all the meta fields you want to search for here
        "first_name",
        "last_name",
        "email",
        "mobile_phone",
        "home_phone",
        "business_phone",
        "other_phone",
        "company",
        "title",
        "notes"

    );
    $searchterm = $query->query_vars['s'];

    // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
    $query->query_vars['s'] = "";

    if ($searchterm != "") {
        $meta_query = array('relation' => 'OR');
        foreach($custom_fields as $cf) {
            array_push($meta_query, array(
                'key' => $cf,
                'value' => $searchterm,
                'compare' => 'LIKE'
            ));
        }
        $query->set("meta_query", $meta_query);
    };
}
add_filter( "pre_get_posts", "contacts_search_query");

// hide search results subheading
function hide_subheading() {
	$screen = get_current_screen();
	if ($screen->id == 'edit-contact') { ?>
    	<style type="text/css">
    		span.subtitle {
				display:none !important;
			}
		</style>
		<?php
	}
}
add_action('admin_head','hide_subheading');



/* ------------------ Admin Panel ---------------- */

// customizing the admin panel for editors
function my_remove_menu_pages() {
    global $menu;
    if (!current_user_can('manage_options')) {
        remove_menu_page('tools.php');
        remove_menu_page('edit-comments.php');
	    remove_menu_page('upload.php');
	    remove_menu_page('edit.php');
		remove_menu_page('edit.php?post_type=page');
		remove_menu_page('index.php');
		unset($menu[4]);
    }
}
add_action( 'admin_menu', 'my_remove_menu_pages' );

// add quick link to launch CM
function add_email_link_fxn(){
	add_menu_page( '', 'Send Email &rarr;', 'edit_pages', 'send_email', '', 'dashicons-email-alt', 5 );
}
add_action( 'admin_menu', 'add_email_link_fxn' );

function tweak_email_link_fxn(){
	echo '<script>
	  jQuery(document).ready(function() {
        jQuery("a.toplevel_page_send_email").attr("href","' . CM_LOGIN_URL . '");
        jQuery("a.toplevel_page_send_email").attr("target","_blank");
      });
	</script>';
}
add_action('admin_head','tweak_email_link_fxn');


// customize the admin top bar
function my_admin_bar_remove_menu() {
	global $wp_admin_bar;
	if (!current_user_can('manage_options')) {
		$wp_admin_bar->remove_menu('new-post');
		$wp_admin_bar->remove_menu('new-media');
		$wp_admin_bar->remove_menu('new-page');
		$wp_admin_bar->remove_menu('comments');
		$wp_admin_bar->remove_menu('wporg');
    	$wp_admin_bar->remove_menu('documentation');
    	$wp_admin_bar->remove_menu('support-forums');
    	$wp_admin_bar->remove_menu('feedback');
    	$wp_admin_bar->remove_menu('wp-logo');
    	$wp_admin_bar->remove_menu('view-site');
	}
}
add_action( 'wp_before_admin_bar_render', 'my_admin_bar_remove_menu' );

// remove jetpack for editor
function remove_jetpack( $caps, $cap, $user_id, $args ) {
    if ( 'jetpack_admin_page' === $cap ) {
        $caps[] = 'manage_options';
    }
    return $caps;
}
add_filter( 'map_meta_cap', 'remove_jetpack', 10, 4 );



/* ----------------- Admin Footer --------------- */
// admin footer text modification
function edit_footer_text_admin ()  {
    echo '<span id="footer-thankyou">Contact Database by <a href="https://www.supint.com/" target="_blank">Superlative</a></span>
		  <p id="footer-upgrade"></p>';
}
add_filter('admin_footer_text', 'edit_footer_text_admin');

// admin footer version removal
function admin_footer_version_removal () {
    remove_filter( 'update_footer', 'core_update_footer' );
}
add_action( 'admin_menu', 'admin_footer_version_removal' );



/* ----------------- Editor Profile Page --------------- */

function set_editor_profile() {
	if (!current_user_can('manage_options')) { ?>
    	<style type="text/css">
    		.user-rich-editing-wrap, .user-comment-shortcuts-wrap, .show-admin-bar, .user-admin-bar-front-wrap,
    		.user-nickname-wrap, .user-display-name-wrap, .user-url-wrap, .user-description-wrap, #profile-page h2 {
				display:none !important;
			}
		</style>
		<?php
	}
}
add_action('admin_head','set_editor_profile');



/* ---------------------- Campaign Monitor --------------------*/

// creates full name (First Last)
function get_full_name($first, $last) {
    return implode(' ', array_filter(array($first, $last)));
}

// creates new list when new group is created
function create_campaign_list($id) {
    require_once 'createsend-php-5.1.2/csrest_lists.php';
    $auth = array('api_key' => CM_API_KEY);
    $wrap = new CS_REST_Lists(NULL, $auth);

    $name = get_the_category_by_ID($id);

    $result = $wrap->create(CM_CLIENT_ID, array(
        'Title' => $name,
        'UnsubscribePage' => null,
        'ConfirmedOptIn' => false,
        'ConfirmationSuccessPage' => null,
        'UnsubscribeSetting' => CS_REST_LIST_UNSUBSCRIBE_SETTING_ONLY_THIS_LIST
    ));

    // save list ID as category meta
    add_term_meta($id, 'list_id', $result->response);
}
add_action('create_category', 'create_campaign_list');

// edits list when group is edited
function update_campaign_list($id) {
    require_once 'createsend-php-5.1.2/csrest_lists.php';
    $auth = array('api_key' => CM_API_KEY);
    $wrap = new CS_REST_Lists(get_term_meta($id, 'list_id', true), $auth);

    $name = get_the_category_by_ID($id);

    $result = $wrap->update(array(
        'Title' => $name,
        'UnsubscribePage' => null,
        'ConfirmedOptIn' => false,
        'ConfirmationSuccessPage' => null,
        'UnsubscribeSetting' => CS_REST_LIST_UNSUBSCRIBE_SETTING_ONLY_THIS_LIST
    ));
}
add_filter('edited_category', 'update_campaign_list');

// deletes list when group is deleted
function delete_campaign_list($id, $taxonomy) {
    if ($taxonomy === 'category') {
        require_once 'createsend-php-5.1.2/csrest_lists.php';
        $auth = array('api_key' => CM_API_KEY);
        $wrap = new CS_REST_Lists(get_term_meta($id, 'list_id', true), $auth);

        $result = $wrap->delete();
    }
}
add_action('pre_delete_term', 'delete_campaign_list', 10, 2);

// add a contact to a list or update an existing contact
function add_contact_to_list($cat_id, $email, $name) {
    require_once 'createsend-php-5.1.2/csrest_subscribers.php';
    $auth = array('api_key' => CM_API_KEY);

    $wrap = new CS_REST_Subscribers(get_term_meta($cat_id, 'list_id', true), $auth);
    $result = $wrap->add(array(
        'EmailAddress' => $email,
        'Name' => $name,
        'Resubscribe' => true
    ));
}

// remove a contact from a list
function delete_contact_from_list($cat_id, $email) {
    require_once 'createsend-php-5.1.2/csrest_subscribers.php';
    $auth = array('api_key' => CM_API_KEY);

    $wrap = new CS_REST_Subscribers(get_term_meta($cat_id, 'list_id', true), $auth);
    $result = $wrap->delete($email);
}

// links contacts to new lists or updates them in existing lists
function pre_contact_update($post_id, $data) {

    // Check if user has permissions to save data.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Check if not an autosave.
    if ( wp_is_post_autosave( $post_id ) ) {
        return;
    }

    // Check if not a revision.
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    if (!empty($_POST) && $data['post_type'] == 'contact') {
        // get category info first because we can cut processing short if there are no categories to deal with
        $old_cats = wp_get_post_categories($post_id);
        $cats = !empty($_POST['post_category']) ? array_filter($_POST['post_category']) : array();
        if (empty($old_cats) && empty($cats)) return;

        // get the rest of the old contact data
        $old_name = get_the_title($post_id);
        $old_email = get_post_meta($post_id, 'email', true);

        // and the new
        $email = $first_name = $last_name = '';
        foreach ($_POST['fields'] as $name => $value) {
            $field = get_field_object($name);
            switch ($field['name']) {
                case 'email':
                    $email = sanitize_text_field($value);
                    break;
                case 'first_name':
                    $first_name = sanitize_text_field($value);
                    break;
                case 'last_name':
                    $last_name = sanitize_text_field($value);
                    break;
            }
        }
        $name = get_full_name($first_name, $last_name);

        // get the categories to add and delete
        $to_add = array_diff($cats, $old_cats);
        $to_delete = array_diff($old_cats, $cats);

        // if categories weren't changed by email or name were changed, we still want to update the lists
        $updated_email = !empty($old_email) && $old_email != $email; // !empty($old_email) tells us if this is a new post or an edit
        $updated_name = !empty($old_email) && $old_name != $name; // !empty($old_email) tells us if this is a new post or an edit
        if (empty($to_add) && !empty($cats) && ($updated_email || $updated_name)) {
            $to_add = $cats;
        }

        if (!empty($to_add)) {
            // add this contact to each list
            foreach ($to_add as $cat_id) {
                // if email was changed, we need to remove the old email
                if ($updated_email) {
                    delete_contact_from_list($cat_id, $old_email);
                }
                add_contact_to_list($cat_id, $email, $name);
            }
        }
        if (!empty($to_delete)) {
            // remove this contact from each list
            foreach ($to_delete as $cat_id) {
                delete_contact_from_list($cat_id, $old_email);
            }
        }
    }
}
// use this hook for post edit
add_action('pre_post_update', 'pre_contact_update', 10, 2);

// use this hook for bulk edit
add_action('set_object_terms', 'contact_set_object_terms', 10, 6);
function contact_set_object_terms($object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids) {
    if (!empty($_GET)) {
        // get category info first because we can cut processing short if there are no categories to deal with
        $old_cats = $old_tt_ids;
        $cats = $tt_ids;
        if (empty($old_cats) && empty($cats)) return;

        // get the rest of the old contact data
        $name = get_the_title($object_id);
        $email = get_post_meta($object_id, 'email', true);

        // get the categories to add and delete
        $to_add = array_diff($cats, $old_cats);
        $to_delete = array_diff($old_cats, $cats);

        if (!empty($to_add)) {
            // add this contact to each list
            foreach ($to_add as $cat_id) {
                add_contact_to_list($cat_id, $email, $name);
            }
        }

        if (!empty($to_delete)) {
            // remove this contact from each list
            foreach ($to_delete as $cat_id) {
                delete_contact_from_list($cat_id, $email);
            }
        }
    }
}

// remove contact from all lists when they're trashed
function contact_trash($post_id) {
    if (get_post_type($post_id) === 'contact') {
        $cats = wp_get_post_categories($post_id);
        foreach ($cats as $cat_id) {
            delete_contact_from_list($cat_id, get_post_meta($post_id, 'email', true));
        }
    }
}
add_action('wp_trash_post', 'contact_trash');

// re-add contact to list when untrashed
function contact_restore($post_id) {
    if (get_post_type($post_id) === 'contact') {
        $cats = wp_get_post_categories($post_id);
        foreach ($cats as $cat_id) {
            add_contact_to_list($cat_id, get_post_meta($post_id, 'email', true), get_the_title($post_id));
        }
    }
}
add_action('untrash_post', 'contact_restore');

/******************************************************/





/*Begin code to add 'Reports' admin section.*/
if(!class_exists('WP_List_Table')){
    // require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    include_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/*Create a new list table package that extends the core WP_List_Table class.*/
class TV_List_Table extends WP_List_Table {
    function __construct(){
        global $status, $page;
        
        parent::__construct( array(
            'singular'  => 'Contact',     //singular name of the listed records
            'plural'    => 'contacts',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    protected $post_count_total;//Define a variable to store the total post count, which will be passed between different methods.
    protected $all_data;//Define a variable to store the query results, which will be passed between different methods.
    protected $all_users;//Define a variable to store the array of users, which will be passed between different methods.

    /* This method is called when the parent class can't find a method specifically build for a given column.*/
    function column_default($item, $column_name){
        $msg_error = "No available data.";
        $user = get_user_by( 'id', $item['post_author'] );
        switch($column_name){
            case 'contact_name':
				return print_r($user->user_nicename,true);
            case 'date_created':				
                    return print_r($item['post_date'],true);
            default:
				return print_r($msg_error,true);
        }
    }

    /* This method is responsible for rendering the 'Contact Name' column.*/
    function column_title($item){
        return sprintf('%1$s',$item['post_title']);
	}
	
    /*This method dictates the table's column slugs and headers.*/
    function get_columns(){
        $columns = array(
            'title'         => 'Contact Name',
            'date_created'  => 'Date Created',
            'contact_name'  => 'Created By'
        );
        return $columns;
	}
	
    /*This method defines which columns are sortable.*/
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'         => array('title',false),     //true means it's already sorted
            'contact_name'  => array('contact_name',false),
            'date_created'  => array('date_created',false)
        );
        return $sortable_columns;
    }

   
    /*This method defines the 'Views' links, which appear directly above the table.  They allow the admin to filter the table by specific users.*/
    function get_views() {

        $total_data = $this->all_data;

        $views = array();
        $current = ( !empty($_REQUEST['customvar']) ? $_REQUEST['customvar'] : 'all');
        
        // include_once(ABSPATH . 'wp-content/themes/contacts-db-child/users.php'); 
        include(ABSPATH . 'wp-content/themes/contacts-db-child/users.php'); 

        $users = $this->all_users;

        $total_user_posts = $this->post_count_total;

        $class = ($current == 'all' ? ' class="current"' :'');
        $all_url = remove_query_arg('customvar');

        $views['all'] = "<a href='{$all_url }' {$class} >All (". $total_user_posts . ")</a>";
        // $views['all'] = "<a href='{" . esc_url($all_url) . " }' {$class} >All (". $total_user_posts . ")</a>";

        for ($i = 0; $i <= count($users)-1; $i++) {
            $arr2 = array(
                array('post_author' => $users[$i])
            );

            $intersect = array_uintersect($total_data, $arr2, 'compareDeepValue');
            $total_posts = count($intersect);

            if ($total_posts > 0) {
                $user = get_user_by( 'id', $users[$i] );
                $user_full = $user->user_nicename;
                $url = add_query_arg('customvar',$user_full);
                $class = ($current == $user_full ? ' class="current"' :'');
                $views[$user_full] = "<a href='{$url}' {$class} >". $user_full . " (" . $total_posts . ")" . "</a>";
            }
        }
        return $views;
    }
   
    /*Add extra markup in the toolbars before or after the list.*/
    function extra_tablenav( $which ) {
        if ( $which == "top" ){
            $customvar = ( isset($_REQUEST['customvar']) ? $_REQUEST['customvar'] : 'all');
            $this->views();
        }
        if ( $which == "bottom" ){
            //The code that goes after the table is here
        }
    
    /*Method to prepare the data that will be displayed in the table.*/}
    function prepare_items() {
        global $wpdb;
        $per_page = 100;//Set how many records to display per page.
        
        // include_once(ABSPATH . 'wp-content/themes/contacts-db-child/users.php');
        include(ABSPATH . 'wp-content/themes/contacts-db-child/users.php');

        /*Define the column headers, including the sortable and hidden columns (if any).*/
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        if (isset($_GET['date-start']) || isset($_GET['date-end'])) {

            $date_s = $_GET['date-start'];
            $date_e = $_GET['date-end'];

            if (!$date_e) {
                $date_e = date('Y-m-d');
            } elseif (!$date_s) {
                $date_s = date('Y-m-d',0);
            }
            $date_s = strtotime($date_s);
            $date_e = strtotime($date_e);
            
            $date_s = date('Y-m-d', $date_s);
            $date_e = date('Y-m-d', $date_e);

        } else {
            $date_s = date('Y-m-d',0);
            $date_e = date('Y-m-d');
        }

        $current = ( !empty($_REQUEST['customvar']) ? $_REQUEST['customvar'] : 'all');

        if ($current != 'all') {
            $curr_user = get_user_by('login',$current);
            $users = array_values(array_intersect($users,array($curr_user->id)));
        }

        $sel_users = "";
        for ($i = 0; $i <= count($users)-1; $i++) {
            if ($i == count($users)-1) {
                    $sel_users .= "P.post_author = ". $users[$i];
            } else {
                    $sel_users .= "P.post_author = ". $users[$i] . " or ";
            }
        }

        $user_query = "SELECT P.ID, P.post_title, P.post_date, P.post_author
            FROM wp_posts AS P
            WHERE
                P.post_type = 'contact' and
                P.post_status = 'publish' and
                P.post_date BETWEEN '" . $date_s . " 00:00:00' AND '" . $date_e . " 23:59:59' and
                (" . $sel_users . ")
            ORDER BY post_title";

        $query_results = $wpdb->get_results( $user_query, ARRAY_A  );

        $data = $query_results;
        $this->all_data = $data;//Save the results of this database query to a property, which can then be accessed by the get_views() method.  In that method I will use the query results to calculate the total number of posts for each user.  This will prevent me from having to run a separate database query for each user.
        $this->all_users = $users;//Save the results of this database query to a property, which can then be accessed by the get_views() method.  In that method I will use the query results to calculate the total number of posts for each user.  This will prevent me from having to run a separate database query for each user.

        /*Required for pagination.*/
        $current_page = $this->get_pagenum();
		$total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //Calculate the total number of items
            'per_page'    => $per_page,                     //Determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //Calculate the total number of pages
        ) );
        $this->post_count_total = $total_items;//Save the total post count to a property, which can then be accessed by the get_views() method.
    }
}

/*Add the new 'Reports' page to the admin menu.*/
function tv_add_menu_items(){
    $reports_page = "contact_reports.php";
    // add_menu_page('Reports', 'Reports', 'manage_options', 'contact_reports.php', 'tv_render_list_page', 'dashicons-clipboard', 6);
    add_menu_page('Reports', 'Reports', 'manage_options', $reports_page, 'tv_render_list_page', 'dashicons-clipboard', 6);
}
add_action('admin_menu', 'tv_add_menu_items');

/* This function will add all content to the 'Reports' page.*/
function tv_render_list_page(){
    
    //Create an instance of our list class
    $ListTable = new TV_List_Table();
    //Fetch, prepare, sort, and filter our data.
    $ListTable->prepare_items();

    if(isset($_REQUEST['page'])) {
        $val_p = $_REQUEST['page'];
    } else {
        $val_p = "";
    }
    $reports_page = "?page=" . $val_p;
    $home_file = "/wp-admin/admin.php" . $reports_page;    

    if(isset($_GET['date-start'])) {
        $val_ds = $_GET['date-start'];
    } else {
        $val_ds = "";
    }
    if(isset($_GET['date-end'])) {
        $val_de = $_GET['date-end'];
    } else {
        $val_de = "";
    }

    ?>
    <div class="wrap">
        <div id="icon-users" class="icon32"></div>
        <h2>Reports</h2>
       
        <form id="reports-filter" method="get">
            <input type="hidden" name="page" value="<?php echo $val_p ?>" />
            <div class="date-select" style="display:flex; align-items: baseline">
                <div>
                    <label for="date-start">Start Date:  </label>
                    <input type="date" id="date-start" name="date-start" value="<?php echo $val_ds;?>">
                </div>
                <div>
                    <label for="date-end">End Date:  </label>
                    <input type="date" id="date-end" name="date-end" value="<?php echo $val_de;?>">

                </div>
                <div>
                    <input type="hidden" name="page" value="contact_reports.php" /> 
                    <input type="submit" value="Submit">
                </div>
            <?php
    
            if (isset($_GET['date-start']) || isset($GET['date-end'])) {        
                // echo "<div><a href='/wp-admin/admin.php?page=contact_reports.php'>Reset</a></div>";
                echo "<div><a href='" . $home_file . "'>Reset</a></div>";
            }
            ?>

        </div>
        <?php $ListTable->display() ?>
        </form>
    </div>
<?php
}
/*End code to add 'Reports' admin section.*/

function compareDeepValue($val1, $val2) {
    return strcmp($val1['post_author'], $val2['post_author']);

}