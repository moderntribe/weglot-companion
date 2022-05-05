<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use Weglot\Client\Api\LanguageEntry;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;

/**
 * A language interface for Weglot to combine language responsibilities
 * into a single class.
 */
class Language {

	protected Language_Service_Weglot $language;
	protected Request_Url_Service_Weglot $request;

	public function __construct( Language_Service_Weglot $language, Request_Url_Service_Weglot $request ) {
		$this->language = $language;
		$this->request  = $request;
	}

	public function original(): LanguageEntry {
		return $this->language->get_original_language();
	}

	public function current(): LanguageEntry {
		return $this->request->get_current_language();
	}

}
