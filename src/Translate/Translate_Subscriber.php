<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use Throwable;
use Tribe\Libs\Container\Abstract_Subscriber;

class Translate_Subscriber extends Abstract_Subscriber {

	public const FILTER = 'tribe/weglot/translate';

	public function register(): void {

		// The Weglot plugin isn't installed or activated.
		if ( ! defined( 'WEGLOT_VERSION' ) ) {
			return;
		}

		/**
		 * Manually translate content with Weglot (generally for HTML returned via Ajax).
		 *
		 * @filter tribe/weglot/translate
		 *
		 * @example $translated = apply_filters( Translate_Subscriber::FILTER, '<p>Some kind of HTML content</p>' );
		 * @example $translated = apply_filters( Translate_Subscriber::FILTER, [ '<li>some content</li>', '<li>some more content</li>' ] );
		 *
		 * @param  string|string[]  $content  The raw content or HTML markup to translate.
		 *
		 * @return string|string[] The translated content.
		 */
		add_filter( self::FILTER, static function ( $content ) {
			try {
				return $this->container->get( Translation_Manager::class )->translate( $content );
			} catch ( Throwable $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					throw $e;
				}

				return $content;
			}
		}, 10, 1 );
	}

}
