<?php
namespace GollumSF\ControllerActionExtractorBundle\Extractor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

interface ControllerActionExtractorInterface {
	public function extractFromRoute(Route $route): ?ControllerAction;
	public function extractFromRequest(Request $request): ?ControllerAction;
	public function extractFromString($controllerAction): ?ControllerAction;
}