<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

use League\Uri\Exceptions\SyntaxError;
use Tribe\Weglot\Uri\Uri_Factory;

class Post_Identifier {

	private Uri_Factory $uri_factory;

	public function __construct( Uri_Factory $factory ) {
		$this->uri_factory = $factory;
	}

	/**
	 * Detect the currently viewed post_id.
	 *
	 * @param array $server The PHP $_SERVER global or an array that matches that structure.
	 */
	public function get_current_post_id( array $server = [] ): int {
		global $post;

		$post_id = $post->ID ?? 0;

		if ( ! empty( max( $post_id, 0 ) ) ) {
			return $post->ID;
		}

		try {
			$uri = $this->uri_factory->create_from_server( $server );
		} catch ( SyntaxError $e ) {
			return 0;
		}

		return url_to_postid( $uri->toString() );
	}

}
