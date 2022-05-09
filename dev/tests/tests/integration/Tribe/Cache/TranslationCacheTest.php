<?php declare(strict_types=1);

namespace Tribe\Tests\Cache;

use ReflectionClass;
use Tribe\Libs\Cache\Cache;
use Tribe\Tests\Test_Case;
use Tribe\Weglot\Cache\Translation_Cache;

final class TranslationCacheTest extends Test_Case {

	private Cache $cache;
	private Translation_Cache $translation_cache;
	private int $test_post_id = 44;

	protected function setUp(): void {
		parent::setUp();

		$this->cache             = new Cache();
		$this->translation_cache = new Translation_Cache( $this->cache, false );
	}

	public function test_it_builds_the_cache_key(): void {
		$this->assertSame( 'tribe_translated_44', $this->translation_cache->get_cache_key( $this->test_post_id )  );
	}

	public function test_it_sets_and_deletes_the_translation_cache_for_a_post(): void {
		// Make sure the translation cache is empty before we begin
		$this->assertSame( [], $this->translation_cache->get( $this->test_post_id ) );

		$german  = $this->translation_cache->set( 'Hallo auf Deutsche', 'de', $this->test_post_id );
		$swedish = $this->translation_cache->set( 'Hej på svenska', 'sv', $this->test_post_id );

		$this->assertTrue( $german );
		$this->assertTrue( $swedish );

		$this->assertSame( 'Hallo auf Deutsche', $this->translation_cache->get_by_language( 'de', $this->test_post_id ) );
		$this->assertSame( 'Hej på svenska', $this->translation_cache->get_by_language( 'sv', $this->test_post_id ) );

		// Check the reference keys inside the cache directly.
		$this->assertSame( [
			'de' => 'tribe_weglot_44_de',
			'sv' => 'tribe_weglot_44_sv',
		], $this->cache->get( 'tribe_translated_44' ) );

		$this->assertSame( [
			'de' => 'Hallo auf Deutsche',
			'sv' => 'Hej på svenska',
		], $this->translation_cache->get( $this->test_post_id ) );

		// Assert everything has been deleted from the cache.
		$this->assertTrue( $this->translation_cache->delete( $this->test_post_id ) );
		$this->assertSame( [], $this->translation_cache->get( $this->test_post_id ) );
		$this->assertEmpty(  $this->cache->get( 'tribe_translated_44' ) );
		$this->assertSame( '', $this->translation_cache->get_by_language( 'de', $this->test_post_id ) );
		$this->assertSame( '', $this->translation_cache->get_by_language( 'sv', $this->test_post_id ) );
	}

	public function test_in_sets_and_deletes_from_in_memory_cache(): void {
		$test_post_id_2 = 45;
		$cache          = new Translation_Cache( $this->cache, true );

		$this->assertSame( [], $cache->get( $this->test_post_id ) );

		$german = $this->translation_cache->set( 'Hallo auf Deutsche', 'de', $this->test_post_id );
		$swedish = $this->translation_cache->set( 'Hej på svenska', 'sv', $test_post_id_2 );

		$this->assertTrue( $german );
		$this->assertTrue( $swedish );

		$this->assertSame( [
			'de' => 'Hallo auf Deutsche',
		], $cache->get( $this->test_post_id ) );

		$this->assertSame( [
			'sv' => 'Hej på svenska',
		], $cache->get( $test_post_id_2 ) );

		$reflector = new ReflectionClass( Translation_Cache::class );
		$translation_prop = $reflector->getProperty( 'translation_cache' );
		$translation_prop->setAccessible( true );
		$reference_prop = $reflector->getProperty( 'reference_cache' );
		$reference_prop->setAccessible( true );

		$this->assertSame( [
			$this->test_post_id => [
				'de' => 'Hallo auf Deutsche',
			],
			$test_post_id_2 => [
				'sv' => 'Hej på svenska',
			],
		], $translation_prop->getValue( $cache ) );

		$this->assertSame( [
			$this->test_post_id => [
				'de' => 'tribe_weglot_44_de',
			],
			$test_post_id_2 => [
				'sv' => 'tribe_weglot_45_sv',
			],
		], $reference_prop->getValue( $cache ) );

		$this->assertTrue( $cache->delete( $this->test_post_id ) );

		$this->assertSame( [
			$test_post_id_2 => [
				'sv' => 'Hej på svenska',
			],
		], $translation_prop->getValue( $cache ) );

		$this->assertSame( [
			$test_post_id_2 => [
				'sv' => 'tribe_weglot_45_sv',
			],
		], $reference_prop->getValue( $cache ) );

		$this->assertTrue( $cache->delete( $test_post_id_2 ) );
		$this->assertEmpty( $translation_prop->getValue( $cache ) );
		$this->assertEmpty( $reference_prop->getValue( $cache ) );

	}

}
