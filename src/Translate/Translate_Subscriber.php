<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use Throwable;
use Tribe\Libs\Container\Abstract_Subscriber;

class Translate_Subscriber extends Abstract_Subscriber {

	public const FILTER    = 'tribe/weglot/translate';
	public const TYPE_HTML = 'html';
	public const TYPE_JSON = 'json';
	public const TYPE_XML  = 'xml';

	public function register(): void {

		// The Weglot plugin isn't installed or activated.
		if ( ! defined( 'WEGLOT_VERSION' ) ) {
			return;
		}

		/**
		 * Manually translate content with Weglot.
		 *
		 * @filter  tribe/weglot/translate
		 *
		 * @param string|string[] $content   The raw content or HTML markup to translate.
		 * @param string          $type      The type of content this is, so Weglot knows what to do with it.
		 * @param string[]        $json_keys Additional JSON keys to translate if $type is "json".
		 *
		 * @return string|string[] The translated content.
		 */
		add_filter( self::FILTER, function ( $content, string $type = self::TYPE_HTML, array $json_keys = [] ) {
			try {
				return $this->container->get( Translation_Factory::class )
									   ->make( $type, $json_keys )
									   ->translate( $content );
			} catch ( Throwable $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					throw $e;
				}

				return $content;
			}
		}, 10, 3 );

		add_filter( 'microsoft/uhf/locale', function ( $locale ): string {
			return $this->container->get( Uhf::class )->set_cookie_banner_locale( (string) $locale );
		}, 10, 1 );
	}

}
