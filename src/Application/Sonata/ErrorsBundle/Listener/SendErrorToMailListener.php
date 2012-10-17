<?php

namespace Application\Sonata\ErrorsBundle\Listener;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Application\Sonata\ErrorsBundle\Tools\SendErrorsToMail;

class SendErrorToMailListener
{
    public function onKernelException(GetResponseEvent $event)
    {
        SendErrorsToMail::byException($event->getException());
    }
}
