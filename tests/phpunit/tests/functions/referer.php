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
	}

	function test_wp_get_referer_from_request_same_url() {
		$_REQUEST['_wp_http_referer'] = get_site_url() . '/test.php?id=123';
		$_SERVER['REQUEST_URI'] = '/test.php?id=123';
		$this->assertFalse( wp_get_referer() );
	}

	function test_wp_get_referer_from_request_different_resource() {
		$_REQUEST['_wp_http_referer'] = get_site_url() . '/another.php?id=123';
		$_SERVER['REQUEST_URI'] = '/test.php?id=123';
		$this->assertEquals( get_site_url() . '/another.php?id=123', wp_get_referer() );
	}

	function test_wp_get_referer_from_request_different_query_args() {
		$_REQUEST['_wp_http_referer'] = get_site_url() . '/test.php?another=555';
		$_SERVER['REQUEST_URI'] = '/test.php?id=123';
		$this->assertEquals( get_site_url() . '/test.php?another=555', wp_get_referer() );
	}

	function test_wp_get_referer_from_server_same_url() {
		$_SERVER['HTTP_REFERER'] = get_site_url() . '/test.php?id=123';
		$_SERVER['REQUEST_URI'] = '/test.php?id=123';
		$this->assertFalse( wp_get_referer() );
	}

	function test_wp_get_referer_from_server_different_resource() {
		$_SERVER['HTTP_REFERER'] = get_site_url() . '/another.php?id=123';
		$_SERVER['REQUEST_URI'] = '/test.php?id=123';
		$this->assertEquals( get_site_url() . '/another.php?id=123', wp_get_referer() );
	}

	function test_wp_get_referer_different_server() {
		$_SERVER['HTTP_REFERER'] = 'http://another.example.org/test.php?id=123';
		$_SERVER['REQUEST_URI'] = '/test.php?id=123';
		$this->assertEquals( 'http://another.example.org/test.php?id=123', wp_get_referer() );
	}
}
