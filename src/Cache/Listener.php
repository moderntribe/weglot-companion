<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

class Listener {

	protected Translation_Cache $cache;

	public function __construct( Translation_Cache $cache ) {
		$this->cache = $cache;
	}

	/**
	 * Clear a post's translation cache when WordPress clears its post cache.
	 *
	 * @action clean_post_cache
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public function clear_translation_cache( int $post_id ): bool {
		return $this->cache->delete( $post_id );
	}

}
