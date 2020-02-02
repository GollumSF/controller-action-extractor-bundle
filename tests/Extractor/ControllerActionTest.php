<?php
namespace Test\GollumSF\ControllerActionExtractorBundle\Extractor;

use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerAction;
use PHPUnit\Framework\TestCase;

class ControllerActionTest extends TestCase {
	
	public function testModel() {
		$controllerAction = new ControllerAction(
			'controllerClass',
			'actionMethod'
		);

		$this->assertEquals($controllerAction->getControllerClass(), 'controllerClass');
		$this->assertEquals($controllerAction->getActionMethod(), 'actionMethod');
	}
}