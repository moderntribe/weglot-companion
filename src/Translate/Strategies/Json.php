<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate\Strategies;

use Tribe\Weglot\Translate\Language;
use Weglot\Parser\Parser;
use WeglotWP\Services\Replace_Url_Service_Weglot;

class Json extends Html {

	/**
	 * Additional json keys for Weglot to translate.
	 *
	 * @var string[]
	 */
	protected array $json_keys = [];

	public function __construct( Language $language, Parser $parser, Replace_Url_Service_Weglot $url, array $json_keys = [] ) {
		parent::__construct( $language, $parser, $url );

		$this->json_keys = $json_keys;
	}

	/**
	 * A better interface for Weglot's translation feature.
	 *
	 * @filter tribe/weglot/translate
	 *
	 * @see \Tribe\Weglot\Translate\Translate_Subscriber::FILTER
	 *
	 * @param  string|mixed[]  $content  The content to translate.
	 *
	 * @throws \Weglot\Client\Api\Exception\InputAndOutputCountMatchException
	 * @throws \Weglot\Client\Api\Exception\InvalidWordTypeException
	 * @throws \Weglot\Client\Api\Exception\MissingRequiredParamException
	 * @throws \Weglot\Client\Api\Exception\MissingWordsOutputException
	 * @throws \Weglot\Client\Api\Exception\ApiError
	 *
	 * @return string|mixed[] The translated content.
	 */
	public function translate( $content ) {
		$original_lang = $this->language->original()->getInternalCode();
		$current_lang  = $this->language->current()->getInternalCode();
		$canonical     = (string) wp_get_referer();

		if ( $original_lang === $current_lang ) {
			return $content;
		}

		$translated = $this->parser->translate( $content, $original_lang, $current_lang, $this->json_keys, $canonical );

		return wp_json_encode( $this->url->replace_link_in_json( json_decode( $translated, true ) ) );
	}

}
