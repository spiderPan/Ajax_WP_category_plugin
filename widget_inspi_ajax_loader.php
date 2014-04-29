<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pan
 * Date: 2/27/14
 * Time: 12:46 PM
 * To change this template use File | Settings | File Templates.
 */
class Widget_Inspi_Cateogry extends WP_Widget {
	function __construct() {
		parent::__construct(
			'inspi_ajax_cat', // Base ID
			__('Inspiratica Category', 'inspi_ajax_loader'), // Name
			array('description' => __('Filter post by ajax', 'inspi_ajax_loader'),) // Args
		);
	}

	private function get_fields() {
		return array(
			'title'       => 'Title',
			'post_type'   => 'Post Type',
			'exclude_cat' => 'Exclude Category ID'
		);
	}


	public function widget($args, $instance) {
		extract($args);
		$instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$title             = $instance['title'];
		$post_type         = $instance['post_type'];
		$exclude_cat_arr   = explode(",", $instance['exclude_cat']);

		echo $before_widget;

		if ($title) {

			echo $before_title . $title . $after_title;
		}

		if ($post_type === 'post') {
			$taxonomy_name = 'category';
		} else {
			$taxonomy_name = get_object_taxonomies($post_type)[0];
		}
		wp_enqueue_script('inspi-ajax-loader', plugins_url('inspiratica-ajax-post-loader/js/inspiratica_ajax_script.js', dirname(__FILE__)), array('jquery'));
		$categories = get_terms($taxonomy_name, array('exclude' => $exclude_cat_arr));
		#echo var_dump($categories);
		$checked_arr = array();
		if(isset($_COOKIE["checkedID"])){
			$checked_arr = explode(',',$_COOKIE["checkedID"]);
			#unset($_COOKIE["checkedID"]);
			
			echo '<script>document.cookie = "checkedID=";</script>';
			
		}
		?>
		<div id="inspi_ajax_loader_wrapper">
			<form id="inspi_ajax_loader_form">
				<ul>
					<?php foreach ($categories as $cat): ?>
						<li class="inspi_cat_item">
							<input type="checkbox" class="inspi_ajax_checkbox" name="inspi_cat[<?php echo $cat->term_id; ?>]" id="inspi_cat_<?php echo $cat->term_id; ?>" value="<?php echo $cat->term_id; ?>" <?php echo (in_array($cat->term_id,$checked_arr))?'checked':'';?>>
							<label for="inspi_cat_<?php echo $cat->term_id; ?>" class="inspi_cat_item_label"><?php echo $cat->name . " (" . $cat->count . ")"; ?></label>
						</li>
					<?php endforeach; ?>
				</ul>

			</form>
		</div>
		<?php
		#echo $content;
		//The content
		echo $after_widget;
	}

	public function form($instance) {
		$form_fields = $this->get_fields();
		foreach ($form_fields as $field_name => $field_label) {
			if (isset($instance[$field_name])) {
				$instance_value = $instance[$field_name];
			} else {
				$instance_value = ' ';
			}

			?>
			<p>
				<label for="<?php echo $this->get_field_id($field_name); ?>"><?php _e($field_label . ':', 'inspi_ajax_loader'); ?>
					<input class="widefat" id="<?php echo $this->get_field_id($field_name); ?>" name="<?php echo $this->get_field_name($field_name); ?>" type="text" value="<?php echo $instance_value; ?>" />
				</label>
			</p>
		<?php
		}
	}

	public function update($new_instance, $old_instance) {

		$instance    = $old_instance;
		$form_fields = $this->get_fields();
		foreach ($form_fields as $field_name => $field_label) {
			$instance[$field_name] = strip_tags($new_instance[$field_name]);
		}

		return $instance;
	}
}






