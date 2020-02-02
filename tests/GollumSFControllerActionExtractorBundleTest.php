<?php
namespace Test\GollumSF\ControllerActionExtractorBundle;

use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractor;
use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractorInterface;
use GollumSF\ControllerActionExtractorBundle\GollumSFControllerActionExtractorBundle;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;

class GollumSFControllerActionExtractorBundleTest extends BaseBundleTestCase {
	
	protected function getBundleClass() {
		return GollumSFControllerActionExtractorBundle::class;
	}
	protected function setUp(): void {
		parent::setUp();
		// Make all services public
		$this->addCompilerPass(new PublicServicePass('|GollumSF*|'));
	}

	public function testInitBundle() {
		
		// Boot the kernel.
		$this->bootKernel();

		// Get the container
		$container = $this->getContainer();

		$this->assertInstanceOf(ControllerActionExtractorInterface::class, $container->get(ControllerActionExtractorInterface::class));
		
		$this->assertInstanceOf(ControllerActionExtractor::class, $container->get(ControllerActionExtractorInterface::class));
	}
}
