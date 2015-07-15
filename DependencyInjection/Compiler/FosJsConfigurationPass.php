<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace OroCRM\Bundle\AmazonBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FosJsConfigurationPass implements CompilerPassInterface
{
    const FOS_JS_CONF_SERVICE_KEY = 'fos_js_routing.extractor';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::FOS_JS_CONF_SERVICE_KEY)) {
            return;
        }

        $container->getDefinition('fos_js_routing.extractor')
            ->replaceArgument(1, array(0 => "[oro_*, eltrino_*]"));
    }
}
