<?php declare(strict_types=1);

namespace Tribe\Weglot\Meta;

use Tribe\Libs\ACF;

class Weglot_Settings_Meta extends ACF\ACF_Meta_Group {

	public const NAME = 'weglot_companion_settings';

	public const FIELD_DISABLE_CACHING = 'weglot_disable_caching';

	public function get_keys(): array {
		return [
			self::FIELD_DISABLE_CACHING,
		];
	}

	/**
	 * @param string|int $key
	 * @param string|int $post_id
	 *
	 * @return mixed|null
	 */
	public function get_value( $key, $post_id = 'option' ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

	protected function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', esc_html__( 'Weglot Companion Settings', 'weglot-companion' ) );

		$group->add_field( $this->get_disable_caching_field() );

		return $group->get_attributes();
	}

	/**
	 * Disable caching field.
	 */
	private function get_disable_caching_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::FIELD_DISABLE_CACHING );

		$field->set_attributes( [
			'label'         => esc_html__( 'Disable Translation caching?', 'weglot-companion' ),
			'name'          => self::FIELD_DISABLE_CACHING,
			'type'          => 'true_false',
			'instructions'  => esc_html__( 'With the cache disabled, translations will use the Weglot API for every page load.', 'weglot-companion' ),
			'required'      => false,
			'default_value' => false,
			'ui'            => true,
		] );

		return $field;
	}

}
