<?php

namespace Application\Listener;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class SendErrorToMailListener
{
    protected $_deny_filter = array(
        '\Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
        '\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException',
    );

    protected $_get_status_code;

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelException(GetResponseEvent $event)
    {
        $date = date('d/m/Y H:i:s');
        $messages = array();

        $server = array();
        $allow_parameter = array(
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

        foreach ($allow_parameter as $p) {

            if (isset($_SERVER['REQUEST_SCHEME']) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                $server['URL'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            }

            if (isset($_SERVER[$p])) {
                $server[$p] = $_SERVER[$p];
            }
        }


        //DBALException
        //if( instanceof NotFoundHttpException)
        /** @var $exception \Exception */
        $exception = $event->getException();

        if ($this->filterFlattenException($exception) || $this->filterExceptionInstanceOf($exception)) {
            return;
        }

        $messages[] = $this->getArrayFormatView(array('$_DATE' => $date));
        $messages[] = $this->getArrayFormatView(array('$_ERROR MESSAGE' => sprintf('Exception thrown when handling an exception (%s: %s)', get_class($exception), $exception->getMessage())));
        $messages[] = $this->getArrayFormatView(array('$_SERVER' => $server));

        if (!empty($_GET)) {
            $messages[] = $this->getArrayFormatView(array('$_GET' => $_GET));
        }

        if (!empty($_POST)) {
            $messages[] = $this->getArrayFormatView(array('$_POST' => $_POST));
        }

        $content = implode("\n\n", $messages);

        $subject = 'Eurotax error code ' . $this->_get_status_code . ' date ' . $date;
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('eurotax@hypernaut.com')
            ->setTo(array('vladimir@hypernaut.net', 'defan.hypernaut@gmail.com'))
            ->setBody($content);


        echo '<pre>';
        echo $subject . "\n";
        exit($content);

        /** @var $mailer \Swift_Mailer */
        $mailer = \AppKernel::getStaticContainer()->get('mailer');
        $mailer->send($message);
    }

    /**
     * @param \Exception $exception
     * @return bool
     */
    public function filterExceptionInstanceOf(\Exception $exception)
    {
        foreach ($this->_deny_filter as $name) {
            if ($exception instanceof $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function filterFlattenException(\Exception $exception)
    {
        if (!$exception instanceof FlattenException) {
            $exception = FlattenException::create($exception);
        }

        $this->_get_status_code = $exception->getStatusCode();
        if ($this->_get_status_code < 500) {
            return true;
        }
        return false;
    }


    /**
     * @param $array
     * @param int $count
     * @return string
     */
    protected function getArrayFormatView($array, $count = 0)
    {
        $messages = array();
        foreach ($array as $key => $value) {

            if (is_array($value)) {
                $messages[] = str_repeat(' ', $count) . $key . "\n" . $this->getArrayFormatView($value, $count + 2);
            } else {
                $messages[] = str_repeat(' ', $count) . $key . ' => ' . $value;
            }

        }
        return implode("\n", $messages);
    }
}
