<?php declare(strict_types=1);

namespace Tribe\Tests\Locale;

use Tribe\Tests\Test_Case;
use Tribe\Weglot\Locale\Locale;
use function Tribe\Weglot\tribe_weglot;

final class LocaleTest extends Test_Case {

	private Locale $locale;

	protected function setUp(): void {
		parent::setUp();

		$this->locale = tribe_weglot()->get_container()->get( Locale::class );
	}

	public function test_it_gets_locales(): void {
		// Afrikaans
		$this->assertSame( 'af-ZA', $this->locale->get_locale( 'af' ) );
		// Azerbaijan
		$this->assertSame( 'az-Latn-AZ', $this->locale->get_locale( 'az' ) );
		// Armenian
		$this->assertSame( 'hy-AM', $this->locale->get_locale( 'hy' ) );
		// Albanian
		$this->assertSame( 'sq', $this->locale->get_locale( 'sq' ) );
		// Amharic
		$this->assertSame( 'am-ET', $this->locale->get_locale( 'am' ) );
		// Arabic
		$this->assertSame( 'ar', $this->locale->get_locale( 'ar' ) );
		// Assamese (India)
		$this->assertSame( 'as-IN', $this->locale->get_locale( 'as' ) );
		// Basque
		$this->assertSame( 'eu-ES', $this->locale->get_locale( 'eu' ) );
		// Bashkir - Russia (doesn't exist for MS, fallback to ru-RU)
		$this->assertSame( 'ru-RU', $this->locale->get_locale( 'ba' ) );
	}

	public function test_fallback_codes(): void {
		$this->assertSame( 'en-US', $this->locale->get_locale( 'not-found', 'en' ) );
		$this->assertSame( 'zh-CN', $this->locale->get_locale( 'another-not-found', 'zh' ) );
	}

	public function test_code_pass_through(): void {
		$this->assertSame( 'not-found', $this->locale->get_locale( 'not-found' ) );
	}

}
