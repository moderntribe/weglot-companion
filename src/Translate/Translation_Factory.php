<?php declare(strict_types=1);

namespace Tribe\Weglot\Translate;

use DI\FactoryInterface;
use Tribe\Weglot\Translate\Strategies\Html;

class Translation_Factory {

	protected FactoryInterface $container;

	/**
	 * A map of content types to their strategy class.
	 *
	 * @var array<string, string>
	 */
	protected array $strategies;

	public function __construct( FactoryInterface $container, array $strategies ) {
		$this->container  = $container;
		$this->strategies = $strategies;
	}

	/**
	 * Make a Translatable instance.
	 *
	 * @param string $type      The type of content to make the strategy from.
	 * @param array  $json_keys Additional JSON keys to process, if $type is JSON.
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @return \Tribe\Weglot\Translate\Translatable
	 */
	public function make( string $type, array $json_keys = [] ): Translatable {
		$strategy_class = $this->strategies[ $type ] ?? Html::class;

		return $this->container->make( $strategy_class, [
			'json_keys' => $json_keys,
		] );
	}

}
