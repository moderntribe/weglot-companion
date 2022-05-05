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

	public function make( string $type ): Translatable {
		$strategy = $this->strategies[ $type ] ?? Html::class;

		return $this->container->make( $strategy );
	}

}
