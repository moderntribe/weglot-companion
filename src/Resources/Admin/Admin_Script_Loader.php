<?php declare(strict_types=1);

namespace Tribe\Weglot\Resources\Admin;

use Tribe\Weglot\Resources\Loader;

/**
 * wp-admin/dashboard script & style loader.
 */
class Admin_Script_Loader extends Loader {

	/**
	 * @action admin_enqueue_scripts
	 */
	public function enqueue(): void {
		wp_enqueue_script( 'weglot-companion-admin-index-js', $this->manifest_loader->get_manifest()['/js/admin/index.js'] );
		wp_enqueue_style( 'weglot-companion-admin-main-css', $this->manifest_loader->get_manifest()['/css/admin/main.css'] );
	}

}
