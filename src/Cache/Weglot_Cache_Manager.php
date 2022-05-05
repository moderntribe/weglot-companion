<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

use Tribe\Weglot\Meta\Weglot_Settings_Meta;
use Tribe\Weglot\Settings\Weglot_Settings;
use Tribe\Weglot\Translate\Language;
use WeglotWP\Services\Translate_Service_Weglot;

/**
 * Cache Weglot translations to reduce the number of remote
 * API requests required and to speed up page loading.
 */
class Weglot_Cache_Manager {

	protected Translation_Cache $cache;
	protected Language $language;
	protected Translate_Service_Weglot $translate;
	protected Post_Identifier $post_identifier;
	protected Weglot_Settings $settings;

	public function __construct(
		Translation_Cache $cache,
		Language $language,
		Translate_Service_Weglot $translate,
		Post_Identifier $post_identifier,
		Weglot_Settings $settings
	) {
		$this->cache           = $cache;
		$this->language        = $language;
		$this->translate       = $translate;
		$this->post_identifier = $post_identifier;
		$this->settings        = $settings;
	}

	/**
	 * Cache a post's translation.
	 *
	 * @filter weglot_html_treat_page
	 *
	 * @param string $translated_content
	 *
	 * @return string
	 */
	public function cache_translation( string $translated_content ): string {
		if ( $this->skip_cache() || empty( $translated_content ) ) {
			return $translated_content;
		}

		$code = $this->language->current()->getInternalCode();
		$id   = $this->post_id();

		if ( empty( $this->cache->get_by_language( $code, $id ) ) ) {
			$this->cache->set( $translated_content, $code, $id );
		}

		return $translated_content;
	}

	/**
	 * Render a translated post from the cache.
	 *
	 * @filter weglot_active_translation_before_treat_page
	 *
	 * @param  bool  $active_translation
	 *
	 * @return bool
	 */
	public function render_cached_translation( bool $active_translation ): bool {
		if ( $this->skip_cache() ) {
			return $active_translation;
		}

		$code = $this->language->current()->getInternalCode();
		$id   = $this->post_id();

		$translation = $this->cache->get_by_language( $code, $id );

		if ( $translation ) {
			$canonical = $this->translate->get_canonical_url_from_content( $translation );
			$content   = $this->translate->weglot_render_dom( $translation, $canonical );
			$cache_key = $this->cache->get_cache_key( $id );

			// Render cached content.
			ob_start( static function () use ( $content, $cache_key ) {
				return str_replace(
					'</html>',
					sprintf( '<!-- translation cache key: %s --></html>', esc_html( $cache_key ) ),
					$content
				);
			} );

			// Short circuit Weglot from translating.
			return false;
		}

		return $active_translation;
	}

	protected function post_id(): int {
		return $this->post_identifier->get_current_post_id();
	}

	/**
	 * Whether we should skip caching or displaying cached data for a
	 * translated post.
	 *
	 * @return bool
	 */
	protected function skip_cache(): bool {
		if ( ! $this->post_id() ) {
			return true;
		}

		if ( is_admin() ) {
			return true;
		}

		if ( $this->settings->get_setting( Weglot_Settings_Meta::FIELD_DISABLE_CACHING ) ) {
			return true;
		}

		return $this->language->current() === $this->language->original();
	}

}
