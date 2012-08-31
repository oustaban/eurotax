<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\ClientOperationsBundle\Entity\Locking;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    protected $_locking = '';
    protected $_month = '';
    protected $_year = '';

    /**
     *
     */
    public function __construct()
    {
        $request = Request::createFromGlobals();
        $filter = $request->query->get('filter');
        if (!empty($filter['client_id']) && !empty($filter['client_id']['value'])) {

            $this->client_id = $filter['client_id']['value'];
            $this->_month = $request->query->get('month', date('m'));
            $this->_year = date('Y');

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
            'month' =>  $this->_month,
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'operation_type' => $this->_operationType,
            'action' => $action,
            'blocked' => isset($this->_locking) ? 0 : 1,
        ));
    }

    /**
     * @return mixed
     */
    protected function getLocking(){

        $this->_locking = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->client_id, 'month' => $this->_month, 'year' => $this->_year));

        return $this->_locking;
    }

    /**
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    protected function getLockingAccessDenied()
    {
        if (isset($this->_locking)) {
            throw new AccessDeniedException();
        }
    }


    /**
     *
     */
    protected function getObjectMonthYear()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        $date_piece = $object->getDatePiece();

        $this->_month = $date_piece->format('m');
        $this->_year = $date_piece->format('Y');
    }

    /*
     *
     */
    protected function getMonthList()
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
        $this->getObjectMonthYear();
        $this->getLocking();
        $this->getLockingAccessDenied();

        $action = $this->_action(parent::editAction(), 'edit', 'form_layout');
        return $action;
    }

    /**
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        $this->getObjectMonthYear();
        $this->getLocking();
        $this->getLockingAccessDenied();

        $action = parent::deleteAction($id);
        return $action;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function batchAction(){

        $date_piece = $this->getRequest()->query->get('date_piece');
        $this->_month = $date_piece['value']['month'];

        $this->getLocking();
        $this->getLockingAccessDenied();

        return parent::batchAction();
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $this->getLocking();
        $session = $this->getRequest()->getSession();
        $session->set('locking', $this->_locking);

        $action = $this->_action(parent::listAction(), 'list', 'list_layout');
        return $action;
    }

    /**
     * {@inheritdoc}
     */
    public function redirectTo($object)
    {
        if ($this->get('request')->get('btn_create_and_edit')) {
            $url = $this->admin->generateUrl('list');

            return  new RedirectResponse($url);
        }

        return parent::redirectTo($object);
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

                            $formData = array('client_id' => $this->client_id, '_token' => $this->get('form.csrf_provider')->generateCsrfToken('unknown'));
                            foreach ($line as $index => $value) {
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
     * @param $client_id
     * @param $month
     * @param int $blocked
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function lockingAction($client_id, $month, $blocked = 1)
    {
        $year = date('Y');
        $em = $this->getDoctrine()->getManager();

        $locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $client_id, 'month' => $month));

        if ($locking) {
            $em->remove($locking);
            $em->flush();
        }

        if ($blocked) {
            $locking = new Locking();
            $locking->setClientId($client_id);
            $locking->setMonth($month);
            $locking->setYear($year);
            $em->persist($locking);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_sonata_clientoperations_' . $this->_tabAlias . '_list', array('filter' => array('client_id' => array('value' => $client_id)), 'month' => $month)));
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
