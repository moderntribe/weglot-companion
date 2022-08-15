<?php declare(strict_types=1);

namespace Tribe\Weglot\Render;

class Button {

	public const BUTTON_NEEDLE = '<div id="weglot_here"></div>';

	/**
	 * You can add this code wherever you want in the source code of your HTML page.
	 * The button will appear at this place.
	 *
	 * @return string
	 */
	public function get_button(): string {
		return self::BUTTON_NEEDLE;
	}

}
