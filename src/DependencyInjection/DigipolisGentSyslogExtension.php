<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\DependencyInjection;

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
final class DigipolisGentSyslogExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../config')
        );
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('monolog')) {
            return;
        }

        $config = [
            'handlers' => [
                'syslog_handler' => [
                    'type' => 'syslog',
                    'level' => $container->getParameter("kernel.environment") === 'prod' ? 'notice' : 'warning',
                    'facility' => defined('LOG_LOCAL4') ? LOG_LOCAL4 : 160,
                    'ident' => $container->getParameter('digipolis_syslog_identity') ?: 'no_syslog_identity_set',
                    'logopts' => LOG_ODELAY,
                    'formatter' => 'monolog.formatter.kibana',
                ],
            ],
        ];

        $container->prependExtensionConfig('monolog', $config);
    }
}
