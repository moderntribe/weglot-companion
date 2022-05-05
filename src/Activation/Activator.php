<?php declare(strict_types=1);

namespace Tribe\Weglot\Activation;

use Tribe\Weglot\Core;

/**
 * Invoked during plugin activation.
 *
 * @package Tribe\Weglot\Activation
 */
class Activator implements Operable {

	public function __invoke( bool $network_wide = false ): void {
		update_option( self::OPTION_NAME, Core::VERSION );
	}

}
