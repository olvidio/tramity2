<?php

namespace ContainerObQFv85;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getOtroGetControllerService extends Tramity_Apps_Backend_BackendKernelTestDebugContainer
{
    /**
     * Gets the public 'Tramity\Apps\Backend\controller\otro\OtroGetController' shared autowired service.
     *
     * @return \Tramity\Apps\Backend\controller\otro\OtroGetController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/controller/otro/OtroGetController.php';
        include_once \dirname(__DIR__, 6).'/src/shared/domain/RandomNumberGenerator.php';
        include_once \dirname(__DIR__, 6).'/src/shared/infrastructure/PhpRandomNumberGenerator.php';

        return $container->services['Tramity\\Apps\\Backend\\controller\\otro\\OtroGetController'] = new \Tramity\Apps\Backend\controller\otro\OtroGetController(($container->privates['Tramity\\shared\\infrastructure\\PhpRandomNumberGenerator'] ??= new \Tramity\shared\infrastructure\PhpRandomNumberGenerator()));
    }
}
