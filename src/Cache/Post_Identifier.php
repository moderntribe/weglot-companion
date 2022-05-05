<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

class Post_Identifier {

	/**
	 * Detect the currently viewed post_id.
	 *
	 * @return int
	 */
	public function get_current_post_id(): int {
		global $post;

		return $post->ID ?? url_to_postid( $_SERVER['REQUEST_URI'] );
	}

}
