<?php
/**
 * Plugin Name: Inspiratica Ajax Post Loader
 * Plugin URI: http://inspiratica.ca/
 * Description: Filtering post category by ajax
 * Version: 1.0
 * Author: Inspiratica
 * Author URI: http://inspiratica.ca/
 */
add_action('init', 'init_inspi_ajax_loader');
function init_inspi_ajax_loader() {
	add_filter('post_class', 'category_id_class');
	#$plugin_url = plugin_dir_path(__FILE__);
	#echo $plugin_url . 'shortcode_inpsi_ajax_loader.php';
	#exit;
	#include($plugin_url . 'shortcode_inspi_ajax_loader.php');

	#
	#echo $plugin_url . 'widget_inspi_ajax_loader.php';
	#exit;
	#include($plugin_url . 'ajax_controller_inspi_ajax_loader.php');


}

//Register Widget
add_action('widgets_init', 'inspi_ajax_loader_register_widget');
function inspi_ajax_loader_register_widget() {

	include('widget_inspi_ajax_loader.php');
	register_widget('Widget_Inspi_Cateogry');

}

#

function category_id_class($classes) {
	global $post, $wp_query;
	if (has_category($post)) {
		$categories = get_the_category($post->ID);
	} else {

		$post_type     = get_post_type($post);
		$taxonomy_name = get_object_taxonomies($post_type)[0];
		$categories    = get_the_terms(get_the_ID(), $taxonomy_name);
	}

	foreach ($categories as $category)
		$classes[] = 'inspi-cat-' . $category->term_id." inspi-post-target ";

	#$classes[] = var_dump($category);


	return $classes;
}




