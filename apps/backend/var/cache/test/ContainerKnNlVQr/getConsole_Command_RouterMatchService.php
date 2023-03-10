<?php

namespace ContainerKnNlVQr;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_RouterMatchService extends Tramity_Apps_Backend_BackendKernelTestDebugContainer
{
    /**
     * Gets the private 'console.command.router_match' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Command\RouterMatchCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/framework-bundle/Command/RouterMatchCommand.php';

        $container->privates['console.command.router_match'] = $instance = new \Symfony\Bundle\FrameworkBundle\Command\RouterMatchCommand(($container->services['router'] ?? $container->getRouterService()), new RewindableGenerator(function () use ($container) {
            return new \EmptyIterator();
        }, 0));

        $instance->setName('router:match');
        $instance->setDescription('Help debug routes by simulating a path info match');

        return $instance;
    }
}
