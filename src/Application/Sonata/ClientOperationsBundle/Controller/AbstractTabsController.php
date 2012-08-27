<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class AbstractTabsController extends Controller
{

    /**
     * @var int
     */
    public $client_id = null;
    public $tabs_arr = array(
        'sell' => array(
            'V01-TVA' => 'V01TVA',
            'V03-283-I' => 'V03283I',
            'V05-LIC' => 'V05LIC',
            'DEB Exped' => 'DEBExped',
            'V07-EX' => 'V07EX',
            'V09-DES' => 'V09DES',
            'V11-INT' => 'V11INT',
        ),
        'buy' => array(
            'A02-TVA' => 'A02TVA',
            'A04-283-I' => 'A04283I',
            'A06-AIB' => 'A06AIB',
            'DEB Intro' => 'DEBIntro',
            'A08-IM' => 'A08IM',
            'A10-CAF' => 'A10CAF',
        ),
    );

    /**
     * @var string
     */
    protected $_tabAlias = '';
    protected $_operationType = '';
    protected $_jsSettingsJson = null;

    /**
     *
     */
    public function __construct()
    {
        $filter = Request::createFromGlobals()->query->get('filter');
        if (!empty($filter['client_id']) && !empty($filter['client_id']['value'])) {

            $this->client_id = $filter['client_id']['value'];


        } else {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }
    }

    /**
     * @param $data
     * @param string $action
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($data, $action = 'create', $template = 'standard_layout')
    {
        $client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($this->client_id);

        return $this->render('ApplicationSonataClientOperationsBundle::' . $template . '.html.twig', array(
            'client_id' => $this->client_id,
            'client' => $client,
            'month_list' => $this->selectedMonthTopInfo(),
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'operation_type' => $this->_operationType,
            'action' => $action,
        ));
    }

    /*
     *
     */
    protected function  selectedMonthTopInfo()
    {
        $month_list = array();

        $month_arr = range(1, date('m'));
        $year = date('Y');

        foreach ($month_arr as $key => $month) {
            $month_list[] = array('key' => $key + 1, 'name' => $this->datefmtFormatFilter(new \DateTime("{$year}-{$month}-01"), 'MMMM'));
        }

        $month_list = array_reverse($month_list);

        return $month_list;
    }

    /**
     * @param $datetime
     * @param null $format
     * @return string
     */
    public function datefmtFormatFilter($datetime, $format = null)
    {
        $dateFormat = is_int($format) ? $format : \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = is_string($format) ? $format : null;

        $formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            $dateFormat,
            $timeFormat,
            null,
            $calendar,
            $pattern
        );
        $formatter->setLenient(false);
        $timestamp = $datetime->getTimestamp();

        return $formatter->format($timestamp);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->_action(parent::createAction(), 'create', 'form_layout');
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        return $this->_action(parent::editAction(), 'edit', 'form_layout');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->_action(parent::listAction(), 'list', 'list_layout');
    }


    /**
     *
     */
    public function blankAction()
    {
        $translator = $this->get('translator');
        $exclude_fields = array('id', 'client_id', 'imports');

        $file_name = 'blank';

        $entity_arr = $this->tabs_arr[$this->_operationType] ? : array();

        $excel = new \PHPExcel();

        $i = 0;
        foreach ($entity_arr as $sheet_name => $class) {

            $className = '\Application\Sonata\ClientOperationsBundle\Entity\\' . $class;
            $entity = new $className();

            $reflect = new \ReflectionClass($entity);
            $props = $reflect->getProperties();


            $fields = array();
            foreach ($props as $field) {
                if (!in_array($field->name, $exclude_fields)) {
                    $fields[] = $translator->trans('ApplicationSonataClientOperationsBundle.list.' . $class . '.' . $field->name);
                }
            }

            unset($entity);

            if ($i > 0) {
                $excel->createSheet(null, $i);
            }

            $excel->setActiveSheetIndex($i);
            $sheet = $excel->getActiveSheet();
            $sheet->fromArray($fields);
            $sheet->setTitle($sheet_name);

            $i++;
        }


        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="' . $file_name . '.xlsx"');

        $obj_writer = new \PHPExcel_Writer_Excel2007($excel);
        $obj_writer->save('php://output');
        exit;
        /*$response = new Response();
        $response->headers->set('Content-type', 'application/excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$file_name.'.xlsx"');
        return $response;*/
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
                case 'list':
                case 'edit':
                case 'create':
                    if (!$this->getRequest()->query->get('client_id')) {
                        $parameters['base_template'] = 'ApplicationSonataClientOperationsBundle::ajax_layout.html.twig';
                        #$parameters['base_template'] = $this->admin->getTemplate('ajax');
                    }
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

    /**
     * @param array $data
     */
    public function jsSettingsJson(array $data)
    {

        $this->_jsSettingsJson = json_encode($data);
    }
}
