<?php

namespace ContainerKnNlVQr;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class Tramity_Apps_Backend_BackendKernelTestDebugContainer extends Container
{
    protected $containerDir;
    protected $targetDir;
    protected $parameters = [];
    private $buildParameters;
    protected \Closure $getService;

    public function __construct(array $buildParameters = [], $containerDir = __DIR__)
    {
        $this->getService = $this->getService(...);
        $this->buildParameters = $buildParameters;
        $this->containerDir = $containerDir;
        $this->targetDir = \dirname($containerDir);
        $this->parameters = $this->getDefaultParameters();

        $this->services = $this->privates = [];
        $this->syntheticIds = [
            'behat.service_container' => true,
            'kernel' => true,
        ];
        $this->methodMap = [
            'event_dispatcher' => 'getEventDispatcherService',
            'http_kernel' => 'getHttpKernelService',
            'request_stack' => 'getRequestStackService',
            'router' => 'getRouterService',
        ];
        $this->fileMap = [
            'Symfony\\Bundle\\FrameworkBundle\\Controller\\RedirectController' => 'getRedirectControllerService',
            'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController' => 'getTemplateControllerService',
            'Tramity\\Apps\\Backend\\controller\\otro\\OtroGetController' => 'getOtroGetControllerService',
            'Tramity\\Tests\\shared\\infrastructure\\Behat\\ApiRequestContext' => 'getApiRequestContextService',
            'Tramity\\Tests\\shared\\infrastructure\\Behat\\ApiResponseContext' => 'getApiResponseContextService',
            'behat.driver.service_container' => 'getBehat_Driver_ServiceContainerService',
            'behat.mink' => 'getBehat_MinkService',
            'behat.mink.default_session' => 'getBehat_Mink_DefaultSessionService',
            'behat.mink.parameters' => 'getBehat_Mink_ParametersService',
            'cache.app' => 'getCache_AppService',
            'cache.app_clearer' => 'getCache_AppClearerService',
            'cache.global_clearer' => 'getCache_GlobalClearerService',
            'cache.system' => 'getCache_SystemService',
            'cache.system_clearer' => 'getCache_SystemClearerService',
            'cache.validator_expression_language' => 'getCache_ValidatorExpressionLanguageService',
            'cache_warmer' => 'getCacheWarmerService',
            'console.command_loader' => 'getConsole_CommandLoaderService',
            'container.env_var_processors_locator' => 'getContainer_EnvVarProcessorsLocatorService',
            'container.get_routing_condition_service' => 'getContainer_GetRoutingConditionServiceService',
            'error_controller' => 'getErrorControllerService',
            'messenger.default_bus' => 'getMessenger_DefaultBusService',
            'routing.loader' => 'getRouting_LoaderService',
            'services_resetter' => 'getServicesResetterService',
            'test.client' => 'getTest_ClientService',
            'test.private_services_locator' => 'getTest_PrivateServicesLocatorService',
            'test.service_container' => 'getTest_ServiceContainerService',
        ];
        $this->aliases = [
            'Tramity\\Apps\\Backend\\BackendKernel' => 'kernel',
        ];

        $this->privates['service_container'] = function () {
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ControllerResolverInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ControllerResolver.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ContainerControllerResolver.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/framework-bundle/Controller/ControllerResolver.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadataFactoryInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadataFactory.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ArgumentResolverInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Controller/ArgumentResolver.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/event-dispatcher/EventSubscriberInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/ResponseListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/LocaleListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/ValidateRequestListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/DisallowRobotsIndexingListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/ErrorListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/CacheAttributeListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ParameterBag/ParameterBagInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ParameterBag/ParameterBag.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ParameterBag/FrozenParameterBag.php';
            include_once \dirname(__DIR__, 6).'/vendor/psr/container/src/ContainerInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ParameterBag/ContainerBagInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ParameterBag/ContainerBag.php';
            include_once \dirname(__DIR__, 6).'/vendor/psr/event-dispatcher/src/EventDispatcherInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/event-dispatcher-contracts/EventDispatcherInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/event-dispatcher/EventDispatcherInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/event-dispatcher/EventDispatcher.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/HttpKernelInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/TerminableInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/HttpKernel.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-foundation/RequestStack.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/config/ConfigCacheFactoryInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/config/ResourceCheckerConfigCacheFactory.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/DebugHandlersListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/RequestContext.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/EventListener/RouterListener.php';
            include_once \dirname(__DIR__, 6).'/vendor/psr/log/Psr/Log/LoggerInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/psr/log/Psr/Log/AbstractLogger.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Log/DebugLoggerInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Log/Logger.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/RequestContextAwareInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/Matcher/UrlMatcherInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/Generator/UrlGeneratorInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/RouterInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/Matcher/RequestMatcherInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/Router.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/CacheWarmer/WarmableInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/service-contracts/ServiceSubscriberInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/framework-bundle/Routing/Router.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/service-contracts/ServiceProviderInterface.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/service-contracts/ServiceLocatorTrait.php';
            include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ServiceLocator.php';
        };
    }

    public function compile(): void
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled(): bool
    {
        return true;
    }

    public function getRemovedIds(): array
    {
        return require $this->containerDir.\DIRECTORY_SEPARATOR.'removed-ids.php';
    }

    protected function load($file, $lazyLoad = true)
    {
        if (class_exists($class = __NAMESPACE__.'\\'.$file, false)) {
            return $class::do($this, $lazyLoad);
        }

        if ('.' === $file[-4]) {
            $class = substr($class, 0, -4);
        } else {
            $file .= '.php';
        }

        $service = require $this->containerDir.\DIRECTORY_SEPARATOR.$file;

        return class_exists($class, false) ? $class::do($this, $lazyLoad) : $service;
    }

    protected function createProxy($class, \Closure $factory)
    {
        class_exists($class, false) || require __DIR__.'/'.$class.'.php';

        return $factory();
    }

    /**
     * Gets the public 'event_dispatcher' shared service.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcherService()
    {
        $this->services['event_dispatcher'] = $instance = new \Symfony\Component\EventDispatcher\EventDispatcher();

        $instance->addListener('kernel.response', [0 => #[\Closure(name: 'response_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener')] function () {
            return ($this->privates['response_listener'] ??= new \Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8', false));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.request', [0 => #[\Closure(name: 'locale_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener')] function () {
            return ($this->privates['locale_listener'] ?? $this->getLocaleListenerService());
        }, 1 => 'setDefaultLocale'], 100);
        $instance->addListener('kernel.request', [0 => #[\Closure(name: 'locale_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener')] function () {
            return ($this->privates['locale_listener'] ?? $this->getLocaleListenerService());
        }, 1 => 'onKernelRequest'], 16);
        $instance->addListener('kernel.finish_request', [0 => #[\Closure(name: 'locale_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener')] function () {
            return ($this->privates['locale_listener'] ?? $this->getLocaleListenerService());
        }, 1 => 'onKernelFinishRequest'], 0);
        $instance->addListener('kernel.request', [0 => #[\Closure(name: 'validate_request_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\ValidateRequestListener')] function () {
            return ($this->privates['validate_request_listener'] ??= new \Symfony\Component\HttpKernel\EventListener\ValidateRequestListener());
        }, 1 => 'onKernelRequest'], 256);
        $instance->addListener('kernel.response', [0 => #[\Closure(name: 'disallow_search_engine_index_response_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\DisallowRobotsIndexingListener')] function () {
            return ($this->privates['disallow_search_engine_index_response_listener'] ??= new \Symfony\Component\HttpKernel\EventListener\DisallowRobotsIndexingListener());
        }, 1 => 'onResponse'], -255);
        $instance->addListener('kernel.controller_arguments', [0 => #[\Closure(name: 'exception_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\ErrorListener')] function () {
            return ($this->privates['exception_listener'] ?? $this->getExceptionListenerService());
        }, 1 => 'onControllerArguments'], 0);
        $instance->addListener('kernel.exception', [0 => #[\Closure(name: 'exception_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\ErrorListener')] function () {
            return ($this->privates['exception_listener'] ?? $this->getExceptionListenerService());
        }, 1 => 'logKernelException'], 0);
        $instance->addListener('kernel.exception', [0 => #[\Closure(name: 'exception_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\ErrorListener')] function () {
            return ($this->privates['exception_listener'] ?? $this->getExceptionListenerService());
        }, 1 => 'onKernelException'], -128);
        $instance->addListener('kernel.response', [0 => #[\Closure(name: 'exception_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\ErrorListener')] function () {
            return ($this->privates['exception_listener'] ?? $this->getExceptionListenerService());
        }, 1 => 'removeCspHeader'], -128);
        $instance->addListener('kernel.controller_arguments', [0 => #[\Closure(name: 'controller.cache_attribute_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\CacheAttributeListener')] function () {
            return ($this->privates['controller.cache_attribute_listener'] ??= new \Symfony\Component\HttpKernel\EventListener\CacheAttributeListener());
        }, 1 => 'onKernelControllerArguments'], 10);
        $instance->addListener('kernel.response', [0 => #[\Closure(name: 'controller.cache_attribute_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\CacheAttributeListener')] function () {
            return ($this->privates['controller.cache_attribute_listener'] ??= new \Symfony\Component\HttpKernel\EventListener\CacheAttributeListener());
        }, 1 => 'onKernelResponse'], -10);
        $instance->addListener('console.error', [0 => #[\Closure(name: 'console.error_listener', class: 'Symfony\\Component\\Console\\EventListener\\ErrorListener')] function () {
            return ($this->privates['console.error_listener'] ?? $this->load('getConsole_ErrorListenerService'));
        }, 1 => 'onConsoleError'], -128);
        $instance->addListener('console.terminate', [0 => #[\Closure(name: 'console.error_listener', class: 'Symfony\\Component\\Console\\EventListener\\ErrorListener')] function () {
            return ($this->privates['console.error_listener'] ?? $this->load('getConsole_ErrorListenerService'));
        }, 1 => 'onConsoleTerminate'], -128);
        $instance->addListener('console.error', [0 => #[\Closure(name: 'console.suggest_missing_package_subscriber', class: 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\SuggestMissingPackageSubscriber')] function () {
            return ($this->privates['console.suggest_missing_package_subscriber'] ??= new \Symfony\Bundle\FrameworkBundle\EventListener\SuggestMissingPackageSubscriber());
        }, 1 => 'onConsoleError'], 0);
        $instance->addListener('kernel.request', [0 => #[\Closure(name: 'debug.debug_handlers_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\DebugHandlersListener')] function () {
            return ($this->privates['debug.debug_handlers_listener'] ?? $this->getDebug_DebugHandlersListenerService());
        }, 1 => 'configure'], 2048);
        $instance->addListener('console.command', [0 => #[\Closure(name: 'debug.debug_handlers_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\DebugHandlersListener')] function () {
            return ($this->privates['debug.debug_handlers_listener'] ?? $this->getDebug_DebugHandlersListenerService());
        }, 1 => 'configure'], 2048);
        $instance->addListener('kernel.request', [0 => #[\Closure(name: 'router_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener')] function () {
            return ($this->privates['router_listener'] ?? $this->getRouterListenerService());
        }, 1 => 'onKernelRequest'], 32);
        $instance->addListener('kernel.finish_request', [0 => #[\Closure(name: 'router_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener')] function () {
            return ($this->privates['router_listener'] ?? $this->getRouterListenerService());
        }, 1 => 'onKernelFinishRequest'], 0);
        $instance->addListener('kernel.exception', [0 => #[\Closure(name: 'router_listener', class: 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener')] function () {
            return ($this->privates['router_listener'] ?? $this->getRouterListenerService());
        }, 1 => 'onKernelException'], -64);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerMessageFailedEvent', [0 => #[\Closure(name: 'messenger.retry.send_failed_message_for_retry_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\SendFailedMessageForRetryListener')] function () {
            return ($this->privates['messenger.retry.send_failed_message_for_retry_listener'] ?? $this->load('getMessenger_Retry_SendFailedMessageForRetryListenerService'));
        }, 1 => 'onMessageFailed'], 100);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerMessageFailedEvent', [0 => #[\Closure(name: 'messenger.failure.add_error_details_stamp_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\AddErrorDetailsStampListener')] function () {
            return ($this->privates['messenger.failure.add_error_details_stamp_listener'] ??= new \Symfony\Component\Messenger\EventListener\AddErrorDetailsStampListener());
        }, 1 => 'onMessageFailed'], 200);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerRunningEvent', [0 => #[\Closure(name: 'messenger.listener.dispatch_pcntl_signal_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\DispatchPcntlSignalListener')] function () {
            return ($this->privates['messenger.listener.dispatch_pcntl_signal_listener'] ??= new \Symfony\Component\Messenger\EventListener\DispatchPcntlSignalListener());
        }, 1 => 'onWorkerRunning'], 100);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerStartedEvent', [0 => #[\Closure(name: 'messenger.listener.stop_worker_on_restart_signal_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\StopWorkerOnRestartSignalListener')] function () {
            return ($this->privates['messenger.listener.stop_worker_on_restart_signal_listener'] ?? $this->load('getMessenger_Listener_StopWorkerOnRestartSignalListenerService'));
        }, 1 => 'onWorkerStarted'], 0);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerRunningEvent', [0 => #[\Closure(name: 'messenger.listener.stop_worker_on_restart_signal_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\StopWorkerOnRestartSignalListener')] function () {
            return ($this->privates['messenger.listener.stop_worker_on_restart_signal_listener'] ?? $this->load('getMessenger_Listener_StopWorkerOnRestartSignalListenerService'));
        }, 1 => 'onWorkerRunning'], 0);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerStartedEvent', [0 => #[\Closure(name: 'messenger.listener.stop_worker_on_sigterm_signal_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\StopWorkerOnSigtermSignalListener')] function () {
            return ($this->privates['messenger.listener.stop_worker_on_sigterm_signal_listener'] ?? $this->load('getMessenger_Listener_StopWorkerOnSigtermSignalListenerService'));
        }, 1 => 'onWorkerStarted'], 100);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerMessageFailedEvent', [0 => #[\Closure(name: 'messenger.listener.stop_worker_on_stop_exception_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\StopWorkerOnCustomStopExceptionListener')] function () {
            return ($this->privates['messenger.listener.stop_worker_on_stop_exception_listener'] ??= new \Symfony\Component\Messenger\EventListener\StopWorkerOnCustomStopExceptionListener());
        }, 1 => 'onMessageFailed'], 0);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerRunningEvent', [0 => #[\Closure(name: 'messenger.listener.stop_worker_on_stop_exception_listener', class: 'Symfony\\Component\\Messenger\\EventListener\\StopWorkerOnCustomStopExceptionListener')] function () {
            return ($this->privates['messenger.listener.stop_worker_on_stop_exception_listener'] ??= new \Symfony\Component\Messenger\EventListener\StopWorkerOnCustomStopExceptionListener());
        }, 1 => 'onWorkerRunning'], 0);

        return $instance;
    }

    /**
     * Gets the public 'http_kernel' shared service.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernel
     */
    protected function getHttpKernelService()
    {
        return $this->services['http_kernel'] = new \Symfony\Component\HttpKernel\HttpKernel(($this->services['event_dispatcher'] ?? $this->getEventDispatcherService()), ($this->privates['controller_resolver'] ?? $this->getControllerResolverService()), ($this->services['request_stack'] ??= new \Symfony\Component\HttpFoundation\RequestStack()), ($this->privates['argument_resolver'] ?? $this->getArgumentResolverService()), false);
    }

    /**
     * Gets the public 'request_stack' shared service.
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStackService()
    {
        return $this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack();
    }

    /**
     * Gets the public 'router' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected function getRouterService()
    {
        $this->services['router'] = $instance = new \Symfony\Bundle\FrameworkBundle\Routing\Router((new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
            'routing.loader' => ['services', 'routing.loader', 'getRouting_LoaderService', true],
        ], [
            'routing.loader' => 'Symfony\\Component\\Config\\Loader\\LoaderInterface',
        ]))->withContext('router.default', $this), 'kernel::loadRoutes', ['cache_dir' => $this->targetDir.'', 'debug' => true, 'generator_class' => 'Symfony\\Component\\Routing\\Generator\\CompiledUrlGenerator', 'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\CompiledUrlGeneratorDumper', 'matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableCompiledUrlMatcher', 'matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\CompiledUrlMatcherDumper', 'strict_requirements' => true, 'resource_type' => 'service'], ($this->privates['router.request_context'] ?? $this->getRouter_RequestContextService()), ($this->privates['parameter_bag'] ??= new \Symfony\Component\DependencyInjection\ParameterBag\ContainerBag($this)), ($this->privates['logger'] ?? $this->getLoggerService()), 'en');

        $instance->setConfigCacheFactory(($this->privates['config_cache_factory'] ?? $this->getConfigCacheFactoryService()));

        return $instance;
    }

    /**
     * Gets the private 'argument_metadata_factory' shared service.
     *
     * @return \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory
     */
    protected function getArgumentMetadataFactoryService()
    {
        return $this->privates['argument_metadata_factory'] = new \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory();
    }

    /**
     * Gets the private 'argument_resolver' shared service.
     *
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolver
     */
    protected function getArgumentResolverService()
    {
        return $this->privates['argument_resolver'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver(($this->privates['argument_metadata_factory'] ??= new \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory()), new RewindableGenerator(function () {
            yield 0 => ($this->privates['argument_resolver.backed_enum_resolver'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\BackedEnumValueResolver());
            yield 1 => ($this->privates['argument_resolver.datetime'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\DateTimeValueResolver());
            yield 2 => ($this->privates['argument_resolver.request_attribute'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver());
            yield 3 => ($this->privates['argument_resolver.request'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver());
            yield 4 => ($this->privates['argument_resolver.session'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver());
            yield 5 => ($this->privates['argument_resolver.service'] ?? $this->load('getArgumentResolver_ServiceService'));
            yield 6 => ($this->privates['argument_resolver.default'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver());
            yield 7 => ($this->privates['argument_resolver.variadic'] ??= new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver());
        }, 8));
    }

    /**
     * Gets the private 'config_cache_factory' shared service.
     *
     * @return \Symfony\Component\Config\ResourceCheckerConfigCacheFactory
     */
    protected function getConfigCacheFactoryService()
    {
        return $this->privates['config_cache_factory'] = new \Symfony\Component\Config\ResourceCheckerConfigCacheFactory(new RewindableGenerator(function () {
            yield 0 => ($this->privates['dependency_injection.config.container_parameters_resource_checker'] ??= new \Symfony\Component\DependencyInjection\Config\ContainerParametersResourceChecker($this));
            yield 1 => ($this->privates['config.resource.self_checking_resource_checker'] ??= new \Symfony\Component\Config\Resource\SelfCheckingResourceChecker());
        }, 2));
    }

    /**
     * Gets the private 'controller.cache_attribute_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\CacheAttributeListener
     */
    protected function getController_CacheAttributeListenerService()
    {
        return $this->privates['controller.cache_attribute_listener'] = new \Symfony\Component\HttpKernel\EventListener\CacheAttributeListener();
    }

    /**
     * Gets the private 'controller_resolver' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver
     */
    protected function getControllerResolverService()
    {
        return $this->privates['controller_resolver'] = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver($this, ($this->privates['logger'] ?? $this->getLoggerService()));
    }

    /**
     * Gets the private 'debug.debug_handlers_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\DebugHandlersListener
     */
    protected function getDebug_DebugHandlersListenerService()
    {
        return $this->privates['debug.debug_handlers_listener'] = new \Symfony\Component\HttpKernel\EventListener\DebugHandlersListener(NULL, NULL, NULL, -1, true, true, NULL);
    }

    /**
     * Gets the private 'disallow_search_engine_index_response_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\DisallowRobotsIndexingListener
     */
    protected function getDisallowSearchEngineIndexResponseListenerService()
    {
        return $this->privates['disallow_search_engine_index_response_listener'] = new \Symfony\Component\HttpKernel\EventListener\DisallowRobotsIndexingListener();
    }

    /**
     * Gets the private 'exception_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\ErrorListener
     */
    protected function getExceptionListenerService()
    {
        return $this->privates['exception_listener'] = new \Symfony\Component\HttpKernel\EventListener\ErrorListener('error_controller', ($this->privates['logger'] ?? $this->getLoggerService()), true, []);
    }

    /**
     * Gets the private 'locale_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\LocaleListener
     */
    protected function getLocaleListenerService()
    {
        return $this->privates['locale_listener'] = new \Symfony\Component\HttpKernel\EventListener\LocaleListener(($this->services['request_stack'] ??= new \Symfony\Component\HttpFoundation\RequestStack()), 'en', ($this->services['router'] ?? $this->getRouterService()), false, []);
    }

    /**
     * Gets the private 'logger' shared service.
     *
     * @return \Symfony\Component\HttpKernel\Log\Logger
     */
    protected function getLoggerService()
    {
        return $this->privates['logger'] = new \Symfony\Component\HttpKernel\Log\Logger(NULL, NULL, NULL, ($this->services['request_stack'] ??= new \Symfony\Component\HttpFoundation\RequestStack()));
    }

    /**
     * Gets the private 'parameter_bag' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ParameterBag\ContainerBag
     */
    protected function getParameterBagService()
    {
        return $this->privates['parameter_bag'] = new \Symfony\Component\DependencyInjection\ParameterBag\ContainerBag($this);
    }

    /**
     * Gets the private 'response_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\ResponseListener
     */
    protected function getResponseListenerService()
    {
        return $this->privates['response_listener'] = new \Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8', false);
    }

    /**
     * Gets the private 'router.request_context' shared service.
     *
     * @return \Symfony\Component\Routing\RequestContext
     */
    protected function getRouter_RequestContextService()
    {
        return $this->privates['router.request_context'] = \Symfony\Component\Routing\RequestContext::fromUri('', 'localhost', 'http', 80, 443);
    }

    /**
     * Gets the private 'router_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\RouterListener
     */
    protected function getRouterListenerService()
    {
        return $this->privates['router_listener'] = new \Symfony\Component\HttpKernel\EventListener\RouterListener(($this->services['router'] ?? $this->getRouterService()), ($this->services['request_stack'] ??= new \Symfony\Component\HttpFoundation\RequestStack()), ($this->privates['router.request_context'] ?? $this->getRouter_RequestContextService()), ($this->privates['logger'] ?? $this->getLoggerService()), \dirname(__DIR__, 4), true);
    }

    /**
     * Gets the private 'validate_request_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\ValidateRequestListener
     */
    protected function getValidateRequestListenerService()
    {
        return $this->privates['validate_request_listener'] = new \Symfony\Component\HttpKernel\EventListener\ValidateRequestListener();
    }

    public function getParameter(string $name): array|bool|string|int|float|\UnitEnum|null
    {
        if (isset($this->buildParameters[$name])) {
            return $this->buildParameters[$name];
        }

        if (!(isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || \array_key_exists($name, $this->parameters))) {
            throw new ParameterNotFoundException($name);
        }
        if (isset($this->loadedDynamicParameters[$name])) {
            return $this->loadedDynamicParameters[$name] ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
        }

        return $this->parameters[$name];
    }

    public function hasParameter(string $name): bool
    {
        if (isset($this->buildParameters[$name])) {
            return true;
        }

        return isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || \array_key_exists($name, $this->parameters);
    }

    public function setParameter(string $name, $value): void
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    public function getParameterBag(): ParameterBagInterface
    {
        if (null === $this->parameterBag) {
            $parameters = $this->parameters;
            foreach ($this->loadedDynamicParameters as $name => $loaded) {
                $parameters[$name] = $loaded ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
            }
            foreach ($this->buildParameters as $name => $value) {
                $parameters[$name] = $value;
            }
            $this->parameterBag = new FrozenParameterBag($parameters);
        }

        return $this->parameterBag;
    }

    private $loadedDynamicParameters = [
        'kernel.runtime_environment' => false,
        'kernel.build_dir' => false,
        'kernel.cache_dir' => false,
        'debug.file_link_format' => false,
        'debug.container.dump' => false,
        'router.cache_dir' => false,
        'validator.mapping.cache.file' => false,
    ];
    private $dynamicParameters = [];

    private function getDynamicParameter(string $name)
    {
        $value = match ($name) {
            'kernel.runtime_environment' => $this->getEnv('default:kernel.environment:APP_RUNTIME_ENV'),
            'kernel.build_dir' => $this->targetDir.'',
            'kernel.cache_dir' => $this->targetDir.'',
            'debug.file_link_format' => $this->getEnv('default::SYMFONY_IDE'),
            'debug.container.dump' => ($this->targetDir.''.'/Tramity_Apps_Backend_BackendKernelTestDebugContainer.xml'),
            'router.cache_dir' => $this->targetDir.'',
            'validator.mapping.cache.file' => ($this->targetDir.''.'/validation.php'),
            default => throw new ParameterNotFoundException($name),
        };
        $this->loadedDynamicParameters[$name] = true;

        return $this->dynamicParameters[$name] = $value;
    }

    protected function getDefaultParameters(): array
    {
        return [
            'kernel.project_dir' => \dirname(__DIR__, 4),
            'kernel.environment' => 'test',
            'kernel.debug' => true,
            'kernel.logs_dir' => (\dirname(__DIR__, 3).'/log'),
            'kernel.bundles' => [
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'FriendsOfBehatSymfonyExtensionBundle' => 'FriendsOfBehat\\SymfonyExtension\\Bundle\\FriendsOfBehatSymfonyExtensionBundle',
            ],
            'kernel.bundles_metadata' => [
                'FrameworkBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/vendor/symfony/framework-bundle'),
                    'namespace' => 'Symfony\\Bundle\\FrameworkBundle',
                ],
                'FriendsOfBehatSymfonyExtensionBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/vendor/friends-of-behat/symfony-extension/src/Bundle'),
                    'namespace' => 'FriendsOfBehat\\SymfonyExtension\\Bundle',
                ],
            ],
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'Tramity_Apps_Backend_BackendKernelTestDebugContainer',
            'event_dispatcher.event_aliases' => [
                'Symfony\\Component\\Console\\Event\\ConsoleCommandEvent' => 'console.command',
                'Symfony\\Component\\Console\\Event\\ConsoleErrorEvent' => 'console.error',
                'Symfony\\Component\\Console\\Event\\ConsoleSignalEvent' => 'console.signal',
                'Symfony\\Component\\Console\\Event\\ConsoleTerminateEvent' => 'console.terminate',
                'Symfony\\Component\\HttpKernel\\Event\\ControllerArgumentsEvent' => 'kernel.controller_arguments',
                'Symfony\\Component\\HttpKernel\\Event\\ControllerEvent' => 'kernel.controller',
                'Symfony\\Component\\HttpKernel\\Event\\ResponseEvent' => 'kernel.response',
                'Symfony\\Component\\HttpKernel\\Event\\FinishRequestEvent' => 'kernel.finish_request',
                'Symfony\\Component\\HttpKernel\\Event\\RequestEvent' => 'kernel.request',
                'Symfony\\Component\\HttpKernel\\Event\\ViewEvent' => 'kernel.view',
                'Symfony\\Component\\HttpKernel\\Event\\ExceptionEvent' => 'kernel.exception',
                'Symfony\\Component\\HttpKernel\\Event\\TerminateEvent' => 'kernel.terminate',
            ],
            'fragment.renderer.hinclude.global_template' => NULL,
            'fragment.path' => '/_fragment',
            'kernel.http_method_override' => true,
            'kernel.trust_x_sendfile_type_header' => false,
            'kernel.trusted_hosts' => [

            ],
            'kernel.default_locale' => 'en',
            'kernel.enabled_locales' => [

            ],
            'kernel.error_controller' => 'error_controller',
            'test.client.parameters' => [

            ],
            'debug.error_handler.throw_at' => -1,
            'router.request_context.host' => 'localhost',
            'router.request_context.scheme' => 'http',
            'router.request_context.base_url' => '',
            'router.resource' => 'kernel::loadRoutes',
            'request_listener.http_port' => 80,
            'request_listener.https_port' => 443,
            'validator.translation_domain' => 'validators',
            'data_collector.templates' => [

            ],
            'console.command.ids' => [

            ],
        ];
    }
}
