<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientBundle\Entity\ClientAlert;
use Application\Form\Type\AmountType;

use Application\Sonata\ClientBundle\Entity\Compte;
use Application\Sonata\ClientBundle\Entity\CompteDeDepot;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class GarantieAdmin extends Admin
{
    protected $_prefix_label = 'garantie';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $id = $this->getRequest()->get($this->getIdParameter());

        $formMapper->with($this->getFieldLabel('title'))
            ->add('type_garantie', null,
            array(
                'label' => $this->getFieldLabel('type_garantie'),
                'disabled' => !!$id,
            ))
            ->add('montant', new AmountType(), array(
            'data_class' => 'Application\Sonata\ClientBundle\Entity\Garantie',
            'label' => $this->getFieldLabel('montant'),
        ))
            ->add('nom_de_lemeteur', null, array('label' => $this->getFieldLabel('nom_de_lemeteur')))
            ->add('nom_de_la_banques_id', 'choice', array(
                'label' => $this->getFieldLabel('nom_de_la_banques_id'),
                'data' => $id ? null : 1,
                'choices' => array(
                    0 => '',
                    1 => 'A établir',
                ))
        )
            ->add('num_de_ganrantie', null, array('label' => $this->getFieldLabel('num_de_ganrantie')))
            ->add('date_demission', null, array(
            'label' => $this->getFieldLabel('date_demission'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('date_decheance', null, array(
            'label' => $this->getFieldLabel('date_decheance'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('expire', null, array('label' => $this->getFieldLabel('expire')))
            ->add('note', null, array('label' => $this->getFieldLabel('note')));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('type_garantie.name', null, array('label' => $this->getFieldLabel('type_garantie')))
            ->add('montant', 'money', array('label' => $this->getFieldLabel('montant')))
            ->add('devise.name', null, array('label' => $this->getFieldLabel('devise')));
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function postPersist($object)
    {
        /** @var $object  \Application\Sonata\ClientBundle\Entity\Garantie */
        if ($object->getTypeGarantie()) {

            //2 => Dépôt de Garantie
            if ($object->getTypeGarantie()->getId() == 2) {

                /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
                $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $doctrine->getManager();

                //example: http://redmine.testenm.com/issues/880

                //1
                $compte = new Compte();
                $compte->setDate($object->getDateDemission());
                $compte->setMontant($object->getMontant());
                $compte->setOperation('Versement du dépôt de garantie');
                $compte->setClientId($object->getClientId());
                $compte->setGarantie($object);
                $em->persist($compte);


                //2
                $compte = new Compte();
                $compte->setDate($object->getDateDemission());
                $compte->setMontant(-$object->getMontant());
                $compte->setOperation('Transfert dans compte de dépôt');
                $compte->setClientId($object->getClientId());
                $compte->setGarantie($object);
                $em->persist($compte);

                //3
                $compte_de_depot = new CompteDeDepot();
                $compte_de_depot->setDate($object->getDateDemission());
                $compte_de_depot->setMontant($object->getMontant());
                $compte_de_depot->setOperation('Transfert du compte courant');
                $compte_de_depot->setClientId($object->getClientId());
                $compte_de_depot->setGarantie($object);
                $em->persist($compte_de_depot);

                $em->flush();

                unset($compte, $compte_de_depot);
            }
        }
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function preRemove($object)
    {
        /** @var $object  \Application\Sonata\ClientBundle\Entity\Garantie */

        //2 => Dépôt de Garantie
        if ($object->getTypeGarantie() && $object->getTypeGarantie()->getId() == 2) {
            /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
            $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $doctrine->getManager();

            /** @var $compte_de_depot CompteDeDepot */
            list($compte_de_depot) = $em->getRepository('ApplicationSonataClientBundle:CompteDeDepot')
                ->createQueryBuilder('c')
                ->select('SUM(c.montant) as total')
                ->andWhere('c.garantie = :id')
                ->setParameter(':id', $object->getId())
                ->setMaxResults(1)
                ->getQuery()
                ->execute();

            if ($compte_de_depot['total'] != 0) {
                echo 'Transfert du compte courant';
                exit;
            } else {

                //DELETE
                /** @var $compte Compte */
                $compte = $em->getRepository('ApplicationSonataClientBundle:Compte')->findByGarantie($object->getId());

                foreach ($compte as $c) {
                    $c->setGarantie(NULL);
                    $em->persist($c);
                }

                /** @var $compte_de_depot CompteDeDepot */
                $compte_de_depot = $em->getRepository('ApplicationSonataClientBundle:CompteDeDepot')->findByGarantie($object->getId());

                foreach ($compte_de_depot as $c) {
                    $c->setGarantie(NULL);
                    $em->persist($c);
                }

                $em->flush();

                unset($c, $compte_de_depot, $compte);
            }
        }
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Garantie */
        parent::validate($errorElement, $object);

        $this->_setupAlerts($errorElement, $object);
    }


    /**
     * @param $errorElement
     * @param $object
     */
    protected function _setupAlerts($errorElement, $object)
    {
        /** @var $doctrine  \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $this->getConfigurationPool()->getContainer()->get('doctrine');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('garanties');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client_id = :client_id')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client_id' => $object->getClientId(),
            ':tab' => $tab,
        ))->getQuery()->execute();

        /* @var $object \Application\Sonata\ClientBundle\Entity\Garantie */

        if ($object->getTypeGarantie()) {

            $value = $object->getTypeGarantie()->getId();
            switch ($value) {

                case 1:
                    $value = $object->getNomDeLaBanquesId();

                    if (0) {
                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText('Manque Garantie Bancaire');

                        $em->persist($alert);
                    }

                    $value = $object->getDateDecheance();
                    if (0) {

                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText("Date d'échéance Garantie Bancaire proche");

                        $em->persist($alert);
                    }
                    break;

                case 3:
                    $value = $object->getNomDeLaBanquesId();

                    if (0) {
                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText('Manque Garantie Parentale');

                        $em->persist($alert);
                    }

                    $value = $object->getDateDecheance();
                    if (0) {

                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText("Date d'échéance Garantie Parentale proche");

                        $em->persist($alert);
                    }
                    break;
            }
        }
    }
}

