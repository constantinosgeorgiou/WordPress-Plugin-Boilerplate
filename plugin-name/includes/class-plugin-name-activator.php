<?php
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Activator {
	/**
	 * List the dependencies required for the plugin to function properly.
	 *
	 * Each dependency should have the following structure:
	 *
	 * ```php
	 * $dependencies = array(
	 *     array(
	 *         'name'     => 'WooCommerce',
	 *         'class'    => 'WooCommerce',
	 *         'file'     => 'woocommerce/woocommerce.php',
	 *         'download' => 'https://wordpress.org/plugins/woocommerce/',
	 *     )
	 * );
	 *
	 * ```
	 *
	 * @since   1.0.0
	 * @var     array   $dependencies
	 */
	public static $dependencies = array();

	/**
	 * Runs once when plugin is activated.
	 *
	 * Performs the following actions:
	 * - Check plugin dependencies.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$dependencies_ok = self::check_plugin_dependencies();
		if ( ! $dependencies_ok ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			deactivate_plugins( plugin_basename( dirname( dirname( __FILE__ ) ) ) );
		}
	}

	/**
	 * Check plugin dependencies.
	 *
	 * @since   1.0.0
	 * @return  bool    true if dependencies are installed and activated, otherwise false.
	 */
	public static function check_plugin_dependencies() {
		foreach ( self::$dependencies as $name => $dependency ) {
			if ( ! class_exists( $dependency['class'] ) ) {
				add_action( 'admin_notices', 'Plugin_Name_Activator::missing_dependency_notice' );
				return false;
			}
		}
		return true;
	}


	/**
	 * Output the HTML for the missing dependencies admin notice.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public static function missing_dependency_notice() {
		$name  = 'WordPress Plugin Boilerplate';
		$class = 'notice notice-error';
		$links = array_map(
			function ( $dependency ) {
				return sprintf(
					'<a href="%2$s">%1$s</a>',
					esc_attr( $dependency['name'] ),
					esc_attr( $dependency['download'] )
				);
			},
			self::$dependencies
		);

		$message = sprintf(
			'%1$s %2$s %3$s',
			esc_attr( $name ),
			esc_html__( 'requires the following plugins to be installed and actived:', 'plugin-name' ),
			implode( ', ', $links )
		);

		$html = sprintf(
			'<div class="%1$s"><p><b>%2$s</b></p><p>%3$s</p></div>',
			esc_attr( $class ),
			esc_attr( $name ),
			$message,
		);

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
