<?php

namespace Application\Listener;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SendErrorToMailListener
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelException(GetResponseEvent $event)
    {
        $date = date('d/m/Y H:i:s');
        $messages = array();

        $server = array();
        $allow_parameter =  array(
            'HTTP_HOST',
            'REQUEST_URI',
            'REQUEST_METHOD',
            'HTTP_REFERER',
            'HTTP_USER_AGENT',
            'HTTP_ACCEPT_LANGUAGE',
            'HTTP_COOKIE',
            'SERVER_SOFTWARE',
            'SERVER_NAME',
            'SERVER_ADDR',
            'REMOTE_ADDR',

            'DOCUMENT_ROOT',
            'CONTENT_TYPE',
            'CONTENT_LENGTH',
            'REQUEST_SCHEME',
            'REQUEST_TIME',
        );

        foreach($allow_parameter as $p){
            if(isset($_SERVER[$p])){
                $server[$p] = $_SERVER[$p];
            }
        }

        $messages[] = $this->getArrayForatView(array('$_DATE' => $date));
        $messages[] = $this->getArrayForatView(array('$_ERROR MESSAGE' => $event->getException()->getMessage()));
        $messages[] = $this->getArrayForatView(array('$_SERVER' => $server));
        $messages[] = $this->getArrayForatView(array('$_GET' => $_GET));
        $messages[] = $this->getArrayForatView(array('$_POST' => $_POST));

        $content = implode("\n\n", $messages);

        $message = \Swift_Message::newInstance()
            ->setSubject('Eurotax error '.$date)
            ->setFrom('eurotex@hypernaut.com')
            ->setTo('defan.hypernaut@gmail.com')
            ->setBody($content);

        $mailer = \AppKernel::getStaticContainer()->get('mailer');
        $mailer->send($message);

        #exit('<pre>'.$content);
    }

    /**
     * @param $array
     * @param int $count
     * @return string
     */
    protected function getArrayForatView($array, $count = 0)
    {
        $messages = array();
        foreach ($array as $key => $value) {

            if (is_array($value)) {
                $messages[] = str_repeat(' ', $count) . $key . "\n" . $this->getArrayForatView($value, $count + 2);
            } else {
                $messages[] = str_repeat(' ', $count) . $key . ' => ' . $value;
            }

        }
        return implode("\n", $messages);
    }
}
