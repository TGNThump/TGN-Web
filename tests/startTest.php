<?php

use Slim\Slim;

class StartTest extends PHPUnit_Framework_TestCase{

	protected $app;

	public function setUp(){
		$this->app = Slim::getInstance();
	}

	public function testApp(){
		$this->assertNotNull($this->app);
	}

	public function testConfig(){
		$this->assertNotNull($this->app->config);
	}

	public function testView(){
		$this->assertNotNull($this->app->view);
	}

	/**
     * @dataProvider configKeysProvider
     */
	public function testConfigOptions($var){
		$result = $this->app->config->get($var);
		$this->assertNotNull($result);
	}

	public function configKeysProvider(){
		return array(
			array('app.www'),
			array('app.core'),
			array('app.hash.algo'),
			array('app.hash.cost'),
			array('twig.debug')
		);
	}

}