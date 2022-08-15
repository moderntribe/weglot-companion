<?php declare(strict_types=1);

namespace Tribe\Tests\Markup;

use Codeception\Test\Unit;
use Tribe\Weglot\Markup\Button;

final class ButtonTest extends Unit {

	public function test_it_gets_weglot_button_markup(): void {
		$button = new Button();

		$this->assertSame( '<div id="weglot_here"></div>', $button->get_button() );
		$this->assertSame( '<div id="weglot_here"></div>', Button::BUTTON_MARKUP );
	}

}
