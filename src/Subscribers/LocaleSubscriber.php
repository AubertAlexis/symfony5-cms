<?php

namespace App\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var string $defaultLocale
     */
    private $defaultLocale;

    /**
     * @var SessionInterface $session
     */
    private $session;

    public function __construct(string $defaultLocale = "fr", SessionInterface $session)
    {
        $this->defaultLocale = $defaultLocale;
        $this->session = $session;
    }

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        $locale = $request->request->get('locale')['locale'] ?? false;

        if ($locale) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 20]
            ]
        ];
    }
}
