<?php

namespace ContainerObQFv85;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getMessenger_Retry_SendFailedMessageForRetryListenerService extends Tramity_Apps_Backend_BackendKernelTestDebugContainer
{
    /**
     * Gets the private 'messenger.retry.send_failed_message_for_retry_listener' shared service.
     *
     * @return \Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/EventListener/SendFailedMessageForRetryListener.php';

        $a = ($container->privates['messenger.retry_strategy_locator'] ??= new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [], []));

        return $container->privates['messenger.retry.send_failed_message_for_retry_listener'] = new \Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener($a, $a, ($container->privates['logger'] ?? $container->getLoggerService()), ($container->services['event_dispatcher'] ?? $container->getEventDispatcherService()));
    }
}
