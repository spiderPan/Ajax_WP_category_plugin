<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pan
 * Date: 2/27/14
 * Time: 12:38 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('../../../wp-config.php');
header('Content-Type: application/json');
#global $wpdb;

if (isset($_POST['inspi_cat']) && isset($_POST['inspi_post_type'])) {
	$cat_arr      = $_POST['inspi_cat'];
	$post_type    = $_POST['inspi_post_type'];
	$loop         = new WP_Query(array(
		                             'post_type' => $post_type,
		                             'cat'       => $cat_arr
	                             ));
	$return_posts = array();
	if ($loop->have_posts()) {
		while ($loop->have_posts()) {
			$loop->the_post();
			$return_posts[get_the_ID()] = array(
				''
			);

		}
	}
	#echo json_encode($cat_arr);
}