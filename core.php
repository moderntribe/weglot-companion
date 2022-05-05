<?php declare(strict_types=1);

/**
 * Plugin Name:       :package_name
 * Plugin URI:        https://github.com/:vendor_slug/:package_slug
 * Description:       :package_description
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Author:            Modern Tribe
 * Author URI:        https://tri.be
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       :package_slug
 * Domain Path:       /languages
 */

namespace Tribe\Starter;

use Tribe\Starter\Activation\Activator;
use Tribe\Starter\Activation\Deactivator;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Prevent duplicate autoloading during tests
if ( ! class_exists( Core::class ) ) {
	// Require the vendor folder via multiple locations
	$autoloaders = [
		trailingslashit( __DIR__ ) . 'vendor/scoper-autoload.php',
		trailingslashit( __DIR__ ) . 'vendor/autoload.php',
		trailingslashit( WP_CONTENT_DIR ) . '../vendor/autoload.php',
		trailingslashit( WP_CONTENT_DIR ) . 'vendor/autoload.php',
	];

	$autoload = current( array_filter( $autoloaders, 'file_exists' ) );

	require_once $autoload;
}

add_action( 'plugins_loaded', static function (): void {
	if ( ! class_exists( 'ACF' ) ) {
		add_action(
			'admin_notices',
			static function (): void { ?>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'Tribe Starter requires Advanced Custom Fields Pro to be installed and activated!', ':package_slug' ); ?></p>
				</div>
			<?php }
		);

		return;
	}

	tribe_starter()->init( __FILE__ );
}, 5, 0 );


function tribe_starter(): Core {
	return Core::instance();
}

register_activation_hook( __FILE__, new Activator() );
register_deactivation_hook( __FILE__, new Deactivator() );
