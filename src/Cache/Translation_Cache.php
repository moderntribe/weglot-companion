<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

use Tribe\Libs\Cache\Cache;

class Translation_Cache {

	protected Cache $cache;

	public function __construct( Cache $cache ) {
		$this->cache = $cache;
	}

	/**
	 * Get a translation cache key for a post.
	 *
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function get_cache_key( int $post_id ): string {
		return sprintf( 'tribe_translated_%d', $post_id );
	}

	/**
	 * Return an array of translated content indexed by its language code, e.g `de`
	 * for German.
	 *
	 * @param int $post_id
	 *
	 * @return array<string, string>
	 */
	public function get( int $post_id ): array {
		return (array) $this->cache->get( $this->get_cache_key( $post_id ) );
	}

	/**
	 * Return a specific language translation for a post.
	 *
	 * @param string $language_code The two letter language code, e.g. `de`
	 * @param int    $post_id
	 *
	 * @return string
	 */
	public function get_by_language( string $language_code, int $post_id ): string {
		$translations = $this->get( $post_id );

		return $translations[ $language_code ] ?? '';
	}

	/**
	 * Appends a language translation to an existing post's translation
	 * cache array.
	 *
	 * @param mixed  $content
	 * @param string $language_code
	 * @param int    $post_id
	 * @param int    $expire
	 *
	 * @return bool
	 */
	public function set( $content, string $language_code, int $post_id, int $expire = 0 ): bool {
		$translations = $this->get( $post_id );

		$translations = array_filter( array_merge( $translations, [
			$language_code => $content,
		] ) );

		return $this->cache->set( $this->get_cache_key( $post_id ), $translations, 'tribe', $expire );
	}

	/**
	 * Deletes a post's cached translations.
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public function delete( int $post_id ): bool {
		return $this->cache->delete( $this->get_cache_key( $post_id ) );
	}

}
