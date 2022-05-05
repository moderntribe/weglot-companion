<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate\Strategies;

use Tribe\Weglot\Translate\Language;
use Tribe\Weglot\Translate\Translatable;
use Weglot\Parser\Parser;
use WeglotWP\Services\Replace_Url_Service_Weglot;

/**
 * Translatable HTML content with Weglot.
 *
 * Weglot's methods have too many responsibilities, so there is no single method/function
 * to just simply translate HTML or content, it always does much more than that.
 *
 * This abstracts the functionality out into a single class.
 *
 * Utilize this class through the provided filter and not directly:
 *
 * @see \Tribe\Weglot\Translate\Translate_Subscriber::FILTER
 * @see \Tribe\Weglot\Translate\Translate_Subscriber::register()
 */
class Html implements Translatable {

	public const EOL_HTML = '<!-- EOL -->';

	protected Language $language;
	protected Parser $parser;
	protected Replace_Url_Service_Weglot $url;

	public function __construct( Language $language, Parser $parser, Replace_Url_Service_Weglot $url ) {
		$this->language = $language;
		$this->parser   = $parser;
		$this->url      = $url;
	}

	/**
	 * A better interface for Weglot's translation feature.
	 *
	 * @filter tribe/weglot/translate
	 *
	 * @see \Tribe\Weglot\Translate\Translate_Subscriber::FILTER
	 *
	 * @param  string|string[]  $content  The content to translate, raw text or HTML.
	 *
	 * @throws \Weglot\Client\Api\Exception\InputAndOutputCountMatchException
	 * @throws \Weglot\Client\Api\Exception\InvalidWordTypeException
	 * @throws \Weglot\Client\Api\Exception\MissingRequiredParamException
	 * @throws \Weglot\Client\Api\Exception\MissingWordsOutputException
	 * @throws \Weglot\Client\Api\Exception\ApiError
	 *
	 * @return string|string[] The translated content.
	 */
	public function translate( $content ) {
		$original_lang = $this->language->original()->getInternalCode();
		$current_lang  = $this->language->current()->getInternalCode();
		$canonical     = (string) wp_get_referer();

		if ( $original_lang === $current_lang ) {
			return $content;
		}

		if ( is_array( $content ) ) {
			return $this->translate_array( $content, $canonical );
		}

		$translated = $this->parser->translate( $content, $original_lang, $current_lang, [], $canonical );

		return $this->replace_urls( $translated );
	}

	/**
	 * Replace URLs with their translated path, if any.
	 *
	 * @param  string  $content The HTML that contains URLs to replace.
	 *
	 * @return string
	 */
	protected function replace_urls( string $content ): string {
		return $this->url->replace_link_in_dom( $content );
	}

	/**
	 * Translatable an array of HTML of content.
	 *
	 * @param  string[]  $content
	 *
	 * @throws \Weglot\Client\Api\Exception\ApiError
	 * @throws \Weglot\Client\Api\Exception\InputAndOutputCountMatchException
	 * @throws \Weglot\Client\Api\Exception\InvalidWordTypeException
	 * @throws \Weglot\Client\Api\Exception\MissingRequiredParamException
	 * @throws \Weglot\Client\Api\Exception\MissingWordsOutputException
	 *
	 * @return string[]
	 */
	protected function translate_array( array $content, string $canonical ): array {
		$content = implode( self::EOL_HTML, $content );

		$translated = $this->parser->translate(
			$content,
			$this->language->original()->getInternalCode(),
			$this->language->current()->getInternalCode(),
			[],
			$canonical
		);

		$translated = $this->replace_urls( $translated );

		return explode( self::EOL_HTML, $translated );
	}

}
