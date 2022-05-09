<?php declare(strict_types=1);

namespace Tribe\Weglot\Meta;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Object_Meta\Object_Meta_Definer;
use Tribe\Weglot\Settings\Weglot_Settings;

class Meta_Definer implements Definer_Interface {

	public function define(): array {
		return [
			Object_Meta_Definer::GROUPS => DI\add( [
				DI\get( Weglot_Settings_Meta::class ),
			] ),

			Weglot_Settings_Meta::class => DI\autowire()
				->constructorParameter( 'object_types', static fn( ContainerInterface $c ) => [
					'settings_pages' => [ $c->get( Weglot_Settings::class )->get_slug() ],
				] ),
		];
	}

}
