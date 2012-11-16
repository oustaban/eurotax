<?php

namespace Application\Tools;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;

//use Behat\Behat\Context\BehatContext,
//    Behat\Behat\Exception\PendingException;
//use Behat\Gherkin\Node\PyStringNode,
//    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;

abstract class MinkContext extends BaseMinkContext implements KernelAwareInterface
{

    protected $kernel;
    protected $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I logged in as "([^"]*)" with password "([^"]*)"$/
     */
    public function iLoggedInAsWithPassword($login, $password)
    {
        /** @var $session \Behat\Mink\Session */

        $session = $this->getSession();
        $session->visit($this->locatePath('/login'));
        $login = $this->fixStepArgument($login);
        $password = $this->fixStepArgument($password);
        $session->getPage()->fillField('username', $login);
        $session->getPage()->fillField('password', $password);
        $session->getPage()->pressButton('_submit');

        //throw new PendingException();
    }
}