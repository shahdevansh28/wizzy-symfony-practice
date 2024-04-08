<?php

namespace App\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EventSubscriber implements EventSubscriberInterface{
    public static function getSubscribedEvents(){
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['logException', 0],
                ['notifyException', -10]
            ]
        ];
    }


    public function processException(ExceptionEvent $e){
    }
    public function logException(ExceptionEvent $e){
    }
    public function notifyException(ExceptionEvent $e){
    }
}