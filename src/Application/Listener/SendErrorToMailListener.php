<?php

namespace Application\Listener;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SendErrorToMailListener
{
    public function onKernelException(GetResponseEvent $event)
    {
        \Tools\SendErrorsToMail::byException($event->getException());
    }
}
