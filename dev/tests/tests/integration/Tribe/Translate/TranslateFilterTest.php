<?php declare(strict_types=1);

namespace Tribe\Tests\Translate;

use Tribe\Tests\Test_Case;
use Tribe\Weglot\Translate\Translate_Subscriber;

class TranslateFilterTest extends Test_Case {

	public function test_it_runs_the_translate_filter(): void {
		$this->assertTrue( has_filter( Translate_Subscriber::FILTER ) );

		$content = apply_filters( Translate_Subscriber::FILTER, 'test' );

		$this->assertSame( 'test', $content );
	}

}
