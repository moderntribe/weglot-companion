<?php declare(strict_types=1);

namespace Tribe\Tests\Cache;

use Tribe\Tests\Test_Case;
use Tribe\Weglot\Cache\Post_Identifier;

final class PostIdentifierTest extends Test_Case {

	private string $original_request_uri;

	protected function setUp(): void {
		parent::setUp();

		$this->original_request_uri = $_SERVER['REQUEST_URI'];
	}

	protected function tearDown(): void {
		parent::tearDown();

		$GLOBALS['post']        = null;
		$_SERVER['REQUEST_URI'] = $this->original_request_uri;
	}

	public function test_it_identifies_a_post_by_post_global(): void {
		$post_id         = $this->factory()->post->create();
		$GLOBALS['post'] = get_post( $post_id );

		$post_identifier = $this->container->get( Post_Identifier::class );

		$this->assertSame( $post_id, $post_identifier->get_current_post_id() );
	}

	public function test_it_identifies_a_post_by_uri(): void {
		$post_id = $this->factory()->post->create( [
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_name'   => 'a-test-post',
		] );

		// set a mock REQUEST_URI for url_to_postid()
		$_SERVER['REQUEST_URI'] = '/a-test-post/';

		$this->assertEmpty( $GLOBALS['post'] );

		$post_identifier = $this->container->get( Post_Identifier::class );

		$this->assertSame( $post_id, $post_identifier->get_current_post_id() );
	}

	public function test_it_identifies_a_post_by_uri_with_bad_post_id(): void {
		$post_id = $this->factory()->post->create( [
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_name'   => 'another-test-post',
		] );

		// set a mock REQUEST_URI for url_to_postid()
		$_SERVER['REQUEST_URI'] = '/another-test-post/';

		$GLOBALS['post']     = get_post( $post_id );
		$GLOBALS['post']->ID = - 1;

		$post_identifier = $this->container->get( Post_Identifier::class );

		$this->assertSame( $post_id, $post_identifier->get_current_post_id() );
	}

	public function test_it_does_not_find_a_post_when_missing_http_host(): void {
		unset( $_SERVER['HTTP_HOST'] );

		$post_identifier = $this->container->get( Post_Identifier::class );

		$this->assertSame( 0, $post_identifier->get_current_post_id() );
	}

}
