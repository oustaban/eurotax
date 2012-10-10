<?php

namespace Application\Sonata\ClientBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


/**
 * Client controller.
 *
 */
class ClientController extends Controller
{
    /** @var array('Code' => 'Country') */
    protected $_CountryEU = array(
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'BG' => 'Bulgaria',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'EE' => 'Estonia',
        'FI' => 'Finland',
        'FR' => 'France',
        'DE' => 'Germany',
        'GR' => 'Greece',
        'HU' => 'Hungary',
        'IE' => 'Ireland',
        'IT' => 'Italy',
        'LV' => 'Latvia',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MT' => 'Malta',
        'NL' => 'Netherlands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'RO' => 'Romania',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'ES' => 'Spain',
        'SE' => 'Sweden',
        'GB' => 'United Kingdom',
    );

    protected $_jsSettingsJson = null;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        if ($this->getRequest()->query->get('TEST', null)) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('defan.hypernaut@gmail.com')
                ->setTo('defan.hypernaut@gmail.com')
                ->setBody('xxxxxx');

            $this->get('mailer')->send($message);
        }

        return $this->_action(parent::createAction());
    }


    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        return $this->_action(parent::editAction($id), $id);
    }

    /**
     * @param $object
     * @param null $id
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($object, $id = null, $template = 'standard_layout_client')
    {
        $client = null;
        if ($id) {
            $client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($id);
        }

        $this->jsSettingsJson(array(
            'country_eu' => $this->_CountryEU,
        ));

        return $this->render('ApplicationSonataClientBundle::' . $template . '.html.twig', array(
            'client_id' => $id,
            'current_client' => $client,
            'active_tab' => 'client',
            'content' => $object->getContent(),
            'js_settings_json' => $this->_jsSettingsJson,
        ));
    }

    /**
     * @param array $data
     */
    public function jsSettingsJson(array $data)
    {
        $this->_jsSettingsJson = json_encode($data);
    }

    /**
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if ($parameters && isset($parameters['action'])) {

            switch ($parameters['action']) {
                case 'edit':
                case 'create':
                    $parameters['base_template'] = $this->admin->getTemplate('ajax');
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

}
