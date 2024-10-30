<?php
/*
Plugin Name: JC Navigation Page
Plugin URI: http://www.netcod.es/products/jc-nav-page/
Description: Add a navigation box in the admin edit page. it displays parent page, adjacents pages and childs pages, to quickly jump from one to other page.
Version: 1.3
Author: Netcodes
Author URI: http://www.netcod.es/
 */

// options prefixe
define('JC_SC_PLUGIN_DIR', 'jc-nav-page');

new JC_Nav_Page();

Class JC_Nav_Page{

    function JC_Nav_Page() {
        $this->__construct();
    } // JC_Nav_Page


    function __construct() {
        add_action( 'admin_init', array(&$this, 'admin_init') );
        add_action( 'add_meta_boxes', array(&$this, 'add_meta_box') );
    } // __construct


    function admin_init(){
        // load languages
        load_plugin_textdomain('jc_np', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }


    function add_meta_box(){
        add_meta_box(
            'jc_nav_page',
            __( 'Navigation', 'jc_np' ),
            array(&$this, 'custom_box'),
            'page',
            'side'
        );
    }


    function custom_box( $post ) {

        echo '<div class="jc_nav_page">';
        // parent page
        if ($post->post_parent) {
            $parent = get_post($post->post_parent);
            echo '<p><strong>'.__('Parent page', 'jc_np').'</strong></p>';
            echo '<ul style="margin-left: 10px;">';
            echo '<li><a href="'.get_edit_post_link($parent->ID).'" title="id: '.$parent->ID.', '.__('order', 'jc_np').': '.$parent->menu_order.', '.__('status', 'jc_np').': '.$parent->post_status.'">'.$parent->post_title.'</a></li>';
            echo '</ul>';
        }

        // adjacents pages
        $args = array(
            'numberposts'     => -1,
            'orderby'         => 'menu_order title',
            'order'           => 'ASC',
            'post_type'       => 'page',
            'post_parent'     => $post->post_parent,
            'post_status'     => 'any'
        );
        $posts_array = get_posts( $args );
        if (count($posts_array) > 1){
            echo '<p><strong>'.__('Adjacent pages', 'jc_np').'</strong></p>';
            echo '<ul style="margin-left: 15px;">';
            foreach( $posts_array as $p ){
                if ($p->ID == $post->ID) {
                    echo '<li><strong><em>&raquo; '.$p->post_title.' &laquo;</em></strong></li>';
                } else {
                    echo '<li><a href="'.get_edit_post_link($p->ID).'" title="id: '.$p->ID.', '.__('order', 'jc_np').': '.$p->menu_order.', '.__('status', 'jc_np').': '.$p->post_status.'">'.$p->post_title.'</a></li>';
                }
            }
            echo '</ul>';
        }

        // clilds pages
        $args = array(
            'numberposts'     => -1,
            'orderby'         => 'menu_order title',
            'order'           => 'ASC',
            'post_type'       => 'page',
            'post_parent'     => $post->ID,
            'post_status'     => 'any'
        );
        $posts_array = get_posts( $args );
        if (count($posts_array) > 1){
            echo '<p><strong>'.__('Child pages', 'jc_np').'</strong></p>';
            echo '<ul style="margin-left: 15px;">';
            foreach( $posts_array as $p ){
                echo '<li><a href="'.get_edit_post_link($p->ID).'" title="id: '.$p->ID.', '.__('order', 'jc_np').': '.$p->menu_order.', '.__('status', 'jc_np').': '.$p->post_status.'">'.$p->post_title.'</a></li>';
            }
            echo '</ul>';
        }
        echo '</div>';

        echo '<p style="text-align: center; padding-top:10px;">'
            . '<a href="http://cut.lu/pronto"><img src="' . plugins_url( 'pronto.png' , __FILE__ ) . '" alt="Pronto - Fast Wordpress Administration"></a>'
        . '</p>';

    }

}

?>
