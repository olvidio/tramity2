<?php

namespace ContainerKnNlVQr;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getPhpRandomNumberGeneratorService extends Tramity_Apps_Backend_BackendKernelTestDebugContainer
{
    /**
     * Gets the private 'Tramity\shared\infrastructure\PhpRandomNumberGenerator' shared autowired service.
     *
     * @return \Tramity\shared\infrastructure\PhpRandomNumberGenerator
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/shared/domain/RandomNumberGenerator.php';
        include_once \dirname(__DIR__, 6).'/src/shared/infrastructure/PhpRandomNumberGenerator.php';

        return $container->privates['Tramity\\shared\\infrastructure\\PhpRandomNumberGenerator'] = new \Tramity\shared\infrastructure\PhpRandomNumberGenerator();
    }
}