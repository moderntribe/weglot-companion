<?php declare(strict_types=1);

namespace Tribe\Weglot\Settings;

use Tribe\Libs\ACF\ACF_Settings;

class Weglot_Settings extends ACF_Settings {

	public function get_title(): string {
		return __( 'Weglot Companion Settings', 'weglot-companion' );
	}

	public function get_capability(): string {
		return 'activate_plugins';
	}

	public function get_parent_slug(): string {
		return 'options-general.php';
	}

}
