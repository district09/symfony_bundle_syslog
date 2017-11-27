<?php

namespace DigipolisGent\SyslogBundle\DependencyInjection;

use DigipolisGent\SyslogBundle\DependencyInjection\Configuration;
use Symfony\Bundle\MonologBundle\DependencyInjection\MonologExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 * @codeCoverageIgnore
 */
class DigipolisGentSyslogExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container) {
        if ($container->hasExtension('monolog')) {
            $config = [
                'handlers' => [
                    'syslog_handler' => [
                        'type' => 'syslog',
                        'level' => 'debug',
                        'facility' => defined('LOG_LOCAL4') ? LOG_LOCAL4 : 160,
                        'formatter' => 'monolog.formatter.kibana'
                    ],
                ],
            ];
            $container->prependExtensionConfig('monolog', $config);
        }
    }

}
