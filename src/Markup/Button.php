<?php declare(strict_types=1);

namespace Tribe\Weglot\Markup;

class Button {

	public const BUTTON_MARKUP = '<div id="weglot_here"></div>';

	/**
	 * You can add this code wherever you want in the source code of your HTML page.
	 * Weglot will process this div and make the button appear in its place.
	 */
	public function get_button(): string {
		return self::BUTTON_MARKUP;
	}

}
