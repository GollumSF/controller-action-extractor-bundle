<?php
namespace Test\GollumSF\ControllerActionExtractorBundle;

use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractor;
use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractorInterface;
use GollumSF\ControllerActionExtractorBundle\GollumSFControllerActionExtractorBundle;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;

class FixedTestKernel extends TestKernel {
	protected function buildContainer(): ContainerBuilder {
		$container = parent::buildContainer();
		// Fix nyholm v3 bug: remove 'annotations' config for SF 8+ compat
		$r = new \ReflectionProperty($container, 'extensionConfigs');
		$r->setAccessible(true);
		$all = $r->getValue($container);
		if (isset($all['framework'])) {
			foreach ($all['framework'] as $i => $config) {
				unset($all['framework'][$i]['annotations']);
			}
			$r->setValue($container, $all);
		}
		return $container;
	}
}

class GollumSFControllerActionExtractorBundleTest extends KernelTestCase {

	protected static function getKernelClass(): string {
		return FixedTestKernel::class;
	}

	protected static function createKernel(array $options = []): KernelInterface {
		$kernel = new FixedTestKernel('test', true);
		if (isset($options['config']) && is_callable($options['config'])) {
			$options['config']($kernel);
		}
		return $kernel;
	}

	#[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
	public function testInitBundle() {

		self::bootKernel(['config' => function (TestKernel $kernel) {
			$kernel->addTestBundle(GollumSFControllerActionExtractorBundle::class);
			$kernel->addTestCompilerPass(new class implements CompilerPassInterface {
				public function process(ContainerBuilder $container): void {
					foreach ($container->getDefinitions() as $id => $definition) {
						if (str_starts_with($id, 'GollumSF\\')) {
							$definition->setPublic(true);
						}
					}
					foreach ($container->getAliases() as $id => $alias) {
						if (str_starts_with($id, 'GollumSF\\')) {
							$alias->setPublic(true);
						}
					}
				}
			}, PassConfig::TYPE_BEFORE_REMOVING);
		}]);

		$container = self::$kernel->getContainer();

		$this->assertInstanceOf(ControllerActionExtractorInterface::class, $container->get(ControllerActionExtractorInterface::class));
		$this->assertInstanceOf(ControllerActionExtractor::class, $container->get(ControllerActionExtractorInterface::class));

	}
}
