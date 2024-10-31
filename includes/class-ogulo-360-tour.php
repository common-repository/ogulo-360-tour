<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.ogulo.de/
 * @since      1.0.0
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/includes
 * @author     Rextheme <support@rextheme.com>
 */
class Ogulo_360_Tour
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ogulo_360_Tour_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    public $dirpath;

    public $urlpath;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('OGULO_360_TOUR_VERSION')) {
            $this->version = OGULO_360_TOUR_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'ogulo-360-tour';


        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->load_tour_handler();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Ogulo_360_Tour_Loader. Orchestrates the hooks of the plugin.
     * - Ogulo_360_Tour_i18n. Defines internationalization functionality.
     * - Ogulo_360_Tour_Admin. Defines all hooks for the admin area.
     * - Ogulo_360_Tour_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/functions.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ogulo-360-tour-api.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ogulo-360-tour-shortcode.php';


        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ogulo-360-tour-url-access.php';
        new Ogulo_360_Tour_URL_Access();

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ogulo-360-tour-widget.php';
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ogulo-360-tour-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ogulo-360-tour-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ogulo-360-tour-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ogulo-360-tour-public.php';

        $this->loader = new Ogulo_360_Tour_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Ogulo_360_Tour_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Ogulo_360_Tour_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Ogulo_360_Tour_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_admin, 'init_parameters');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'adminmenu');
        $this->loader->add_action('wp_ajax_ogulo_activation', $plugin_admin, 'activate_licence');
        $this->loader->add_action('wp_ajax_get_all_tours', $plugin_admin, 'get_all_tours');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Ogulo_360_Tour_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_tour_handler()
    {
        $tour_loader = new Ogulo_360_Tour_Shortcode();
        $this->loader->add_action('wp_ajax_load_front_tours', $tour_loader, 'load_front_tours');
        $this->loader->add_action('wp_ajax_nopriv_load_front_tours', $tour_loader, 'load_front_tours');
    }
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }


    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Ogulo_360_Tour_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
