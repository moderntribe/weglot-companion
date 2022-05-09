<?php declare(strict_types=1);

namespace Tribe\Tests\Translate;

use Tribe\Tests\Test_Case;
use Tribe\Weglot\Translate\Strategies\Html;
use Tribe\Weglot\Translate\Strategies\Json;
use Tribe\Weglot\Translate\Translatable;
use Tribe\Weglot\Translate\Translate_Subscriber;
use Tribe\Weglot\Translate\Translation_Factory;

class TranslateFactoryTest extends Test_Case {

	public function test_it_creates_translation_strategy_instances(): void {
		$factory = $this->container->get( Translation_Factory::class );

		$html = $factory->make( Translate_Subscriber::TYPE_HTML );

		$this->assertInstanceOf( Translatable::class, $html );
		$this->assertInstanceOf( Html::class, $html );

		$json = $factory->make( Translate_Subscriber::TYPE_JSON );

		$this->assertInstanceOf( Translatable::class, $json );
		$this->assertInstanceOf( Json::class, $json );

		$xml = $factory->make( Translate_Subscriber::TYPE_XML );

		$this->assertInstanceOf( Translatable::class, $xml );
		$this->assertInstanceOf( Html::class, $xml );

		// Should use the HTML strategy as the default
		$fallback = $factory->make( 'Unknown_Class' );

		$this->assertInstanceOf( Translatable::class, $fallback );
		$this->assertInstanceOf( Html::class, $fallback );

	}

}
