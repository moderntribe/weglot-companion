<?php declare(strict_types=1);

namespace Tribe\Weglot;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Throwable;
use Tribe\Libs\Object_Meta\Object_Meta_Subscriber;
use Tribe\Libs\Settings\Settings_Subscriber;
use Tribe\Weglot\Cache\Cache_Subscriber;
use Tribe\Weglot\Config\Config_Subscriber;
use Tribe\Weglot\Meta\Meta_Definer;
use Tribe\Weglot\Settings\Settings_Definer;
use Tribe\Weglot\Translate\Translate_Definer;
use Tribe\Weglot\Translate\Translate_Subscriber;

/**
 * Class Core
 *
 * @package Tribe\Weglot
 */
final class Core {

	public const    PLUGIN_FILE        = 'plugin.file';
	public const    VERSION_DEFINITION = 'plugin.version';
	public const    VERSION            = '1.0.0';
	public const    RESOURCES_PATH     = 'plugin.resources_path';
	public const    RESOURCES_URI      = 'plugin.resources_uri';
	public const    DIST_DIR_PATH      = 'plugin.dist_dir_path';
	public const    DIST_DIR_URI       = 'plugin.dist_dir_uri';
	protected const RESOURCES_DIR      = 'resources';
	protected const DIST_DIR           = 'dist';

	private ContainerInterface $container;

	/**
	 * @var \Tribe\Libs\Container\Definer_Interface[]
	 */
	private array $definers = [
		Translate_Definer::class,
		Meta_Definer::class,
		Settings_Definer::class,
	];

	/**
	 * @var \Tribe\Libs\Container\Abstract_Subscriber[]
	 */
	private array $subscribers = [
		Config_Subscriber::class,
		Object_Meta_Subscriber::class,
		Cache_Subscriber::class,
		Translate_Subscriber::class,
		Settings_Subscriber::class,
	];

	private static self $instance;

	private function __construct() {
	}

	/**
	 * Singleton constructor.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param  string  $plugin_path  The server path to the main plugin file.
	 */
	public function init( string $plugin_path ): void {
		// Build an auto-wiring service container.
		$builder = new ContainerBuilder();
		$builder->useAutowiring( true );
		$builder->useAnnotations( false );
		$builder->addDefinitions( [ self::PLUGIN_FILE => $plugin_path ] );
		$builder->addDefinitions( [ self::VERSION_DEFINITION => self::VERSION ] );
		$builder->addDefinitions( [ self::RESOURCES_PATH => plugin_dir_path( $plugin_path ) . self::RESOURCES_DIR ] );
		$builder->addDefinitions( [ self::RESOURCES_URI => plugin_dir_url( $plugin_path ) . self::RESOURCES_DIR ] );
		$builder->addDefinitions( [ self::DIST_DIR_PATH => plugin_dir_path( $plugin_path ) . sprintf( '%s/%s', self::RESOURCES_DIR, self::DIST_DIR ) ] );
		$builder->addDefinitions( [ self::DIST_DIR_URI => plugin_dir_url( $plugin_path ) . sprintf( '%s/%s', self::RESOURCES_DIR, self::DIST_DIR ) ] );
		$builder->addDefinitions( ...array_map( static function ( $classname ) {
			return ( new $classname() )->define();
		}, $this->definers ) );

		try {
			$this->container = $builder->build();
		} catch ( Throwable $e ) {
			return;
		}

		foreach ( $this->subscribers as $subscriber_class ) {
			( new $subscriber_class( $this->container ) )->register();
		}
	}

	/**
	 * Returns the PHP-DI container.
	 *
	 * @return \Psr\Container\ContainerInterface
	 */
	public function get_container(): ContainerInterface {
		return $this->container;
	}

	private function __clone() {
	}

}
