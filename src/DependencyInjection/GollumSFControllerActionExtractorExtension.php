<?php
namespace GollumSF\ControllerActionExtractorBundle\DependencyInjection;

use GollumSF\RestBundle\Configuration\ApiConfiguration;
use GollumSF\RestBundle\Configuration\ApiConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class GollumSFControllerActionExtractorExtension extends Extension {
	
	public function load(array $configs, ContainerBuilder $container) {
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');
	}
}