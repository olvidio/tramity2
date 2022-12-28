<?php

namespace ContainerObQFv85;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getTest_PrivateServicesLocatorService extends Tramity_Apps_Backend_BackendKernelTestDebugContainer
{
    /**
     * Gets the public 'test.private_services_locator' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->services['test.private_services_locator'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'Tramity\\shared\\infrastructure\\PhpRandomNumberGenerator' => ['privates', 'Tramity\\shared\\infrastructure\\PhpRandomNumberGenerator', 'getPhpRandomNumberGeneratorService', true],
            'controller_resolver' => ['privates', 'controller_resolver', 'getControllerResolverService', false],
            'argument_metadata_factory' => ['privates', 'argument_metadata_factory', 'getArgumentMetadataFactoryService', false],
            'argument_resolver' => ['privates', 'argument_resolver', 'getArgumentResolverService', false],
            'argument_resolver.backed_enum_resolver' => ['privates', 'argument_resolver.backed_enum_resolver', 'getArgumentResolver_BackedEnumResolverService', true],
            'argument_resolver.datetime' => ['privates', 'argument_resolver.datetime', 'getArgumentResolver_DatetimeService', true],
            'argument_resolver.request_attribute' => ['privates', 'argument_resolver.request_attribute', 'getArgumentResolver_RequestAttributeService', true],
            'argument_resolver.request' => ['privates', 'argument_resolver.request', 'getArgumentResolver_RequestService', true],
            'argument_resolver.session' => ['privates', 'argument_resolver.session', 'getArgumentResolver_SessionService', true],
            'argument_resolver.service' => ['privates', 'argument_resolver.service', 'getArgumentResolver_ServiceService', true],
            'argument_resolver.default' => ['privates', 'argument_resolver.default', 'getArgumentResolver_DefaultService', true],
            'argument_resolver.variadic' => ['privates', 'argument_resolver.variadic', 'getArgumentResolver_VariadicService', true],
            'response_listener' => ['privates', 'response_listener', 'getResponseListenerService', false],
            'locale_listener' => ['privates', 'locale_listener', 'getLocaleListenerService', false],
            'validate_request_listener' => ['privates', 'validate_request_listener', 'getValidateRequestListenerService', false],
            'disallow_search_engine_index_response_listener' => ['privates', 'disallow_search_engine_index_response_listener', 'getDisallowSearchEngineIndexResponseListenerService', false],
            'exception_listener' => ['privates', 'exception_listener', 'getExceptionListenerService', false],
            'controller.cache_attribute_listener' => ['privates', 'controller.cache_attribute_listener', 'getController_CacheAttributeListenerService', false],
            'parameter_bag' => ['privates', 'parameter_bag', 'getParameterBagService', false],
            'cache_clearer' => ['privates', 'cache_clearer', 'getCacheClearerService', true],
            'filesystem' => ['privates', 'filesystem', 'getFilesystemService', true],
            'file_locator' => ['privates', 'file_locator', 'getFileLocatorService', true],
            'config_cache_factory' => ['privates', 'config_cache_factory', 'getConfigCacheFactoryService', false],
            'dependency_injection.config.container_parameters_resource_checker' => ['privates', 'dependency_injection.config.container_parameters_resource_checker', 'getDependencyInjection_Config_ContainerParametersResourceCheckerService', true],
            'config.resource.self_checking_resource_checker' => ['privates', 'config.resource.self_checking_resource_checker', 'getConfig_Resource_SelfCheckingResourceCheckerService', true],
            'container.env_var_processor' => ['privates', 'container.env_var_processor', 'getContainer_EnvVarProcessorService', true],
            'container.getenv' => ['privates', 'container.getenv', 'getContainer_GetenvService', true],
            'config_builder.warmer' => ['privates', 'config_builder.warmer', 'getConfigBuilder_WarmerService', true],
            'error_handler.error_renderer.html' => ['privates', 'error_handler.error_renderer.html', 'getErrorHandler_ErrorRenderer_HtmlService', true],
            'console.error_listener' => ['privates', 'console.error_listener', 'getConsole_ErrorListenerService', true],
            'console.suggest_missing_package_subscriber' => ['privates', 'console.suggest_missing_package_subscriber', 'getConsole_SuggestMissingPackageSubscriberService', true],
            'console.command.about' => ['privates', 'console.command.about', 'getConsole_Command_AboutService', true],
            'console.command.assets_install' => ['privates', 'console.command.assets_install', 'getConsole_Command_AssetsInstallService', true],
            'console.command.cache_clear' => ['privates', 'console.command.cache_clear', 'getConsole_Command_CacheClearService', true],
            'console.command.cache_pool_clear' => ['privates', 'console.command.cache_pool_clear', 'getConsole_Command_CachePoolClearService', true],
            'console.command.cache_pool_prune' => ['privates', 'console.command.cache_pool_prune', 'getConsole_Command_CachePoolPruneService', true],
            'console.command.cache_pool_invalidate_tags' => ['privates', 'console.command.cache_pool_invalidate_tags', 'getConsole_Command_CachePoolInvalidateTagsService', true],
            'console.command.cache_pool_delete' => ['privates', 'console.command.cache_pool_delete', 'getConsole_Command_CachePoolDeleteService', true],
            'console.command.cache_pool_list' => ['privates', 'console.command.cache_pool_list', 'getConsole_Command_CachePoolListService', true],
            'console.command.cache_warmup' => ['privates', 'console.command.cache_warmup', 'getConsole_Command_CacheWarmupService', true],
            'console.command.config_debug' => ['privates', 'console.command.config_debug', 'getConsole_Command_ConfigDebugService', true],
            'console.command.config_dump_reference' => ['privates', 'console.command.config_dump_reference', 'getConsole_Command_ConfigDumpReferenceService', true],
            'console.command.container_debug' => ['privates', 'console.command.container_debug', 'getConsole_Command_ContainerDebugService', true],
            'console.command.container_lint' => ['privates', 'console.command.container_lint', 'getConsole_Command_ContainerLintService', true],
            'console.command.debug_autowiring' => ['privates', 'console.command.debug_autowiring', 'getConsole_Command_DebugAutowiringService', true],
            'console.command.dotenv_debug' => ['privates', 'console.command.dotenv_debug', 'getConsole_Command_DotenvDebugService', true],
            'console.command.event_dispatcher_debug' => ['privates', 'console.command.event_dispatcher_debug', 'getConsole_Command_EventDispatcherDebugService', true],
            'console.command.messenger_consume_messages' => ['privates', 'console.command.messenger_consume_messages', 'getConsole_Command_MessengerConsumeMessagesService', true],
            'console.command.messenger_setup_transports' => ['privates', 'console.command.messenger_setup_transports', 'getConsole_Command_MessengerSetupTransportsService', true],
            'console.command.messenger_debug' => ['privates', 'console.command.messenger_debug', 'getConsole_Command_MessengerDebugService', true],
            'console.command.messenger_stop_workers' => ['privates', 'console.command.messenger_stop_workers', 'getConsole_Command_MessengerStopWorkersService', true],
            'console.command.messenger_stats' => ['privates', 'console.command.messenger_stats', 'getConsole_Command_MessengerStatsService', true],
            'console.command.router_debug' => ['privates', 'console.command.router_debug', 'getConsole_Command_RouterDebugService', true],
            'console.command.router_match' => ['privates', 'console.command.router_match', 'getConsole_Command_RouterMatchService', true],
            'console.command.validator_debug' => ['privates', 'console.command.validator_debug', 'getConsole_Command_ValidatorDebugService', true],
            'console.command.xliff_lint' => ['privates', 'console.command.xliff_lint', 'getConsole_Command_XliffLintService', true],
            'console.command.yaml_lint' => ['privates', 'console.command.yaml_lint', 'getConsole_Command_YamlLintService', true],
            'console.command.secrets_set' => ['privates', 'console.command.secrets_set', 'getConsole_Command_SecretsSetService', true],
            'console.command.secrets_remove' => ['privates', 'console.command.secrets_remove', 'getConsole_Command_SecretsRemoveService', true],
            'console.command.secrets_generate_key' => ['privates', 'console.command.secrets_generate_key', 'getConsole_Command_SecretsGenerateKeyService', true],
            'console.command.secrets_list' => ['privates', 'console.command.secrets_list', 'getConsole_Command_SecretsListService', true],
            'console.command.secrets_decrypt_to_local' => ['privates', 'console.command.secrets_decrypt_to_local', 'getConsole_Command_SecretsDecryptToLocalService', true],
            'console.command.secrets_encrypt_from_local' => ['privates', 'console.command.secrets_encrypt_from_local', 'getConsole_Command_SecretsEncryptFromLocalService', true],
            'cache.app.taggable' => ['privates', 'cache.app.taggable', 'getCache_App_TaggableService', true],
            'cache.messenger.restart_workers_signal' => ['privates', 'cache.messenger.restart_workers_signal', 'getCache_Messenger_RestartWorkersSignalService', true],
            'cache.default_marshaller' => ['privates', 'cache.default_marshaller', 'getCache_DefaultMarshallerService', true],
            'cache.default_clearer' => ['services', 'cache.app_clearer', 'getCache_AppClearerService', true],
            'translator' => ['privates', 'translator', 'getTranslatorService', true],
            'test.client.history' => [false, 'test.client.history', 'getTest_Client_HistoryService', true],
            'test.client.cookiejar' => [false, 'test.client.cookiejar', 'getTest_Client_CookiejarService', true],
            'debug.debug_handlers_listener' => ['privates', 'debug.debug_handlers_listener', 'getDebug_DebugHandlersListenerService', false],
            'debug.file_link_formatter' => ['privates', 'debug.file_link_formatter', 'getDebug_FileLinkFormatterService', true],
            'routing.resolver' => ['privates', 'routing.resolver', 'getRouting_ResolverService', true],
            'routing.loader.xml' => ['privates', 'routing.loader.xml', 'getRouting_Loader_XmlService', true],
            'routing.loader.yml' => ['privates', 'routing.loader.yml', 'getRouting_Loader_YmlService', true],
            'routing.loader.php' => ['privates', 'routing.loader.php', 'getRouting_Loader_PhpService', true],
            'routing.loader.glob' => ['privates', 'routing.loader.glob', 'getRouting_Loader_GlobService', true],
            'routing.loader.directory' => ['privates', 'routing.loader.directory', 'getRouting_Loader_DirectoryService', true],
            'routing.loader.container' => ['privates', 'routing.loader.container', 'getRouting_Loader_ContainerService', true],
            'routing.loader.annotation' => ['privates', 'routing.loader.annotation', 'getRouting_Loader_AnnotationService', true],
            'routing.loader.annotation.directory' => ['privates', 'routing.loader.annotation.directory', 'getRouting_Loader_Annotation_DirectoryService', true],
            'routing.loader.annotation.file' => ['privates', 'routing.loader.annotation.file', 'getRouting_Loader_Annotation_FileService', true],
            'routing.loader.psr4' => ['privates', 'routing.loader.psr4', 'getRouting_Loader_Psr4Service', true],
            'router.default' => ['services', 'router', 'getRouterService', false],
            'router.request_context' => ['privates', 'router.request_context', 'getRouter_RequestContextService', false],
            'router.cache_warmer' => ['privates', 'router.cache_warmer', 'getRouter_CacheWarmerService', true],
            'router_listener' => ['privates', 'router_listener', 'getRouterListenerService', false],
            'secrets.vault' => ['privates', 'secrets.vault', 'getSecrets_VaultService', true],
            'secrets.decryption_key' => ['privates', 'secrets.decryption_key', 'getSecrets_DecryptionKeyService', true],
            'secrets.local_vault' => ['privates', 'secrets.local_vault', 'getSecrets_LocalVaultService', true],
            'validator' => ['privates', 'validator', 'getValidatorService', true],
            'validator.builder' => ['privates', 'validator.builder', 'getValidator_BuilderService', true],
            'validator.mapping.cache_warmer' => ['privates', 'validator.mapping.cache_warmer', 'getValidator_Mapping_CacheWarmerService', true],
            'validator.validator_factory' => ['privates', 'validator.validator_factory', 'getValidator_ValidatorFactoryService', true],
            'validator.expression' => ['privates', 'validator.expression', 'getValidator_ExpressionService', true],
            'validator.email' => ['privates', 'validator.email', 'getValidator_EmailService', true],
            'validator.not_compromised_password' => ['privates', 'validator.not_compromised_password', 'getValidator_NotCompromisedPasswordService', true],
            'validator.when' => ['privates', 'validator.when', 'getValidator_WhenService', true],
            'messenger.senders_locator' => ['privates', 'messenger.senders_locator', 'getMessenger_SendersLocatorService', true],
            'messenger.middleware.dispatch_after_current_bus' => ['privates', 'messenger.middleware.dispatch_after_current_bus', 'getMessenger_Middleware_DispatchAfterCurrentBusService', true],
            'messenger.middleware.reject_redelivered_message_middleware' => ['privates', 'messenger.middleware.reject_redelivered_message_middleware', 'getMessenger_Middleware_RejectRedeliveredMessageMiddlewareService', true],
            'messenger.middleware.failed_message_processing_middleware' => ['privates', 'messenger.middleware.failed_message_processing_middleware', 'getMessenger_Middleware_FailedMessageProcessingMiddlewareService', true],
            'messenger.receiver_locator' => ['privates', 'messenger.receiver_locator', 'getMessenger_ReceiverLocatorService', true],
            'messenger.retry_strategy_locator' => ['privates', 'messenger.retry_strategy_locator', 'getMessenger_RetryStrategyLocatorService', true],
            'messenger.retry.send_failed_message_for_retry_listener' => ['privates', 'messenger.retry.send_failed_message_for_retry_listener', 'getMessenger_Retry_SendFailedMessageForRetryListenerService', true],
            'messenger.failure.add_error_details_stamp_listener' => ['privates', 'messenger.failure.add_error_details_stamp_listener', 'getMessenger_Failure_AddErrorDetailsStampListenerService', true],
            'messenger.listener.dispatch_pcntl_signal_listener' => ['privates', 'messenger.listener.dispatch_pcntl_signal_listener', 'getMessenger_Listener_DispatchPcntlSignalListenerService', true],
            'messenger.listener.stop_worker_on_restart_signal_listener' => ['privates', 'messenger.listener.stop_worker_on_restart_signal_listener', 'getMessenger_Listener_StopWorkerOnRestartSignalListenerService', true],
            'messenger.listener.stop_worker_on_sigterm_signal_listener' => ['privates', 'messenger.listener.stop_worker_on_sigterm_signal_listener', 'getMessenger_Listener_StopWorkerOnSigtermSignalListenerService', true],
            'messenger.listener.stop_worker_on_stop_exception_listener' => ['privates', 'messenger.listener.stop_worker_on_stop_exception_listener', 'getMessenger_Listener_StopWorkerOnStopExceptionListenerService', true],
            'messenger.listener.reset_services' => ['privates', 'messenger.listener.reset_services', 'getMessenger_Listener_ResetServicesService', true],
            'messenger.routable_message_bus' => ['privates', 'messenger.routable_message_bus', 'getMessenger_RoutableMessageBusService', true],
            'messenger.bus.default' => ['services', 'messenger.default_bus', 'getMessenger_DefaultBusService', true],
            'messenger.bus.default.middleware.add_bus_name_stamp_middleware' => ['privates', 'messenger.bus.default.middleware.add_bus_name_stamp_middleware', 'getMessenger_Bus_Default_Middleware_AddBusNameStampMiddlewareService', true],
            'messenger.bus.default.middleware.send_message' => ['privates', 'messenger.bus.default.middleware.send_message', 'getMessenger_Bus_Default_Middleware_SendMessageService', true],
            'messenger.bus.default.middleware.handle_message' => ['privates', 'messenger.bus.default.middleware.handle_message', 'getMessenger_Bus_Default_Middleware_HandleMessageService', true],
            'messenger.bus.default.messenger.handlers_locator' => ['privates', 'messenger.bus.default.messenger.handlers_locator', 'getMessenger_Bus_Default_Messenger_HandlersLocatorService', true],
            'logger' => ['privates', 'logger', 'getLoggerService', false],
            'Tramity\\shared\\domain\\RandomNumberGenerator' => ['privates', 'Tramity\\shared\\infrastructure\\PhpRandomNumberGenerator', 'getPhpRandomNumberGeneratorService', true],
            'Symfony\\Component\\DependencyInjection\\ParameterBag\\ContainerBagInterface' => ['privates', 'parameter_bag', 'getParameterBagService', false],
            'Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBagInterface' => ['privates', 'parameter_bag', 'getParameterBagService', false],
            'Symfony\\Component\\EventDispatcher\\EventDispatcherInterface' => ['services', 'event_dispatcher', 'getEventDispatcherService', false],
            'Symfony\\Contracts\\EventDispatcher\\EventDispatcherInterface' => ['services', 'event_dispatcher', 'getEventDispatcherService', false],
            'Psr\\EventDispatcher\\EventDispatcherInterface' => ['services', 'event_dispatcher', 'getEventDispatcherService', false],
            'Symfony\\Component\\HttpKernel\\HttpKernelInterface' => ['services', 'http_kernel', 'getHttpKernelService', false],
            'Symfony\\Component\\HttpFoundation\\RequestStack' => ['services', 'request_stack', 'getRequestStackService', false],
            'Symfony\\Component\\HttpKernel\\KernelInterface' => ['services', 'kernel', 'getKernelService', true],
            'Symfony\\Component\\Filesystem\\Filesystem' => ['privates', 'filesystem', 'getFilesystemService', true],
            'Symfony\\Component\\HttpKernel\\Config\\FileLocator' => ['privates', 'file_locator', 'getFileLocatorService', true],
            'error_renderer.html' => ['privates', 'error_handler.error_renderer.html', 'getErrorHandler_ErrorRenderer_HtmlService', true],
            'error_renderer' => ['privates', 'error_handler.error_renderer.html', 'getErrorHandler_ErrorRenderer_HtmlService', true],
            'Psr\\Container\\ContainerInterface $parameterBag' => ['privates', 'parameter_bag', 'getParameterBagService', false],
            'Psr\\Cache\\CacheItemPoolInterface' => ['services', 'cache.app', 'getCache_AppService', true],
            'Symfony\\Contracts\\Cache\\CacheInterface' => ['services', 'cache.app', 'getCache_AppService', true],
            'Symfony\\Contracts\\Cache\\TagAwareCacheInterface' => ['privates', 'cache.app.taggable', 'getCache_App_TaggableService', true],
            'Symfony\\Contracts\\Translation\\TranslatorInterface' => ['privates', 'translator', 'getTranslatorService', true],
            'Symfony\\Component\\HttpKernel\\Debug\\FileLinkFormatter' => ['privates', 'debug.file_link_formatter', 'getDebug_FileLinkFormatterService', true],
            'Symfony\\Component\\Routing\\RouterInterface' => ['services', 'router', 'getRouterService', false],
            'Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface' => ['services', 'router', 'getRouterService', false],
            'Symfony\\Component\\Routing\\Matcher\\UrlMatcherInterface' => ['services', 'router', 'getRouterService', false],
            'Symfony\\Component\\Routing\\RequestContextAwareInterface' => ['services', 'router', 'getRouterService', false],
            'Symfony\\Component\\Routing\\RequestContext' => ['privates', 'router.request_context', 'getRouter_RequestContextService', false],
            'Symfony\\Component\\Validator\\Validator\\ValidatorInterface' => ['privates', 'validator', 'getValidatorService', true],
            'validator.mapping.class_metadata_factory' => ['privates', 'validator', 'getValidatorService', true],
            'Symfony\\Component\\Messenger\\MessageBusInterface' => ['services', 'messenger.default_bus', 'getMessenger_DefaultBusService', true],
            'Behat\\Mink\\Mink' => ['services', 'behat.mink', 'getBehat_MinkService', true],
            'Behat\\Mink\\Session' => ['services', 'behat.mink.default_session', 'getBehat_Mink_DefaultSessionService', true],
            'FriendsOfBehat\\SymfonyExtension\\Mink\\MinkParameters' => ['services', 'behat.mink.parameters', 'getBehat_Mink_ParametersService', true],
            'Symfony\\Component\\DependencyInjection\\ContainerInterface $driverContainer' => ['services', 'behat.driver.service_container', 'getBehat_Driver_ServiceContainerService', true],
            'argument_resolver.controller_locator' => ['privates', '.service_locator.TGeGvuk', 'get_ServiceLocator_TGeGvukService', true],
            'Psr\\Log\\LoggerInterface' => ['privates', 'logger', 'getLoggerService', false],
            'Symfony\\Bundle\\FrameworkBundle\\KernelBrowser' => [false, 'test.client', 'getTest_ClientService', true],
            'Symfony\\Component\\HttpKernel\\HttpKernelBrowser' => [false, 'test.client', 'getTest_ClientService', true],
        ], [
            'Tramity\\shared\\infrastructure\\PhpRandomNumberGenerator' => '?',
            'controller_resolver' => '?',
            'argument_metadata_factory' => '?',
            'argument_resolver' => '?',
            'argument_resolver.backed_enum_resolver' => '?',
            'argument_resolver.datetime' => '?',
            'argument_resolver.request_attribute' => '?',
            'argument_resolver.request' => '?',
            'argument_resolver.session' => '?',
            'argument_resolver.service' => '?',
            'argument_resolver.default' => '?',
            'argument_resolver.variadic' => '?',
            'response_listener' => '?',
            'locale_listener' => '?',
            'validate_request_listener' => '?',
            'disallow_search_engine_index_response_listener' => '?',
            'exception_listener' => '?',
            'controller.cache_attribute_listener' => '?',
            'parameter_bag' => '?',
            'cache_clearer' => '?',
            'filesystem' => '?',
            'file_locator' => '?',
            'config_cache_factory' => '?',
            'dependency_injection.config.container_parameters_resource_checker' => '?',
            'config.resource.self_checking_resource_checker' => '?',
            'container.env_var_processor' => '?',
            'container.getenv' => '?',
            'config_builder.warmer' => '?',
            'error_handler.error_renderer.html' => '?',
            'console.error_listener' => '?',
            'console.suggest_missing_package_subscriber' => '?',
            'console.command.about' => '?',
            'console.command.assets_install' => '?',
            'console.command.cache_clear' => '?',
            'console.command.cache_pool_clear' => '?',
            'console.command.cache_pool_prune' => '?',
            'console.command.cache_pool_invalidate_tags' => '?',
            'console.command.cache_pool_delete' => '?',
            'console.command.cache_pool_list' => '?',
            'console.command.cache_warmup' => '?',
            'console.command.config_debug' => '?',
            'console.command.config_dump_reference' => '?',
            'console.command.container_debug' => '?',
            'console.command.container_lint' => '?',
            'console.command.debug_autowiring' => '?',
            'console.command.dotenv_debug' => '?',
            'console.command.event_dispatcher_debug' => '?',
            'console.command.messenger_consume_messages' => '?',
            'console.command.messenger_setup_transports' => '?',
            'console.command.messenger_debug' => '?',
            'console.command.messenger_stop_workers' => '?',
            'console.command.messenger_stats' => '?',
            'console.command.router_debug' => '?',
            'console.command.router_match' => '?',
            'console.command.validator_debug' => '?',
            'console.command.xliff_lint' => '?',
            'console.command.yaml_lint' => '?',
            'console.command.secrets_set' => '?',
            'console.command.secrets_remove' => '?',
            'console.command.secrets_generate_key' => '?',
            'console.command.secrets_list' => '?',
            'console.command.secrets_decrypt_to_local' => '?',
            'console.command.secrets_encrypt_from_local' => '?',
            'cache.app.taggable' => '?',
            'cache.messenger.restart_workers_signal' => '?',
            'cache.default_marshaller' => '?',
            'cache.default_clearer' => '?',
            'translator' => '?',
            'test.client.history' => '?',
            'test.client.cookiejar' => '?',
            'debug.debug_handlers_listener' => '?',
            'debug.file_link_formatter' => '?',
            'routing.resolver' => '?',
            'routing.loader.xml' => '?',
            'routing.loader.yml' => '?',
            'routing.loader.php' => '?',
            'routing.loader.glob' => '?',
            'routing.loader.directory' => '?',
            'routing.loader.container' => '?',
            'routing.loader.annotation' => '?',
            'routing.loader.annotation.directory' => '?',
            'routing.loader.annotation.file' => '?',
            'routing.loader.psr4' => '?',
            'router.default' => '?',
            'router.request_context' => '?',
            'router.cache_warmer' => '?',
            'router_listener' => '?',
            'secrets.vault' => '?',
            'secrets.decryption_key' => '?',
            'secrets.local_vault' => '?',
            'validator' => '?',
            'validator.builder' => '?',
            'validator.mapping.cache_warmer' => '?',
            'validator.validator_factory' => '?',
            'validator.expression' => '?',
            'validator.email' => '?',
            'validator.not_compromised_password' => '?',
            'validator.when' => '?',
            'messenger.senders_locator' => '?',
            'messenger.middleware.dispatch_after_current_bus' => '?',
            'messenger.middleware.reject_redelivered_message_middleware' => '?',
            'messenger.middleware.failed_message_processing_middleware' => '?',
            'messenger.receiver_locator' => '?',
            'messenger.retry_strategy_locator' => '?',
            'messenger.retry.send_failed_message_for_retry_listener' => '?',
            'messenger.failure.add_error_details_stamp_listener' => '?',
            'messenger.listener.dispatch_pcntl_signal_listener' => '?',
            'messenger.listener.stop_worker_on_restart_signal_listener' => '?',
            'messenger.listener.stop_worker_on_sigterm_signal_listener' => '?',
            'messenger.listener.stop_worker_on_stop_exception_listener' => '?',
            'messenger.listener.reset_services' => '?',
            'messenger.routable_message_bus' => '?',
            'messenger.bus.default' => '?',
            'messenger.bus.default.middleware.add_bus_name_stamp_middleware' => '?',
            'messenger.bus.default.middleware.send_message' => '?',
            'messenger.bus.default.middleware.handle_message' => '?',
            'messenger.bus.default.messenger.handlers_locator' => '?',
            'logger' => '?',
            'Tramity\\shared\\domain\\RandomNumberGenerator' => '?',
            'Symfony\\Component\\DependencyInjection\\ParameterBag\\ContainerBagInterface' => '?',
            'Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBagInterface' => '?',
            'Symfony\\Component\\EventDispatcher\\EventDispatcherInterface' => '?',
            'Symfony\\Contracts\\EventDispatcher\\EventDispatcherInterface' => '?',
            'Psr\\EventDispatcher\\EventDispatcherInterface' => '?',
            'Symfony\\Component\\HttpKernel\\HttpKernelInterface' => '?',
            'Symfony\\Component\\HttpFoundation\\RequestStack' => '?',
            'Symfony\\Component\\HttpKernel\\KernelInterface' => '?',
            'Symfony\\Component\\Filesystem\\Filesystem' => '?',
            'Symfony\\Component\\HttpKernel\\Config\\FileLocator' => '?',
            'error_renderer.html' => '?',
            'error_renderer' => '?',
            'Psr\\Container\\ContainerInterface $parameterBag' => '?',
            'Psr\\Cache\\CacheItemPoolInterface' => '?',
            'Symfony\\Contracts\\Cache\\CacheInterface' => '?',
            'Symfony\\Contracts\\Cache\\TagAwareCacheInterface' => '?',
            'Symfony\\Contracts\\Translation\\TranslatorInterface' => '?',
            'Symfony\\Component\\HttpKernel\\Debug\\FileLinkFormatter' => '?',
            'Symfony\\Component\\Routing\\RouterInterface' => '?',
            'Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface' => '?',
            'Symfony\\Component\\Routing\\Matcher\\UrlMatcherInterface' => '?',
            'Symfony\\Component\\Routing\\RequestContextAwareInterface' => '?',
            'Symfony\\Component\\Routing\\RequestContext' => '?',
            'Symfony\\Component\\Validator\\Validator\\ValidatorInterface' => '?',
            'validator.mapping.class_metadata_factory' => '?',
            'Symfony\\Component\\Messenger\\MessageBusInterface' => '?',
            'Behat\\Mink\\Mink' => '?',
            'Behat\\Mink\\Session' => '?',
            'FriendsOfBehat\\SymfonyExtension\\Mink\\MinkParameters' => '?',
            'Symfony\\Component\\DependencyInjection\\ContainerInterface $driverContainer' => '?',
            'argument_resolver.controller_locator' => '?',
            'Psr\\Log\\LoggerInterface' => '?',
            'Symfony\\Bundle\\FrameworkBundle\\KernelBrowser' => '?',
            'Symfony\\Component\\HttpKernel\\HttpKernelBrowser' => '?',
        ]);
    }
}
