<?php declare(strict_types=1);

namespace Tribe\Weglot\Uri;

use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;

class Uri_Factory {

	/**
	 * Create a Uri instance.
	 *
	 * @param array $server The PHP $_SERVER global, or something similar.
	 *
	 * @return \League\Uri\Contracts\UriInterface
	 */
	public function create_from_server( array $server = [] ): UriInterface {
		if ( empty( $server ) ) {
			$server = $_SERVER ?? [];
		}

		return Uri::createFromServer( $server );
	}

}
