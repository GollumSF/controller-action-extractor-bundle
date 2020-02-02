<?php
namespace Test\GollumSF\ControllerActionExtractorBundle\Extractor;

use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerAction;
use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class StubController {
	public function action() {
	}
}

class ControllerActionExtractorTest extends TestCase {
	
	public function providerExtractFromString() {
		return [
			[ StubController::class, StubController::class, '__invoke' ],
			[ StubController::class.'::action', StubController::class, 'action' ],
			[ [ StubController::class, 'action' ], StubController::class, 'action' ],
			[ [ new StubController(), 'action' ], StubController::class, 'action' ],
		];
	}

	/**
	 * @dataProvider providerExtractFromString
	 */
	public function testExtractFromString($controllerAction, $controllerClass, $action) {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;
			
		$container
			->method('has')
			->with($controllerClass)
			->willReturn(false)
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromString($controllerAction);
		$this->assertInstanceOf(ControllerAction::class, $controllerAction);
		$this->assertEquals($controllerAction->getControllerClass(), $controllerClass);
		$this->assertEquals($controllerAction->getActionMethod(), $action);
	}

	public function testExtractFromStringService() {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;

		$container
			->method('has')
			->with('serviceName')
			->willReturn(true)
		;
		$container
			->method('get')
			->with('serviceName')
			->willReturn(new StubController())
		;
		
		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromString(['serviceName', 'action']);
		$this->assertInstanceOf(ControllerAction::class, $controllerAction);
		$this->assertEquals($controllerAction->getControllerClass(), StubController::class);
		$this->assertEquals($controllerAction->getActionMethod(), 'action');
	}

	public function testExtractFromStringNull() {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromString(null);
		$this->assertNull($controllerAction);
	}

	public function testExtractFromStringBad() {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromString(new \stdClass());
		$this->assertNull($controllerAction);
	}

	/**
	 * @dataProvider providerExtractFromString
	 */
	public function testExtractFromRoute($controllerAction, $controllerClass, $action) {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;

		$container
			->method('has')
			->with($controllerClass)
			->willReturn(false)
		;

		$route = $this->getMockBuilder(Route::class)->disableOriginalConstructor()->getMock();
		$route
			->expects($this->once())
			->method('getDefault')
			->with('_controller')
			->willReturn($controllerAction)
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromRoute($route);
		$this->assertInstanceOf(ControllerAction::class, $controllerAction);
		$this->assertEquals($controllerAction->getControllerClass(), $controllerClass);
		$this->assertEquals($controllerAction->getActionMethod(), $action);
	}

	/**
	 * @dataProvider providerExtractFromString
	 */
	public function testExtractFromRequest($controllerAction, $controllerClass, $action) {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;

		$container
			->method('has')
			->with($controllerClass)
			->willReturn(false)
		;

		$request    = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
		$attributes = $this->getMockBuilder(ParameterBag::class)->disableOriginalConstructor()->getMock();
		$request->attributes = $attributes;
		$attributes
			->expects($this->once())
			->method('get')
			->with('_controller')
			->willReturn($controllerAction)
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromRequest($request);
		$this->assertInstanceOf(ControllerAction::class, $controllerAction);
		$this->assertEquals($controllerAction->getControllerClass(), $controllerClass);
		$this->assertEquals($controllerAction->getActionMethod(), $action);
	}

	public function testExtractFromRouteNull() {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;

		$route = $this->getMockBuilder(Route::class)->disableOriginalConstructor()->getMock();
		$route
			->expects($this->once())
			->method('getDefault')
			->with('_controller')
			->willReturn(null)
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromRoute($route);
		$this->assertNull($controllerAction);
	}

	public function testExtractFromRequestNull() {

		$container = $this->getMockBuilder(ContainerInterface::class)
			->getMockForAbstractClass()
		;
		
		$request    = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
		$attributes = $this->getMockBuilder(ParameterBag::class)->disableOriginalConstructor()->getMock();
		$request->attributes = $attributes;
		$attributes
			->expects($this->once())
			->method('get')
			->with('_controller')
			->willReturn(null)
		;

		$controllerActionExtractor = new ControllerActionExtractor($container);

		$controllerAction = $controllerActionExtractor->extractFromRequest($request);
		$this->assertNull($controllerAction);
	}
}