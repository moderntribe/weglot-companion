<?php declare(strict_types=1);

namespace Tribe\Weglot\Activation;

/**
 * Implement for activators/deactivators.
 *
 * @package Tribe\Weglot
 */
interface Operable {

	public const OPTION_NAME = 'tribe_weglot';

	/**
	 * @param  bool  $network_wide Pass via WordPress if this is a network activation
	 */
	public function __invoke( bool $network_wide = false ): void;

}
