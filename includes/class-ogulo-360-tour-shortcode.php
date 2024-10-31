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
 * @subpackage Ogulo_360_Tour/shortcode
 * @author     Rextheme <support@rextheme.com>
 */


class Ogulo_360_Tour_Shortcode
{
    var $api_key;
    var $content_type;
    var $expire_temp_url_secs;

    function __construct()
    {
        add_shortcode('ogulo_tour', [$this, 'ogulo_tour_callback']);
        $this->init_parameters();
    }

    public function init_parameters()
    {
        $verification_key = 'oogulo_verification_key';
        $exists_key = get_transient($verification_key);
        $this->api_key = $exists_key;
        $this->content_type = 'application/json';
        $this->expire_temp_url_secs = 15;
    }

    public function ogulo_tour_callback($atts)
    {
        global $ogulo360;
        $view = '';
        if (empty($this->api_key)) {
            return;
        }
        $atts = shortcode_atts(array(
            'name' => '',
            'slug' => '',
            'height' => '100%',
            'width' => '100%'
        ), $atts, 'ogulo_tour');

        if (!empty($atts['slug'])) {
            $view .= '<div  class="ogulo_tour_handler before">
                        <div id="tour-id-' . $atts['slug'] . '" data-tour-width="' . $atts['width'] . '" data-tour-height="' . $atts['height'] . '" class="tour-id-' . $atts['slug'] . ' ogulo_tour_handler_inner">
                        <img src="' . $ogulo360->urlpath . '/public/images/ogulo.png">
                        <p>' . __(sprintf('Tour: %s is displayed', $atts['name']), 'ogulo-360-tour') . '</p>
                      </div></div>';
        } else {
            $view .= '<div class="ogulo_tour_handler before">
                        <div class="ogulo_tour_handler_inner">
                        <img src="' . $ogulo360->urlpath . '/public/images/ogulo.png">
                        <p>' . __('Click here to choose a tour', 'ogulo-360-tour') . '</p>
                      </div></div>';
        }

        return $view;
    }

    public function load_front_tours()
    {
        $nonce = $_POST['nonce'];
        $log = [];
        $tour = sanitize_text_field(stripslashes_deep($_POST['tour']));
        $tour_info = explode("|", $tour);
        $atts = [
            'slug' => $tour_info[0],
            'height' => $tour_info[2],
            'width' => $tour_info[1]
        ];

        if (wp_verify_nonce($nonce, 'ogulo-verify-key')) {
            if (!empty($tour)) {

                //foreach (array_unique($tours) as $short_code) {

                //$tours_embeds[$short_code] = $this->ogulo_tour_load_tour($atts);
                $tour_embed = $this->ogulo_tour_load_tour($atts);
                if ($tour_embed) {
                    $log['success']['embed'] = $tour_embed;
                } else {
                    $log['error'] = __('Sorry we could not find this tour in our list.', 'ogulo-360-tour');
                }
                //}	

            } else {
                $log['error'] = __('Tour id should not be empty.', 'ogulo-360-tour');
            }
        } else {
            $log['error'] = __('Unauthorized Access.', 'ogulo-360-tour');
        }
        $log['tour']['tour_id'] = $tour_info[0];
        echo json_encode($log);
        wp_die();
    }

    public function ogulo_tour_load_tour($atts)
    {

        if (empty($this->api_key)) {
            return false;
        }
        $atts = shortcode_atts(array(
            'slug' => '',
            'height' => 0,
            'width' => 0
        ), $atts, 'ogulo_tour');

        if (!empty($atts['slug'])) {

            $body = [
                'api_key' => $this->api_key
            ];
            $headers = [
                'Content-Type' => $this->content_type
            ];
            $api = new Ogulo_360_Tour_Admin_API($body, $headers);
            $token = $api->get_token(); //get existing token

            $tour = $api->get_tour($atts['slug']);


            if (empty($tour)) {
                $token = $api->set_token();
                $tour = $api->get_tour($atts['slug']);
            }

            if (!empty($tour)) {
                $red_code = md5(wp_rand(200, 999999999));
                if (get_transient('ogulo_access_redirection_' . $red_code)) {
                    delete_transient('ogulo_access_redirection_' . $red_code);
                }


                $temp_url = home_url() . '/ogulo_access/' . $red_code;
                $original_url = $api->tour_domain . '/' . $tour[0]->short_code;

                set_transient('ogulo_access_redirection_' . $red_code, $original_url, 15); //expire the temp url in 30 secs
                return '<style>body {margin:0;}</style><embed style=" width:' . $atts['width'] . ';height:' . $atts['height'] . '" src="' . $temp_url . '">';
            } else {
                return false;
            }
        }
    }
}
