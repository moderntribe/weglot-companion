<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

use Tribe\Libs\Cache\Cache;

class Translation_Cache {

	protected Cache $cache;

	/**
	 * In memory cache of the language_code => 'content' for
	 * the current post.
	 *
	 * @var array<int, array<string, string>>
	 */
	protected array $translation_cache = [];

	/**
	 * In memory cache of this post's reference keys.
	 *
	 * @var array<string, string>|null
	 */
	protected ?array $reference_cache = null;

	/**
	 * Enable in-memory caching.
	 */
	protected bool $use_memory_cache;

	public function __construct( Cache $cache, bool $use_memory_cache = true ) {
		$this->cache            = $cache;
		$this->use_memory_cache = $use_memory_cache;
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
	 * Returns all cached content translations for a post.
	 *
	 * @param int $post_id
	 *
	 * @return array<string, string>
	 */
	public function get( int $post_id ): array {
		if ( $this->use_memory_cache && ! empty( $this->translation_cache[ $post_id ] ) ) {
			return $this->translation_cache[ $post_id ];
		}

		$references = $this->get_reference_map( $post_id );

		$translations = [];

		foreach ( $references as $code => $key ) {
			$translations[ $code ] = $this->get_translated_content( $key );
		}

		$this->translation_cache[ $post_id ] = $translations;

		return $translations;
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
		$references = $this->get_reference_map( $post_id );

		$key = $references[ $language_code ] ?? '';

		if ( ! $key ) {
			return '';
		}

		return $this->get_translated_content( $key );
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
		$reference_key = $this->build_reference_key( $post_id, $language_code );
		$reference_map = array_filter( array_merge( $this->get_reference_map( $post_id ), [
			$language_code => $reference_key,
		] ) );

		// Store the translated content in its own unique key.
		if ( ! $this->cache->set( $reference_key, $content, 'tribe', $expire ) ) {
			return false;
		}

		// Store the references to the translation keys.
		return $this->cache->set( $this->get_cache_key( $post_id ), $reference_map, 'tribe', $expire );
	}

	/**
	 * Deletes a post's cached translations.
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public function delete( int $post_id ): bool {
		$references = $this->get_reference_map( $post_id );

		// Delete all content translations for this post.
		foreach ( $references as $key ) {
			$this->cache->delete( $key );
		}

		// Remove from in-memory caches.
		if ( isset( $this->reference_cache[ $post_id ] ) ) {
			unset( $this->reference_cache[ $post_id ] );
		}

		if ( isset( $this->translation_cache[ $post_id ] ) ) {
			unset( $this->translation_cache[ $post_id ] );
		}

		// Delete the references
		return $this->cache->delete( $this->get_cache_key( $post_id ) );
	}

	/**
	 * Create's a key to store a specific language's translation for a
	 * specific post ID.
	 *
	 * @param int    $post_id
	 * @param string $language_code
	 *
	 * @return string
	 */
	protected function build_reference_key( int $post_id, string $language_code ): string {
		return sprintf( 'tribe_weglot_%d_%s', $post_id, $language_code );
	}

	/**
	 * Return the $language_code => $object_cache_key reference map.
	 *
	 * @param int $post_id
	 *
	 * @return array<string, string>
	 */
	protected function get_reference_map( int $post_id ): array {
		if ( $this->use_memory_cache && ! empty( $this->reference_cache[ $post_id ] ) ) {
			return $this->reference_cache[ $post_id ];
		}

		$reference_keys = array_filter( (array) $this->cache->get( $this->get_cache_key( $post_id ) ) );

		$this->reference_cache[ $post_id ] = $reference_keys;

		return $reference_keys;
	}

	/**
	 * Grab a specific translation for a post by its reference key.
	 *
	 * @param string $reference_key
	 *
	 * @return string
	 */
	protected function get_translated_content( string $reference_key ): string {
		return (string) $this->cache->get( $reference_key );
	}

}
