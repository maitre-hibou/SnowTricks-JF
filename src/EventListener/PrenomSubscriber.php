<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class PrenomSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'addPrenomToAttributes',
            'kernel.controller' => 'test1',
            'kernel.response' => 'test2',
        ];
    }

    public function addPrenomToAttributes(RequestEvent $requestEvent)
    {
        $requestEvent->getRequest()->attributes->set('prenom', 'Jose');
    }

    public function test1()
    {
        dump('1');
    }

    public function test2()
    {
        dump('2');
    }
}