<?php
namespace App\EventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener{
    public function onKernelRequest(RequestEvent $event){
        if(!$event->isMasterRequest()){
            return;
        }
        $req = $event->getRequest();

        $message = sprintf(
            'My Error says: %s with code: %s',
            $req->getRequestUri(),
            $req->getMethod()
        );  

        $response = new Response();
        $response->setContent($message);

        $event->setResponse($response);
    }
}