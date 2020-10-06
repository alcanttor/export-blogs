<?php
/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profile_Grid
 *
 * @wordpress-plugin
 * Plugin Name:       MetaGauss Export Data
 * Plugin URI:        http://metagauss.com
 * Description:       ProfileGrid adds user groups and user profiles functionality to your site.
 * Version:           1.0
 * Author:            metagauss
 * Author URI:        http://metagauss.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       metagauss-export-data
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'admin_menu', 'metagauss_export_admin_menu' );

function metagauss_export_admin_menu()
{
    add_menu_page(__('Metagauss','metagauss-export-data'),__('Metagauss','metagauss-export-data'),"manage_options","metagauss_dashboard",'metagauss_dashboard','dashicons-groups');
    /*add_submenu_page("pm_manage_groups",__('All Groups','profilegrid-user-profiles-groups-and-communities'),__('All Groups<sup>Start</sup>','profilegrid-user-profiles-groups-and-communities'),"manage_options","pm_manage_groups",array( $this, 'pm_manage_groups' ));*/
                
}

function metagauss_dashboard()
{
    wp_enqueue_script('metagauss-admin',plugin_dir_url( __FILE__ ) . 'metagauss-admin.css', array(),1.0, 'all'); 
    include 'submissions.php';
}

 function pm_get_pagination($num_of_pages,$pagenum,$base='') 
{
    if($pagenum=="") { $pagenum=1; }
        if($base=='') { $base = esc_url_raw( add_query_arg( 'pagenum', '%#%' ) ); }
		$args = array(
		'base'               => $base,
		'format'             => '',
		'total'              => $num_of_pages,
		'current'            => $pagenum,
		'show_all'           => false,
		'end_size'           => 1,
		'mid_size'           => 2,
		'prev_next'          => true,
		'prev_text'          => __('&laquo;', 'profilegrid-user-profiles-groups-and-communities' ),
		'next_text'          => __('&raquo;', 'profilegrid-user-profiles-groups-and-communities'),
		'type'               => 'list',
		'add_args'           => false,
		'add_fragment'       => '',
		'before_page_number' => '',
		'after_page_number'  => '' );
	
		$page_links = paginate_links( $args );
		return $page_links;
	}