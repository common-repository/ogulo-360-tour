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
 * @subpackage Ogulo_360_Tour/admin
 * @author     Rextheme <support@rextheme.com>
 */
class Ogulo_360_Tour_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    private $api_key;

    private $content_type;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->content_type = 'application/json';
        $this->api_key = '';
    }

    public function init_parameters()
    {
        $verification_key = 'oogulo_verification_key';
        $exists_key = get_transient($verification_key);
        $this->api_key = $exists_key;
    }
    public function adminmenu()
    {
        add_menu_page(__('Ogulo', 'ogulo-360-tour'), __('Ogulo', 'ogulo-360-tour'), 'manage_options', 'ogulo-360-tour', [$this, 'menu_callback'], 'dashicons-slides', 22);
        add_submenu_page('ogulo-360-tour', 'Logs', 'Logs', 'manage_options', 'ogulo-360-tour-log', [$this, 'ogulo_log']);
    }

    public function ogulo_log()
    {
        ogulo_view('admin/log', ['image_path' => plugin_dir_url(__FILE__) . '/images/', 'key' => $this->api_key]);
    }

    public function menu_callback($hook)
    {

        ogulo_view('admin/settings', ['image_path' => plugin_dir_url(__FILE__) . '/images/', 'key' => $this->api_key]);
    }

    public function activate_licence()
    {
        $log = [];

        $nonce = $_POST['nonce'];
        $key = sanitize_text_field($_POST['key']);

        if (!wp_verify_nonce($nonce, 'ogulo-verify-key')) {
            $log['error'] = __('You are not authorized to access this process.', 'ogulo-360-tour');
        } else {

            $this->api_key = $key;
            $body = [
                'api_key' => $this->api_key
            ];
            $headers = [
                'Content-Type' => $this->content_type
            ];

            $api = new Ogulo_360_Tour_Admin_API($body, $headers);
            $token = $api->set_token();
            $verification_key = 'oogulo_verification_key';
            if ($token) {
                $log['success'] = 'success';

                set_transient($verification_key, $key);
            } else {
                $log['error'] = 'invalid_api_key';
                delete_transient($verification_key);
            }
        }
        echo json_encode($log);
        wp_die();
    }

    public function get_all_tours()
    {
        $log = [];
        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'ogulo-verify-key')) {
            echo '<div>' . __('You are not authorized to access this process.', 'ogulo-360-tour') . '</div>';
        } else {

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

                // get all tours
                $all_tours = $api->getTours();

                // get all tours fallback
                if (empty($all_tours)) {
                    $token = $api->set_token();
                    $all_tours = $api->getTours();
                }

                // get company
                $company = $api->getCompany($all_tours[0]->company_id);
            }

            ob_start();

            ogulo_view('admin/tours', ['tours' => $all_tours, 'company' => $company]);

            $view = ob_get_clean();
            ob_end_clean();

            echo $view;
        }

        wp_die();
    }
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {

        if ($hook = 'toplevel_page_ogulo-360-tour' or $hook = 'toplevel_page_ogulo-360-tour-log') {
            /**
             * 
             *
             * An instance of this class should be passed to the run() function
             * defined in Ogulo_360_Tour_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Ogulo_360_Tour_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            wp_enqueue_style("jquery-ui-tabs");
            wp_enqueue_style('dashicons');
            wp_enqueue_style($this->plugin_name . '-datatable', plugin_dir_url(__FILE__) . 'css/datatable.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ogulo-360-tour-admin.css', array(), $this->version, 'all');
        } else {
            return;
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {

        if ($hook != 'toplevel_page_ogulo-360-tour') {
            return;
        }
        /**
         * An instance of this class should be passed to the run() function
         * defined in Ogulo_360_Tour_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ogulo_360_Tour_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script("jquery-ui-tabs");
        wp_enqueue_script($this->plugin_name . '-datatable', plugin_dir_url(__FILE__) . 'js/datatable.js', array(), $this->version, 'all');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ogulo-360-tour-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'oguloJSObject', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('ogulo-verify-key')
        ]);
    }
}
