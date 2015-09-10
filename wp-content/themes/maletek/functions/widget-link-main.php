<?php
/***********************************************************************************************/
/* Widget para mostrar links a diferentes secciones de la página */
/***********************************************************************************************/

	class Maletek_Link_Main_Widget extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'maletek_link_main_w',
				'Custom Widget: Links Main',
				array('description' => __('Mostrar links a diferentes secciones de la página', THEMEDOMAIN))
			);
		}

		public function form($instance) {
			$defaults = array(
				'title' 		=> 	'',
				'subtitle'		=>	'',
				'text'			=>	'',
				'source'		=>	'page',
				'page'			=>	'',
				'category'		=>	'',
				'link_externo'	=>	'',
				'img'			=>	''
			);

			$instance = wp_parse_args((array) $instance, $defaults);

			?>
			<!-- The Title -->
			<p>
				<label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Título:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>

			<!-- The Sub-title -->
			<p>
				<label for="<?php echo $this->get_field_id('subtitle') ?>"><?php _e('Sub-Título:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" value="<?php echo esc_attr($instance['subtitle']); ?>" />
			</p>

			<!-- The Text -->
			<p>
				<label for="<?php echo $this->get_field_id('text') ?>"><?php _e('Texto:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" value="<?php echo esc_attr($instance['text']); ?>" />
			</p>

			<!-- Source -->
			<?php $checkbox = $instance['source']; ?>
			<h3>Fuente:</h3>
			<p>Seleccione la fuente y guarde para cargar las opciones de acuerdo a su selección.</p>
			<p>
				<label for="<?php echo $this->get_field_id('source') ?>"><?php _e('Página:', THEMEDOMAIN); ?></label>
				<input type="radio" class="widefat" id="<?php echo $this->get_field_id('source'); ?>" name="<?php echo $this->get_field_name('source'); ?>" value="page" <?php checked( $checkbox, 'page' ); ?> />

				<label for="<?php echo $this->get_field_id('source') ?>"><?php _e('Categoría:', THEMEDOMAIN); ?></label>
				<input type="radio" class="widefat" id="<?php echo $this->get_field_id('source'); ?>" name="<?php echo $this->get_field_name('source'); ?>" value="category" <?php checked( $checkbox, 'category' ); ?> />

				<label for="<?php echo $this->get_field_id('source') ?>"><?php _e('Enlace externo:', THEMEDOMAIN); ?></label>
				<input type="radio" class="widefat" id="<?php echo $this->get_field_id('source'); ?>" name="<?php echo $this->get_field_name('source'); ?>" value="link" <?php checked( $checkbox, 'link' ); ?> />
			</p>

			<!-- Page -->
			<?php if ($instance['source'] == 'page') : ?>
			<?php $pages = get_pages(); ?>
			<p>
				<label for="<?php echo $this->get_field_id('page') ?>"><?php _e('Seleccione la página:', THEMEDOMAIN); ?></label>
	        	<select id="<?php echo $this->get_field_id('page') ?>" name="<?php echo $this->get_field_name('page'); ?>">
                <?php
	                foreach ($pages as $page) {
	                    $selected = ($page->ID == $instance['page']) ? 'selected="selected"' : '';
	            ?>
	                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
	            <?php
	                }
	            ?>
	            </select>
			</p>
			<?php endif; ?>

			<!-- Category -->
			<?php if ($instance['source'] == 'category') : ?>
			<?php
				$args = array(
					'taxonomy'		=>	'category',
					'hide_empty'	=>	0,
					'exclude'		=>	'1'
				);
				$categories = get_categories($args);
			?>
			<p>
				<label for="<?php echo $this->get_field_id('category') ?>"><?php _e('Seleccione la categoría:', THEMEDOMAIN); ?></label>
	        	<select id="<?php echo $this->get_field_id('category') ?>" name="<?php echo $this->get_field_name('category'); ?>">
                <?php
	                foreach ($categories as $category) {
	                   	$selected = ($category->cat_ID == $instance['category']) ? 'selected="selected"' : '';
	            ?>
	                    <option value="<?php echo $category->cat_ID; ?>" <?php echo $selected; ?>><?php echo $category->name; ?></option>
	            <?php
	                }
	            ?>
	            </select>
			</p>
			<?php endif; ?>

			<!-- Link externo -->
			<?php if ($instance['source'] == 'link') : ?>
			<p>
				<label for="<?php echo $this->get_field_id('link_externo') ?>"><?php _e('Link externo:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_externo'); ?>" name="<?php echo $this->get_field_name('link_externo'); ?>" value="<?php echo esc_attr($instance['link_externo']); ?>" />
			</p>
			<?php endif; ?>

			<!-- Image -->
			<p>
				<label for="<?php echo $this->get_field_id('img') ?>"><?php _e('URL Imagen:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('img'); ?>" name="<?php echo $this->get_field_name('img'); ?>" value="<?php echo $instance['img']; ?>" />
			</p>
		<?php
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;

			// The Title
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['subtitle'] = strip_tags($new_instance['subtitle']);
			$instance['text'] = strip_tags($new_instance['text']);

			$instance['source'] = $new_instance['source'];
			$instance['page'] = $new_instance['page'];
			$instance['category'] = $new_instance['category'];
			$instance['link_externo'] = $new_instance['link_externo'];
			$instance['img'] = $new_instance['img'];

			return $instance;
		}

		public function widget($args, $instance) {
			extract($args);

			// Get the title and prepare it for display
			$title = apply_filters('widget_title', $instance['title']);
			$subtitle = apply_filters('widget_title', $instance['subtitle']);
			$text = apply_filters('widget_title', $instance['text']);

			// Get the ad images
			$source = $instance['source'];
			$img = (!empty($instance['img'])) ? $instance['img'] : '';

			$link = '';

			switch ($source) {
				case 'page' :
					$page = $instance['page'];
					break;
				case 'category' :
					$category = $instance['category'];
					break;
				case 'link' :
					$link = $instance['link_externo'];
					break;
			}

			echo $before_widget;

			if (isset($page) && !empty($page)) {
				$link = get_page_link($page);
			} elseif (isset($category) && !empty($category)) {
				$link = get_category_link($category);
			}
?>
		<a href="<?php echo $link; ?>">
			<img class="img-responsive" src="<?php echo $img; ?>" alt="<?php echo $title . ' ' . $subtitle; ?>" />
			<div class="content-botoner">
				<h3><?php echo $title; ?></h3>
				<h2><?php echo $subtitle; ?></h2>
				<p class="text-right"><?php echo $text; ?></p>
			</div> <!-- end of content-botoner -->
		</a>
<?php
			echo $after_widget;
		}
	}

	register_widget('Maletek_Link_Main_Widget');