<?php declare(strict_types=1);

namespace Tribe\Weglot\Config;

use Tribe\Libs\Container\Abstract_Subscriber;

class Config_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter(
			'weglot_get_api_key',
			fn ( $key ): string =>
			$this->container->get( Weglot_Api::class )->set_api_key( (string) $key ),
			10,
			1
		);
	}

}
