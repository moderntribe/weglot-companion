<?php declare(strict_types=1);

namespace Tribe\Weglot\Resources\Admin;

use Tribe\Weglot\Resources\Loader;

/**
 * Block editor script & style loader.
 */
class Editor_Script_Loader extends Loader {

	/**
	 * @action enqueue_block_editor_assets
	 */
	public function enqueue(): void {
		wp_enqueue_script( 'weglot-companion-editor-js', $this->manifest_loader->get_manifest()['/js/admin/editor.js'] );
		wp_enqueue_style( 'weglot-companion-editor-css', $this->manifest_loader->get_manifest()['/css/admin/editor.css'] );
	}

}
