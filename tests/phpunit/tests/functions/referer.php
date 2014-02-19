<?php

/**
 * Test wp_get_referer().
 *
 * @group functions.php
 */
class Tests_Functions_Referer extends WP_UnitTestCase {

	private $request = array();
	private $server = array();

	function setUp() {
		parent::setUp();

		$this->server = $_SERVER;
		$this->request = $_REQUEST;
	}

	function tearDown() {
		parent::tearDown();

		$_SERVER = $this->server;
		$_REQUEST = $this->request;

		remove_filter( 'site_url', array( 'Tests_Functions_Referer', '_fake_subfolder_install' ) );
	}

	static function _fake_subfolder_install() {
		return 'http://example.org/subfolder';
	}

	function test_wp_get_referer_from_request_same_url() {
		$_REQUEST['_wp_http_referer'] = addslashes( 'http://example.org/test.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/test.php?id=123' );
		$this->assertFalse( wp_get_referer() );
	}

	function test_wp_get_referer_from_request_different_resource() {
		$_REQUEST['_wp_http_referer'] = addslashes( 'http://example.org/another.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/test.php?id=123' );
		$this->assertEquals( 'http://example.org/another.php?id=123', wp_get_referer() );
	}

	function test_wp_get_referer_from_request_different_query_args() {
		$_REQUEST['_wp_http_referer'] = addslashes( 'http://example.org/test.php?another=555' );
		$_SERVER['REQUEST_URI'] = addslashes( '/test.php?id=123' );
		$this->assertEquals( 'http://example.org/test.php?another=555', wp_get_referer() );
	}

	/**
	 * @ticket 19856
	 */
	function test_subfolder_wp_get_referer_from_request_same_url() {
		add_filter( 'site_url', array( 'Tests_Functions_Referer', '_fake_subfolder_install' ) );

		$_REQUEST['_wp_http_referer'] = addslashes( 'http://example.org/subfolder/test.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/subfolder/test.php?id=123' );
		$this->assertFalse( wp_get_referer() );
	}

	/**
	 * @ticket 19856
	 */
	function test_subfolder_wp_get_referer_from_request_different_resource() {
		add_filter( 'site_url', array( 'Tests_Functions_Referer', '_fake_subfolder_install' ) );

		$_REQUEST['_wp_http_referer'] = addslashes( 'http://example.org/subfolder/another.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/subfolder/test.php?id=123' );
		$this->assertEquals( 'http://example.org/subfolder/another.php?id=123', wp_get_referer() );
	}

	function test_wp_get_referer_from_server_same_url() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://example.org/test.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/test.php?id=123' );
		$this->assertFalse( wp_get_referer() );
	}

	function test_wp_get_referer_from_server_different_resource() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://example.org/another.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/test.php?id=123' );
		$this->assertEquals( 'http://example.org/another.php?id=123', wp_get_referer() );
	}

	/**
	 * @ticket 19856
	 * @ticket 27152
	 */
	function test_wp_get_referer_different_server() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://another.example.org/test.php?id=123' );
		$_SERVER['REQUEST_URI'] = addslashes( '/test.php?id=123' );
		$this->assertEquals( 'http://another.example.org/test.php?id=123', wp_get_referer() );
	}

}
