<?php declare(strict_types=1);

namespace Tribe\Tests\Translate;

use Codeception\Test\Unit;
use Tribe\Weglot\Translate\Language;
use Tribe\Weglot\Translate\Uhf;
use Weglot\Client\Api\LanguageEntry;

final class UhfTest extends Unit {

	public function test_it_uses_default_locale_with_default_language(): void {
		$en = new LanguageEntry( 'en', 'en', 'English', 'English' );

		// Mock the Language object
		$language = $this->make( Language::class, [
			'original' => $en,
			'current'  => $en,
		] );

		$uhf    = new Uhf( $language );
		$locale = $uhf->set_cookie_banner_locale( 'en-US' );
		$this->assertSame( 'en-US', $locale );
	}

	public function test_it_sets_a_new_locale_with_translated_language(): void {
		$en = new LanguageEntry( 'en', 'en', 'English', 'English' );
		$sv = new LanguageEntry( 'sv', 'sv', 'Swedish', 'Swedish' );

		// Mock the Language object
		$language = $this->make( Language::class, [
			'original' => $en,
			'current'  => $sv,
		] );

		$uhf    = new Uhf( $language );
		$locale = $uhf->set_cookie_banner_locale( 'en-US' );
		$this->assertSame( 'sv', $locale );
	}

}
