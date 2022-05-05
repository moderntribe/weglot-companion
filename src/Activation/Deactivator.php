<?php declare(strict_types=1);

namespace Tribe\Weglot\Activation;

/**
 * Invoked during plugin deactivation.
 *
 * @package Tribe\Weglot\Activation
 */
class Deactivator implements Operable {

	public function __invoke( bool $network_wide = false ): void {
		delete_option( self::OPTION_NAME );
	}

}
