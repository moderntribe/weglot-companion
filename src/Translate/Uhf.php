<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use Tribe\Weglot\Locale\Locale;

/**
 * The Microsoft UHF banner is a custom plugin to provide Microsoft sites
 * with a header and footer.
 *
 * This class won't do anything if that plugin isn't active.
 */
class Uhf {

	protected Language $language;
	protected Locale $locale;

	public function __construct( Language $language, Locale $locale ) {
		$this->language = $language;
		$this->locale   = $locale;
	}

	/**
	 * Filter the locale for the Microsoft UHF cookie banner, so it provides the
	 * correct translation for the selected language.
	 *
	 * @filter microsoft/uhf/locale
	 *
	 * @param string $locale
	 *
	 * @return string
	 */
	public function set_cookie_banner_locale( string $locale ): string {
		$original_lang = $this->language->original()->getInternalCode();
		$current_lang  = $this->language->current()->getInternalCode();

		if ( $original_lang === $current_lang ) {
			return $locale;
		}

		return $this->locale->get_locale( $this->language->current()->getExternalCode() );
	}

}
