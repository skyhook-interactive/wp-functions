/*-----------------------------------------------------------------------------------*/
/* Thumbnail Settings
/*-----------------------------------------------------------------------------------*/
// Make sure theme supports feature images
add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-thumbnails', array( 'page' ) ); // Add it for pages

// Set Feature Image Size
set_post_thumbnail_size( 492, 100 );



/*-----------------------------------------------------------------------------------*/
/* Remove link from attachments
/*-----------------------------------------------------------------------------------*/
add_filter('wp_get_attachment_url', 'remove_link_from_image_attachments');
function remove_link_from_image_attachments($url){

        $dont_link_images = array( 'png', 'jpg', 'gif', 'jpeg');

        if ( in_array( end( explode(".", $url) ), $dont_link_images) ) {
                update_option('image_default_link_type' , 'none');
        } else {
                update_option('image_default_link_type' , 'file');
        }

        return $url;

}



/*-----------------------------------------------------------------------------------*/
/* Hide Menus that aren't used
/*-----------------------------------------------------------------------------------*/
add_action('admin_menu', 'remove_menus');
function remove_menus () {
global $menu;
	//$restricted = array( __('Posts'), __('Links'), __('Comments'), __('Tools'), __('Plugins'));
	$restricted = array( __('Links'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}



/*-----------------------------------------------------------------------------------*/
/* Clean up user profile fields
/*-----------------------------------------------------------------------------------*/
add_filter('user_contactmethods','soaz_contactmethods',10,1);
function soaz_contactmethods($contactmethods) {
	unset($contactmethods['yim']);
	unset($contactmethods['aim']);
	unset($contactmethods['jabber']);
	return $contactmethods;
}



/*-----------------------------------------------------------------------------------*/
/* Hide ACF menu item from the admin menu
/*-----------------------------------------------------------------------------------*/
add_action('admin_head', 'hide_admin_menu');
function hide_admin_menu(){
	global $current_user;
	get_currentuserinfo();
 
	if($current_user->user_login != 'admin'){
		echo '<style type="text/css">#toplevel_page_edit-post_type-acf{display:none;}</style>';
	}
}



/*-----------------------------------------------------------------------------------*/
/* Check if post ID is in the tree
/*-----------------------------------------------------------------------------------*/
if (!function_exists('is_tree')) {
	function is_tree($pid){
		global $post;

		$ancestors = get_post_ancestors($post->$pid);
		$root = count($ancestors) - 1;
		$parent = $ancestors[$root];

		if(is_page() && (is_page($pid) || $post->post_parent == $pid || in_array($pid, $ancestors))){
			return true;
		} else {
			return false;
		}
	};
}



/*-----------------------------------------------------------------------------------*/
/* Disable admin bar
/*-----------------------------------------------------------------------------------*/
show_admin_bar(false);



/*-----------------------------------------------------------------------------------*/
/* Disable Themes menu
/*-----------------------------------------------------------------------------------*/
add_action('admin_init', 'remove_theme_menus');
function remove_theme_menus() {
	global $submenu;
	unset($submenu['themes.php'][5]);
	unset($submenu['themes.php'][15]);
}



/*-----------------------------------------------------------------------------------*/
/* Remove Extra Columns from WordPress SEO
/*-----------------------------------------------------------------------------------*/
add_filter('wpseo_use_page_analysis', function(){return false;}, 10, 1);




/*-----------------------------------------------------------------------------------*/
/* Add/Remove Items from Admin Bar
/*-----------------------------------------------------------------------------------*/
function sk_remove_add_links(){
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('new-link');
	$wp_admin_bar->remove_menu('new-media');
	$wp_admin_bar->remove_menu('new-user');
	/*$wp_admin_bar->add_menu(array(
		'parent' => 'new-content',
		'id' => 'new_testmonial',
		'title' => __('Testimonial'),
		'href' => admin_url('post-new.php?post_type=testimonial')
	));*/
}
add_action('wp_before_admin_bar_render', 'sk_remove_add_links');
