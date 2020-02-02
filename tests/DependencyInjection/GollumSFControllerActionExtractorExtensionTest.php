<?php
namespace Test\GollumSF\ControllerActionExtractorBundle\DependencyInjection;

use GollumSF\ControllerActionExtractorBundle\DependencyInjection\GollumSFControllerActionExtractorExtension;
use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractorInterface;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class GollumSFControllerActionExtractorExtensionTest extends AbstractExtensionTestCase {

	protected function getContainerExtensions(): array {
		return [
			new GollumSFControllerActionExtractorExtension()
		];
	}
	
	public function testLoad() {
		$this->load();
		$this->assertContainerBuilderHasService(ControllerActionExtractorInterface::class);
	}
}