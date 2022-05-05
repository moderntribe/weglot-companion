<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

/**
 * Different Weglot translation strategies should implement
 * this interface.
 */
interface Translatable {

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
	public function translate( $content );

}
