<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ogulo.de/
 * @since      1.0.0
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/widget
 * @author     Rextheme <support@rextheme.com>
 */

add_action('widgets_init', 'register_ogulo_tour_widget');
function register_ogulo_tour_widget()
{
    register_widget('Ogulo_360_Tour_Widget');
}
class Ogulo_360_Tour_Widget extends WP_Widget
{

    var $api_key;

    var $content_type;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct()
    {

        parent::__construct(
            'ogulo_tours_widget', // Base ID
            __('Ogulo Tours', 'ogulo-360-tour'), // Name
            array('description' => __('Ogulo Tours', 'ogulo-360-tour'),) // Args
        );

        $this->init_parameters();
    }


    public function init_parameters()
    {
        $verification_key = 'oogulo_verification_key';
        $exists_key = get_transient($verification_key);
        $this->api_key = $exists_key;
        $this->content_type = 'application/json';
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        extract($args);

        $name = "";
        $slug = "";
        $title = apply_filters('ogulo_widget_title', $instance['title']);
        $tour = $instance['tours'];
        $width = (int) apply_filters('ogulo_widget_tour_width', $instance['width']);
        $width_type =  ($instance['size_type_width'] ?: 'px');
        $height = (int) apply_filters('ogulo_widget_tour_height', $instance['height']);
        $height_type = ($instance['size_type_height'] ?: 'px');
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        if (!empty($tour)) {
            $tour_arr = explode("|", $tour);
            $slug = $tour_arr[0];
            $name = $tour_arr[1];
        }
        echo do_shortcode('[ogulo_tour name=\'' . $name . '\' slug=\'' . $slug . '\' height=\'' . $height . $height_type . '\' width=\'' . $width . $width_type . '\']');
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Überschrift', 'ogulo-360-tour');
        }

        if (isset($instance['tours'])) {
            $selected_tour = $instance['tours'];
        } else {
            $selected_tour = 0;
        }

        if (isset($instance['width'])) {
            $width = $instance['width'];
        } else {
            $width = 100;
        }

        if (isset($instance['height'])) {
            $height = $instance['height'];
        } else {
            $height = 600;
        }

        if (isset($instance['size_type_width'])) {
            $selected_size_w = $instance['size_type_width'];
        } else {
            $selected_size_w = '%';
        }

        if (isset($instance['size_type_height'])) {
            $selected_size_h = $instance['size_type_height'];
        } else {
            $selected_size_h = 'px';
        }

        if (empty($this->api_key)) {
            $all_tours = [];
        } else {
            $body = [
                'api_key' => $this->api_key
            ];
            $headers = [
                'Content-Type' => $this->content_type
            ];
            $api = new Ogulo_360_Tour_Admin_API($body, $headers);
            $token = $api->get_token(); //get existing token

            $all_tours = $api->getTours(); //enable it after testing
            if (empty($all_tours)) {
                $token = $api->set_token();
                $all_tours = $api->getTours();
            }
        }

?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:', 'ogulo-360-tour'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('tours'); ?>"><?php _e('Show Tour:', 'ogulo-360-tour'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('tours'); ?>" name="<?php echo $this->get_field_name('tours'); ?>">
                <?php
                $tours_list = '<option value="0">' . __('Select a tour', 'ogulo-360-tour') . '</option>';
                if (!empty($all_tours)) {
                    foreach ($all_tours as $tour) {
                        $tours_list .= '<option value="' . $tour->short_code . '|' . $tour->headline . '" ' . selected($selected_tour, $tour->short_code . '|' . $tour->headline, false) . '>' . $tour->headline . '</option>';
                    }
                }
                echo $tours_list;
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('width'); ?>"><?php _e('Width:', 'ogulo-360-tour'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="number" min="0" value="<?php echo esc_attr($width); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('size_type_width'); ?>"><?php _e('Width in:', 'ogulo-360-tour'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('size_type_width'); ?>" name="<?php echo $this->get_field_name('size_type_width'); ?>">
                <?php
                $size_list = '<option value="px" ' . selected($selected_size_w, 'px', false) . '>' . __('px (Pixels)', 'ogulo-360-tour') . '</option>';
                $size_list .= '<option value="%" ' . selected($selected_size_w, '%', false) . '>' . __('% (Percentage)', 'ogulo-360-tour') . '</option>';
                echo $size_list;
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name('height'); ?>"><?php _e('Height:', 'ogulo-360-tour'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="number" min="0" value="<?php echo esc_attr($height); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('size_type_height'); ?>"><?php _e('Height in:', 'ogulo-360-tour'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('size_type_height'); ?>" name="<?php echo $this->get_field_name('size_type_height'); ?>">
                <?php
                $size_list = '<option value="px" ' . selected($selected_size_h, 'px', false) . '>' . __('px (Pixels)', 'ogulo-360-tour') . '</option>';
                $size_list .= '<option value="%" ' . selected($selected_size_h, '%', false) . '>' . __('% (Percentage)', 'ogulo-360-tour') . '</option>';
                echo $size_list;
                ?>
            </select>
        </p>
<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags(sanitize_text_field(stripslashes_deep($new_instance['title']))) : '';
        $instance['tours'] = (!empty($new_instance['tours'])) ? strip_tags(sanitize_text_field(stripslashes_deep($new_instance['tours']))) : '';
        $instance['width'] = (!empty($new_instance['width'])) ? strip_tags(sanitize_text_field(stripslashes_deep($new_instance['width']))) : '';
        $instance['height'] = (!empty($new_instance['height'])) ? strip_tags(sanitize_text_field(stripslashes_deep($new_instance['height']))) : '';
        $instance['size_type_width'] = (!empty($new_instance['size_type_width'])) ? strip_tags(sanitize_text_field(stripslashes_deep($new_instance['size_type_width']))) : '';
        $instance['size_type_height'] = (!empty($new_instance['size_type_height'])) ? strip_tags(sanitize_text_field(stripslashes_deep($new_instance['size_type_height']))) : '';

        return $instance;
    }
}
