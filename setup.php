<?php
    register_shutdown_function(array('\Application\Sonata\ErrorsBundle\Tools\SendErrorsToMail', 'shutdown'));