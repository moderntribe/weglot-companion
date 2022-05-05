<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use Tribe\Libs\Container\Definer_Interface;
use Weglot\Parser\Parser;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Replace_Url_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;

class Translate_Definer implements Definer_Interface {

	public function define(): array {
		return [
			Parser::class                     => static fn () => weglot_get_service( 'Parser_Service_Weglot' )->get_parser(),
			Language_Service_Weglot::class    => static fn () => weglot_get_service( 'Language_Service_Weglot' ),
			Request_Url_Service_Weglot::class => static fn () => weglot_get_service( 'Request_Url_Service_Weglot' ),
			Replace_Url_Service_Weglot::class => static fn () => weglot_get_service( 'Replace_Url_Service_Weglot' ),
		];
	}

}
