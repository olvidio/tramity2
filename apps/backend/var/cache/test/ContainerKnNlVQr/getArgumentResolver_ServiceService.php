<?php

namespace ContainerKnNlVQr;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getArgumentResolver_ServiceService extends Tramity_Apps_Backend_BackendKernelTestDebugContainer
{
    /**
     * Gets the private 'argument_resolver.service' shared service.
     *
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ArgumentValueResolverInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ValueResolverInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ArgumentResolver/ServiceValueResolver.php';

        return $container->privates['argument_resolver.service'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver(($container->privates['.service_locator.TGeGvuk'] ?? $container->load('get_ServiceLocator_TGeGvukService')));
    }
}
