<?php declare(strict_types=1);

namespace Tribe\Weglot\Locale;

class Locale {

	/**
	 * The locales map.
	 *
	 * @var array<string, string>
	 */
	protected array $locales;

	public function __construct( array $locales ) {
		$this->locales = $locales;
	}

	/**
	 * Guess a locale based on ISO 639-1 language codes. These codes are primarily used
	 * for Microsoft services.
	 *
	 * @param string $code The two character, ISO_639-1 language code, e.g. af.
	 * @param string $fallback The fallback language code to use if this one doesn't exist.
	 *
	 * @link https://docs.microsoft.com/en-us/windows-hardware/manufacture/desktop/default-input-locales-for-windows-language-packs?view=windows-11
	 * @link https://en.wikipedia.org/wiki/ISO_639-1
	 *
	 * @return string The locale, e.g. af-ZA
	 */
	public function get_locale( string $code, string $fallback = '' ): string {
		return $this->locales[ $code ] ?? $this->locales[ $fallback ] ?? $code;
	}

}
