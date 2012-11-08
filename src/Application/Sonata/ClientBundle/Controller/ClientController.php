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
        return $this->_action(parent::createAction());
    }

    /**
     * {@inheritdoc}
     */
    public function redirectTo($object)
    {
        if ($this->get('request')->get('btn_create_and_edit')) {
            $this->get('request')->query->set('btn_update_and_list', $this->get('request')->get('btn_create_and_edit'));
        }
        elseif ($this->get('request')->get('btn_update_and_list')) {
            return $this->render(':redirects:history.html.twig', array('depth' => 2, 'default' => $this->admin->generateUrl('list')));
        }

        return parent::redirectTo($object);
    }


    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        $object = $this->admin->getObject($id);
        if (!$object) {
            return $this->redirect($this->admin->generateUrl('list'));
        }

        return $this->_action(parent::editAction($id), $id);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        global $clientsDimmed;

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $clientsDimmed = json_encode($em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('c, cdi, u, ndc')
            ->leftJoin('c.center_des_impots', 'cdi')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.nature_du_client', 'ndc')
            ->andWhere('c.date_fin_mission BETWEEN :date_lowest AND :date_highest')
            ->setParameter(':date_lowest', new \DateTime('1000-01-01'))
            ->setParameter(':date_highest', new \DateTime())
            ->orderBy('c.raison_sociale')
            ->getQuery()->getArrayResult(), 256);

        return parent::listAction();
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
        if($id){
            $client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($id);
        }

        $this->jsSettingsJson(array(
            'country_eu' => $this->_CountryEU,
            'niveau_dobligation' => $this->admin->getNiveauDobligationIdListHelp(),
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
