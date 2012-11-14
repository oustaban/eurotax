<?php

namespace Application\Sonata\ErrorsBundle\Tools;

use Symfony\Component\HttpKernel\Exception\FlattenException;

class SendErrorsToMail
{
    /**
     * @var SendErrorsToMail
     */
    private static $_instance;
    /**
     * @var \Exception
     */
    protected $_exception;
    protected $_debug = true;
    protected $_date;
    protected $_get_status_code;
    private $_server = array();

    protected $_mailFrom = 'eurotax@hypernaut.com';

    protected $_mailTo = array(
        'Pierre.DeLespinay@masao.eu',
        'vladimir@hypernaut.net',
        'defan.hypernaut@gmail.com',
    );

    /**
     * @var array
     */
    protected $_allow_server_parameter = array(
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

    /**
     * @var array
     */
    protected $_deny_filter = array(
        '\Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
        '\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException',
    );

    private function __construct()
    {
        $this->_date = date('d/m/Y H:i:s');
        $this->allowServerParameter();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @static
     *
     */
    public static function shutdown() {
        $error = error_get_last();
        if (isset($error)) {
            SendErrorsToMail::byException(new \ErrorException($error['message'], $error['type'], null, $error['file'], $error['line']));
        }
    }

    /**
     * @static
     * @param \Exception $exception
     */
    public static function byException(\Exception $exception)
    {
        static::getInstance()
            ->setException($exception)
            ->sendMail();
    }

    /**
     * @static
     * @return SendErrorsToMail
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {

            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @return SendErrorsToMail
     */
    public function sendMail()
    {
        /** @var $exception \Exception */
        $exception = $this->getException();

        if (!($this->filterFlattenException($exception) || $this->filterExceptionInstanceOf($exception))) {
            $content = implode("\n\n", $this->getMessage());

            $subject = 'Eurotax error code ' . $this->_get_status_code . ' date ' . $this->_date;
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->_mailFrom)
                ->setTo($this->_mailTo)
                ->setBody($content);

            if ($this->_debug) {
                echo '<pre>';
                echo $content;
                pritn_r(debug_backtrace(false));
                exit;
            }

            /** @var $mailer \Swift_Mailer */
            $mailer = \AppKernel::getStaticContainer()->get('mailer');
            $mailer->send($message);
        }

        return $this;
    }

    /**
     * @param \Exception $exception
     * @return SendErrorsToMail
     */
    public function setException(\Exception $exception)
    {
        $this->_exception = $exception;

        return $this;
    }

    /**
     * @return \Exception
     */
    protected function getException()
    {
        return $this->_exception;
    }

    /**
     * @param bool $value
     * @return SendErrorsToMail
     */
    public function setDebug($value = true)
    {
        $this->_debug = $value;

        return $this;
    }


    protected function allowServerParameter()
    {
        foreach ($this->_allow_server_parameter as $p) {

            if (isset($_SERVER['REQUEST_SCHEME']) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                $this->_server['URL'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            }

            if (isset($_SERVER[$p])) {
                $this->_server[$p] = $_SERVER[$p];
            }
        }
    }

    /**
     * @return array
     */
    protected function getMessage()
    {
        $messages = array();
        /** @var $exception \Exception */
        $exception = $this->getException();

        $messages[] = $this->getArrayFormatView(array('$_DATE' => $this->_date));

        $messages[] = $this->getArrayFormatView(array('$_ERROR MESSAGE' =>
        sprintf("Exception thrown when handling an exception (%s: %s) \nFile %s \nLine %s", get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine())
        ));

        $messages[] = $this->getArrayFormatView(array('$_SERVER' => $this->_server));

        if (!empty($_GET)) {
            $messages[] = $this->getArrayFormatView(array('$_GET' => $_GET));
        }

        if (!empty($_POST)) {
            $messages[] = $this->getArrayFormatView(array('$_POST' => $_POST));
        }

        return $messages;
    }


    /**
     * @param \Exception $exception
     * @return bool
     */
    protected function filterExceptionInstanceOf(\Exception $exception)
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
     * @return bool
     */
    protected function filterFlattenException(\Exception $exception)
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
     * @param array $data
     * @param int $count
     * @return string
     */
    protected function getArrayFormatView(array $data, $count = 0)
    {
        $messages = array();
        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $messages[] = str_repeat(' ', $count) . $key . "\n" . $this->getArrayFormatView($value, $count + 2);
            } else {
                $messages[] = str_repeat(' ', $count) . $key . ' => ' . $value;
            }

        }
        return implode("\n", $messages);
    }
}
