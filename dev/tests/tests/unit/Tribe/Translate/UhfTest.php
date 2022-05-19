<?php declare(strict_types=1);

namespace Tribe\Tests\Translate;

use Codeception\Test\Unit;
use Tribe\Weglot\Locale\Locale;
use Tribe\Weglot\Translate\Language;
use Tribe\Weglot\Translate\Uhf;
use Weglot\Client\Api\LanguageEntry;

final class UhfTest extends Unit {

	private Locale $locale;

	protected function setUp(): void {
		parent::setUp();

		$this->locale = new Locale( [
			'en' => 'en-US',
			'sv' => 'sv-SE',
		] );
	}

	public function test_it_uses_default_locale_with_default_language(): void {
		$en = new LanguageEntry( 'en', 'en', 'English', 'English' );

		// Mock the Language object
		$language = $this->make( Language::class, [
			'original' => $en,
			'current'  => $en,
		] );

		$uhf    = new Uhf( $language, $this->locale );
		$locale = $uhf->set_cookie_banner_locale( 'en-US' );
		$this->assertSame( 'en-US', $locale );
	}

	public function test_it_uses_fallback_language_code_when_locale_mapping_is_missing(): void {
		$en = new LanguageEntry( 'en', 'en', 'English', 'English' );
		$de = new LanguageEntry( 'de', 'de', 'German', 'Deutsch' );

		// Mock the Language object
		$language = $this->make( Language::class, [
			'original' => $en,
			'current'  => $de,
		] );

		$uhf    = new Uhf( $language, $this->locale );
		$locale = $uhf->set_cookie_banner_locale( 'en-US' );
		$this->assertSame( 'de', $locale );
	}

	public function test_it_sets_a_new_locale_with_translated_language(): void {
		$en = new LanguageEntry( 'en', 'en', 'English', 'English' );
		$sv = new LanguageEntry( 'sv', 'sv', 'Swedish', 'Swedish' );

		// Mock the Language object
		$language = $this->make( Language::class, [
			'original' => $en,
			'current'  => $sv,
		] );

		$uhf    = new Uhf( $language, $this->locale );
		$locale = $uhf->set_cookie_banner_locale( 'en-US' );
		$this->assertSame( 'sv-SE', $locale );
	}

}
