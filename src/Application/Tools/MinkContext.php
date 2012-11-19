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
    }


    /**
     * Fills in form field with specified id|name|label|value.
     *
     * @When /^(?:|I )fill field "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function fillField($field, $value)
    {
//        $named = $this->getSession()->getPage()->find('method', 'POST');
//
//        print_r($named);
//
////        print_r($this->getSession()->getPage()->find('named', 'METHOD'));
//        echo "\n\n";
//        echo "$field, $value";
//        exit;
        parent::fillField($field, $value);
    }

}