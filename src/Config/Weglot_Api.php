<?php declare(strict_types=1);

namespace Tribe\Weglot\Config;

class Weglot_Api {

	/**
	 * Allow the Weglot API key to be set via environment variables.
	 *
	 * @filter weglot_get_api_key
	 *
	 * @param string $api_key
	 *
	 * @return string
	 */
	public function set_api_key( string $api_key ): string {
		if ( defined( 'WEGLOT_API_KEY' ) && WEGLOT_API_KEY ) {
			return (string) WEGLOT_API_KEY;
		}

		return $api_key;
	}

}
