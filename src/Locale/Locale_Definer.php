<?php declare(strict_types=1);

namespace Tribe\Weglot\Locale;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Weglot\Core;

class Locale_Definer implements Definer_Interface {

	public function define(): array {
		return [
			Locale::class => DI\autowire()
			->constructorParameter( 'locales', static fn ( ContainerInterface $c ) =>
				json_decode( file_get_contents( $c->get( Core::RESOURCES_PATH ) . '/json/locales.json' ), true )
			),
		];
	}

}
