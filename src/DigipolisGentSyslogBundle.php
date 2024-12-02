<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Digipolis Gent Syslog Bundle.
 *
 * @codeCoverageIgnore
 */
final class DigipolisGentSyslogBundle extends AbstractBundle
{
    /**
     * @inheritDoc
     *
     * Adds the bundle services definitions.
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yml');
    }

    /**
     * @inheritDoc
     *
     * Adds a pre-defined syslog handler to the monolog configuration.
     */
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void {
        if (!$builder->hasExtension('monolog')) {
            return;
        }

        $config = [
            'handlers' => [
                'syslog' => [
                    'type' => 'syslog',
                    'level' => $builder->getParameter('kernel.environment') === 'prod' ? 'notice' : 'warning',
                    'facility' => defined('LOG_LOCAL4') ? LOG_LOCAL4 : 160,
                    'ident' => $builder->getParameter('digipolis_syslog_identity') ?: 'no_syslog_identity_set',
                    'logopts' => LOG_ODELAY,
                    'formatter' => 'monolog.formatter.kibana',
                ],
            ],
        ];

        $builder->prependExtensionConfig('monolog', $config);
    }
}
