<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
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
     * - Adds the bundle services definitions.
     * - Add a preconfigured Syslog handler using the kibana formatter.
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yml');

        $config = [
          'handlers' => [
            'syslog' => [
              'type' => 'syslog',
              'level' => $builder->getParameter('kernel.environment') === 'prod' ? 'notice' : 'warning',
              'facility' => defined('LOG_LOCAL4') ? LOG_LOCAL4 : 160,
              'ident' => $builder->hasParameter('digipolis_syslog_identity') ? $builder->getParameter('digipolis_syslog_identity') : 'no_syslog_identity_set',
              'logopts' => LOG_ODELAY,
              'formatter' => 'monolog.formatter.kibana',
            ],
          ],
        ];
        $builder->prependExtensionConfig('monolog', $config);
    }

}
