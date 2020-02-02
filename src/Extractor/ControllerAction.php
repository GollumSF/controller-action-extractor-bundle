<?php
namespace GollumSF\ControllerActionExtractorBundle\Extractor;

class ControllerAction {
	
	/** @var string */
	private $controllerClass;

	/** @var string */
	private $actionMethod;
	
	public function __construct(
		string $controllerClass,
		string $actionMethod
	) {
		$this->controllerClass = $controllerClass;
		$this->actionMethod = $actionMethod;
	}

	public function getControllerClass(): string {
		return $this->controllerClass;
	}

	public function getActionMethod(): string {
		return $this->actionMethod;
	}
}