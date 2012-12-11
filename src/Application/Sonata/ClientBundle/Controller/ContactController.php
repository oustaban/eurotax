<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{
    /**
     * @var string
     */
    protected  $_tabAlias = 'contact';

    public function configure()
    {
        parent::configure();

        if (!$this->client->getAutreDestinataireDeFacturation()){
            throw new NotFoundHttpException();
        }
    }
}
