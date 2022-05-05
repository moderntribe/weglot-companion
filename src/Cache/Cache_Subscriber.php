<?php declare(strict_types=1);

namespace Tribe\Weglot\Cache;

use Tribe\Libs\Container\Abstract_Subscriber;

class Cache_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'weglot_init_start', function (): void {
			add_filter( 'weglot_html_treat_page', function ( $translated_content ) {
				return $this->container->get( Weglot_Cache_Manager::class )->cache_translation( (string) $translated_content );
			}, 10, 1 );

			add_filter( 'weglot_active_translation_before_treat_page', function ( $active_translation ) {
				return $this->container->get( Weglot_Cache_Manager::class )->render_cached_translation( (bool) $active_translation );
			}, 10, 1 );
		}, 10, 0 );

		add_action( 'clean_post_cache', function ( $post_id ): void {
			$this->container->get( Listener::class )->clear_translation_cache( (int) $post_id );
		}, 10, 1 );
	}

}
