<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
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
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name {
	/**
	 * The single instance of the class.
	 *
	 * @var     Plugin_Name             $instance
	 * @access  private
	 * @since   1.0.0
	 */
	private static $instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Main Plugin Instance.
	 * Ensures only one instance of the plugin is loaded or can be loaded.
	 *
	 * @since   1.0.0
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since   1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'plugin-name' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since   1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'plugin-name' ), '1.0.0' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 * @since   1.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Define Plugin_Name Constants.
	 *
	 * @since   1.0.0
	 */
	private function define_constants() {
		$this->define( 'PLUGIN_NAME_VERSION', '1.0.0' );
		$this->define( 'PLUGIN_NAME_NAME', 'WordPress Plugin Boilerplate' );
		$this->define( 'PLUGIN_NAME_CONTEXT', 'plugin-name' );
		$this->define( 'PLUGIN_NAME_PLUGIN_DIR', untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
		$this->define( 'PLUGIN_NAME_PLUGIN_URL', untrailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
	}

	/**
	 * Define Plugin_Name plugin settings.
	 *
	 * @return void
	 */
	private function define_options() {
	}


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->define_constants();
		$this->define_options();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_I18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @throws  Exception   Composer `autoload.php` missing.
	 */
	private function load_dependencies() {
		$loader = include_once PLUGIN_NAME_PLUGIN_DIR . '/vendor/autoload.php';

		if ( ! $loader ) {
			throw new Exception( 'vendor/autoload.php missing please run `composer install`' );
		}

		require_once PLUGIN_NAME_PLUGIN_DIR . '/includes/class-plugin-name-loader.php';
		require_once PLUGIN_NAME_PLUGIN_DIR . '/includes/class-plugin-name-i18n.php';
		require_once PLUGIN_NAME_PLUGIN_DIR . '/admin/class-plugin-name-admin.php';
		require_once PLUGIN_NAME_PLUGIN_DIR . '/public/class-plugin-name-public.php';

		$this->loader = Plugin_Name_Loader::instance();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function set_locale() {
		$this->loader->add_action( 'plugins_loaded', 'Plugin_Name_I18n', 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function define_admin_hooks() {
		$this->loader->add_action( 'admin_enqueue_scripts', Plugin_Name_Admin::instance(), 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', Plugin_Name_Admin::instance(), 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function define_public_hooks() {
		$this->loader->add_action( 'wp_enqueue_scripts', Plugin_Name_Public::instance(), 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', Plugin_Name_Public::instance(), 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since   1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since   1.0.0
	 * @return  Plugin_Name_Loader  Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
