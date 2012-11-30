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
    protected $_uniqid = null;

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
     * @Given /^I logged in as "([^"]*)" with password "([^"]*)" drupal$/
     */
    public function iLoggedInAsWithPasswordDrupal($login, $password)
    {
        /** @var $session \Behat\Mink\Session */

        $session = $this->getSession();
        $session->visit($this->locatePath('/user'));

        $login = $this->fixStepArgument($login);
        $password = $this->fixStepArgument($password);
        $session->getPage()->fillField('name', $login);
        $session->getPage()->fillField('pass', $password);
        $session->getPage()->pressButton('edit-submit');
    }

    /**
     * @Given /^I save list drupal$/
     */

    public function iSaveListDrupal()
    {
        /** @var $session \Behat\Mink\Session */
        $list = explode("\n", file_get_contents(getcwd() . '/' . 'list.txt'));
        foreach ($list as $key => $name) {
            $this->nameList($name, $key + 20);
        }
    }

    /**
     * @param $name
     * @param $weight
     */
    protected function nameList($name, $weight)
    {
        $session = $this->getSession();
        $session->visit($this->locatePath('admin/structure/taxonomy/realisations/add'));

        $session->getPage()->fillField('name', $name);
        //$session->getPage()->selectFieldOption('edit-parent', 'Secteur');

        $session->getPage()->pressButton('Valider');
    }


    /**
     * Fills in form field with specified id|name|label|value.
     *
     * @When /^(?:|I )fill field "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function fillField($field, $value)
    {
        if (!$this->_uniqid) {
            $content = $this->getSession()->getPage()->getContent();

            if (preg_match('/\?uniqid=(.*?)\&/is', $content, $match)) {
                $this->_uniqid = $match[1];
            }
        }
        parent::fillField($this->_uniqid . '_' . $field, $value);
    }

    //javascript

    /**
     * @BeforeStep @javascript
     */
    public function beforeStep($event)
    {
        $text = $event->getStep()->getText();
        if (preg_match('/(follow|press|click|submit)/i', $text)) {
            $this->ajaxClickHandler_before();
        }
    }

    /**
     * @AfterStep @javascript
     */
    public function afterStep($event)
    {
        $text = $event->getStep()->getText();
        if (preg_match('/(follow|press|click|submit)/i', $text)) {
            $this->ajaxClickHandler_after();
        }
    }


    /**
     * Hook into jQuery ajaxStart and ajaxComplete events.
     * Prepare __ajaxStatus() functions and attach them to these events.
     * Event handlers are removed after one run.
     */
    public function ajaxClickHandler_before()
    {
        $javascript = <<<JS
window.jQuery(document).one('ajaxStart.ss.test', function(){
    window.__ajaxStatus = function() {
        return 'waiting';
    };
});
window.jQuery(document).one('ajaxComplete.ss.test', function(){
    window.__ajaxStatus = function() {
        return 'no ajax';
    };
});
JS;
        $this->getSession()->executeScript($javascript);
    }

    /**
     * Wait for the __ajaxStatus()to return anything but 'waiting'.
     * Don't wait longer than 5 seconds.
     */
    public function ajaxClickHandler_after()
    {
        $this->getSession()->wait(5000,
            "(typeof window.__ajaxStatus !== 'undefined' ?
                window.__ajaxStatus() : 'no ajax') !== 'waiting'"
        );
    }

    /**
     * @Given /^I press "([^"]*)" button$/
     */
    public function stepIPressButton($button)
    {
        /** @var $page \Behat\Mink\Element\DocumentElement */
        $page = $this->getSession()->getPage();

        $button_selector = array('link_or_button', "'$button'");
        $button_element = $page->find('named', $button_selector);

        if (null === $button_element) {
            throw new \Exception("'$button' button not found");
        }

        $this->ajaxClickHandler_before();
        $button_element->click();
        $this->ajaxClickHandler_after();
    }
}