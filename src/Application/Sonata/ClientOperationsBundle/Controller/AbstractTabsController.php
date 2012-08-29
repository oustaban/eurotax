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
        'V01-TVA' => 'V01TVA',
        'V03-283-I' => 'V03283I',
        'V05-LIC' => 'V05LIC',
        'DEB Exped' => 'DEBExped',
        'V07-EX' => 'V07EX',
        'V09-DES' => 'V09DES',
        'V11-INT' => 'V11INT',
        'A02-TVA' => 'A02TVA',
        'A04-283-I' => 'A04283I',
        'A06-AIB' => 'A06AIB',
        'DEB Intro' => 'DEBIntro',
        'A08-IM' => 'A08IM',
        'A10-CAF' => 'A10CAF',
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
            'month_list' => $this->getMonthList(),
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'operation_type' => $this->_operationType,
            'action' => $action,
        ));
    }

    /*
     *
     */
    protected function  getMonthList()
    {
        $month_list = array();

        $year = date('Y');

        for ($month = date('n'); $month > 0; $month--) {
            $month_list[] = array('key' => $month, 'name' => $this->datefmtFormatFilter(new \DateTime("{$year}-{$month}-01"), 'MMMM'));
        }

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
     */
    public function importAction()
    {
        $translator = $this->get('translator');
        $exclude_fields = array('id', 'client_id', 'imports');

        if (!empty($_FILES) && !empty($_FILES["inputFile"])) {
            $file = TMP_UPLOAD_PATH . '/' . $_FILES["inputFile"]["name"];
            $tmpFile = $_FILES["inputFile"]["tmp_name"];
            if (move_uploaded_file($tmpFile, $file)) {
                $objReader = \PHPExcel_IOFactory::createReaderForFile($file);
                /* @var $objPHPExcel \PHPExcel */
                $objPHPExcel = $objReader->load($file);
                $sheets = $objPHPExcel->getAllSheets();

                foreach ($sheets as $sheet) {
                    $title = $sheet->getTitle();
                    if (array_key_exists($title, $this->tabs_arr)) {
                        $class = $this->tabs_arr[$title];

                        $className = '\Application\Sonata\ClientOperationsBundle\Entity\\' . $class;
                        $adminClassName = '\Application\Sonata\ClientOperationsBundle\Admin\\' . $class . 'Admin';
                        $adminCode = 'application.sonata.admin.' . strtolower($class);
                        $entity = new $className();

                        $reflect = new \ReflectionClass($entity);
                        $props = $reflect->getProperties();

                        $fields = array();
                        foreach ($props as $field) {
                            if (!in_array($field->name, $exclude_fields)) {
                                $fields[] = $field->name;
                            }
                        }

                        unset($entity);

                        $data = $sheet->toArray();
                        if (isset($data[0][0]) && $data[0][0] == $translator->trans('ApplicationSonataClientOperationsBundle.list.' . $class . '.' . $fields[0])) {
                            array_shift($data);
                        }
                        foreach ($data as $line) {
                            file_put_contents($tmpFile, '');
                            /* @var $admin \Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin */
                            $admin = $this->container->get('sonata.admin.pool')->getAdminByAdminCode($adminCode);
                            $object = $admin->getNewInstance();
                            $admin->setSubject($object);
                            /* @var $form \Symfony\Component\Form\Form */
                            $form = $admin->getForm();
                            $form->setData($object);

                            $formData = array('client_id' => $this->client_id,'_token'=>$this->get('form.csrf_provider')->generateCsrfToken('unknown'));
                            foreach ($line as $index=>$value) {
                                $fieldName = $fields[$index];
                                $formData[$fieldName] = $admin->getFormValue($fieldName, $value);
                            }
                            $form->bind($formData);

                            if ($form->isValid()) {
                                $admin->create($object);
                            }
                        }
                    }
                }
            }
        }

        return $this->render('ApplicationSonataClientOperationsBundle:redirects:back.html.twig');
    }

    /**
     *
     */
    public function blankAction()
    {
        $translator = $this->get('translator');
        $exclude_fields = array('id', 'client_id', 'imports');

        $file_name = 'blank-' . md5(time() . rand(1, 99999999));

        $excel = new \PHPExcel();

        $i = 0;
        foreach ($this->tabs_arr as $sheet_name => $class) {

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

            $toCol = $sheet->getColumnDimension($sheet->getHighestColumn())->getColumnIndex();
            $toCol++;
            for ($k = 'A'; $k !== $toCol; $k++) {
                $sheet->getColumnDimension($k)->setAutoSize(true);
            }

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
