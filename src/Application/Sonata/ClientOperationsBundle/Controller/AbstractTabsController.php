<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\ClientOperationsBundle\Entity\Locking;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Sonata\ClientOperationsBundle\Entity\Imports;

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
    protected $_month = 0;
    protected $_query_month = 0;
    protected $_year = 0;
    protected $_import_counts = array();
    protected $_import_reports = array();
    protected $_client_documents = array();
    protected $_imports = null;
    protected $_parameters_url = array();


    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function configure()
    {
        parent::configure();
        $this->admin->getRequestParameters($this->getRequest());

        if (empty($this->admin->client_id)) {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }

        $this->client_id = $this->admin->client_id;
        $this->_month = $this->admin->month;
        $this->_query_month = $this->admin->query_month;
        $this->_year = $this->admin->year;

        $this->_parameters_url['filter']['client_id']['value'] = $this->client_id;

        if ($this->admin->month_default != $this->_query_month) {
            $this->_parameters_url['month'] = $this->_query_month;
        }

        $this->jsSettingsJson(array(
            'url' => array(
                'importremove' => $this->admin->generateUrl('importremove', array('filter' => array('client_id' => array('value' => $this->client_id))))
            )
        ));
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
            'client_documents' => $this->_client_documents,
            'month_list' => $this->getMonthList(),
            'month' => $this->_month,
            'query_month' => $this->_query_month,
            'year' => $this->_year,
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'operation_type' => $this->_operationType,
            'action' => $action,
            'blocked' => isset($this->_locking) ? 0 : 1,
            'js_settings_json' => $this->_jsSettingsJson,
            '_filter_json' => $this->_parameters_url,

        ));
    }

    /**
     * @return mixed
     */
    protected function getLocking()
    {

        $this->_locking = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->client_id, 'month' => $this->_month, 'year' => $this->_year));
        #$session = $this->getRequest()->getSession();
        #$session->set('locking', $this->_locking);
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

        if ($date_piece) {
            $this->_month = $date_piece->format('m');
            $this->_year = $date_piece->format('Y');
        }
    }

    /**
     * @return array
     */
    protected function getMonthList()
    {
        $year = date('Y');

        $month_list = array();
        $month_list[] = array('key' => '', 'name' => 'Operations en cours');

        for ($month = date('n'); $month > date('n') - 12; $month--) {

            $mktime = mktime(0, 0, 0, $month, 1, $year);

            $month_list[] = array('key' => date('n' . $this->admin->date_filter_separator . 'Y', $mktime), 'name' => $this->datefmtFormatFilter(new \DateTime(date('Y-m-d', $mktime)), 'MMMM') . ' ' . date('Y', $mktime));
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

    public function cloneAction($id = null)
    {
        return $this->_action($this->abstractCloneAction($id), 'create', 'form_layout');
    }

    protected function abstractCloneAction($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);
        $object = clone $object;

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        // the key used to lookup the template
        $templateKey = 'edit';

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($object);
        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
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
    public function batchAction()
    {

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
        $this->_initClientDocuments();

        $action = $this->_action(parent::listAction(), 'list', 'list_layout');
        return $action;
    }

    /**
     * @return AbstractTabsController
     */
    protected function _initClientDocuments()
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $this->_client_documents = $qb->select('d')
            ->from('Application\Sonata\ClientBundle\Entity\Document', 'd')
            ->where('d.client_id = :client_id')
            ->setParameter(':client_id', $this->client_id)
            ->getQuery()->getResult();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function redirectTo($object)
    {
        if ($this->get('request')->get('btn_create_and_edit')) {
            $url = $this->admin->generateUrl('list');

            return new RedirectResponse($url);
        }

        return parent::redirectTo($object);
    }

    /**
     * {@inheritdoc}
     */
    public function importAction()
    {
        $translator = $this->get('translator');
        $exclude_fields = array('id', 'client_id', 'imports');

        if (!empty($_FILES) && !empty($_FILES["inputFile"])) {
            $file = TMP_UPLOAD_PATH . '/' . $_FILES["inputFile"]["name"];
            $tmpFile = $_FILES["inputFile"]["tmp_name"];
            if (move_uploaded_file($tmpFile, $file)) {
                /* @var $objReader \PHPExcel_Reader_Excel2007 */
                $objReader = \PHPExcel_IOFactory::createReaderForFile($file);
                /* @var $objPHPExcel \PHPExcel */
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($file);
                $sheets = $objPHPExcel->getAllSheets();

                $user = $this->get('security.context')->getToken()->getUser();
                $em = $this->getDoctrine()->getManager();
                $users = $em->getRepository('ApplicationSonataUserBundle:User')->find($user->getId());

                $this->_imports = new Imports();
                $this->_imports->setDate(new \DateTime(date('Y-m-d H:i:s')));
                $this->_imports->setUser($users);

                $em->persist($this->_imports);
                $em->flush();

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
                                $object->setImports($this->_imports);
                                $admin->create($object);
                                $this->setCountImports($class, 'success');

                            } else {

                                $this->setCountImports($class, 'errors', $form->getErrorsAsString());
                            }
                        }
                    }
                }
            }
        }

        $this->get('session')->setFlash('sonata_flash_info|raw', implode("<br/>", $this->getCountMessageImports()));

        return $this->render('ApplicationSonataClientOperationsBundle:redirects:back.html.twig');
    }


    protected function getCountMessageImports()
    {
        $translator = $this->get('translator');

        $messages = array();

        foreach ($this->_import_counts as $table => $values) {

            $message = array();
            switch ($table) {

                case 'rows';
                    if (isset($values['success'])) {
                        $message[] = $translator->trans('Imported %table% : %count%', array(
                            '%table%' => $table,
                            '%count%' => $values['success'],
                        ));
                    }
                    if (isset($values['errors'])) {

                        $render_view_popup = $this->renderView('ApplicationSonataClientOperationsBundle:popup:popup_message.html.twig', array(
                            'error_reports' => $this->_import_reports,
                            'active_tab' => $this->_tabAlias,
                            'import_id' => $this->_imports ? $this->_imports->getId() : null,
                        ));


                        $message[] = '<span class="error">' . $translator->trans('Not valid %table% : %count%', array(
                            '%table%' => $table,
                            '%count%' => $values['errors'],
                        )) . '</span>, ' . $render_view_popup;
                    }

                    $messages[] = implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $message);
                    break;
                default;
                    $table = $translator->trans('ApplicationSonataClientOperationsBundle.form.' . $table . '.title');
                    $str_repeat = str_repeat('&nbsp;', 4);
                    if (isset($values['success'])) {
                        $message[] = $str_repeat . $translator->trans('Imported : %count%', array('%count%' => $values['success']));
                    }
                    if (isset($values['errors'])) {
                        $message[] = $str_repeat . '<span class="error">' . $translator->trans('Not valid : %count%', array('%count%' => $values['errors'])) . '</span>';
                    }
                    $messages[] = '<strong>' . $table . '</strong><br />' . implode('; ', $message);
                    break;
            }


        }

        return $messages;
    }

    /**
     * @param $table
     * @param $type
     * @param string $messages
     */
    protected function setCountImports($table, $type, $messages = '')
    {
        if (!isset($this->_import_counts[$table][$type])) {
            $this->_import_counts['rows'][$type] = 0;
            $this->_import_counts[$table][$type] = 0;
        }

        $this->_import_counts['rows'][$type]++;
        $this->_import_counts[$table][$type]++;

        if (!empty($messages)) {
            $this->_import_reports[$table][] = $messages;
        }
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
    }

    public function importremoveAction()
    {
        $id = $this->getRequest()->query->get('id');


        $em = $this->getDoctrine()->getManager();
        foreach ($this->tabs_arr as $table => $entity) {
            $object = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $entity)->findByImports($id);

            foreach ($object as $obj) {
                $em->remove($obj);
            }
            unset($object);
        }
        $em->flush();

        return $this->renderJson(array(
            'status' => 1,
        ));
    }


    /**
     * @param $client_id
     * @param $month
     * @param $year
     * @param int $blocked
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function lockingAction($client_id, $month, $year, $blocked = 1)
    {
        list($_month, $_year) = $this->admin->getQueryMonth($month);

        $em = $this->getDoctrine()->getManager();

        $locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $client_id, 'month' => $_month, 'year' => $_year));

        if ($locking) {
            $em->remove($locking);
            $em->flush();
        }

        if ($blocked) {
            $locking = new Locking();
            $locking->setClientId($client_id);
            $locking->setMonth($_month);
            $locking->setYear($_year);
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
                    if (!($this->get('request')->getMethod() == 'POST' && $this->get('request')->request->get('action') == 'delete')) {
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
