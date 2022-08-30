<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Weglot\Translate\Strategies\Html;
use Tribe\Weglot\Translate\Strategies\Json;
use Weglot\Parser\Parser;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Replace_Url_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;
use WeglotWP\Services\Translate_Service_Weglot;

class Translate_Definer implements Definer_Interface {

	/**
	 * @return mixed[]
	 */
	public function define(): array {
		return [
			Parser::class                     => static fn () => weglot_get_service( 'Parser_Service_Weglot' )->get_parser(),
			Language_Service_Weglot::class    => static fn () => weglot_get_service( 'Language_Service_Weglot' ),
			Request_Url_Service_Weglot::class => static fn () => weglot_get_service( 'Request_Url_Service_Weglot' ),
			Replace_Url_Service_Weglot::class => static fn () => weglot_get_service( 'Replace_Url_Service_Weglot' ),
			Translate_Service_Weglot::class   => static fn () => weglot_get_service( 'Translate_Service_Weglot' ),
			Translation_Factory::class        => DI\autowire()
				->constructorParameter( 'strategies', [
					Translate_Subscriber::TYPE_HTML => Html::class,
					Translate_Subscriber::TYPE_JSON => Json::class,
					Translate_Subscriber::TYPE_XML  => Html::class,
				] ),
		];
	}

}
