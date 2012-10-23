<?php

namespace Application\Sonata\UserBundle\Form\Type;

use Sonata\UserBundle\Form\Type\SecurityRolesType as BaseSecurityRolesType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class SecurityRolesType extends BaseSecurityRolesType
{
    protected $_excludeRoles = array(
        'ROLE_SUPER_ADMIN',
        'ROLE_USER',
        'ROLE_SONATA_ADMIN',
    );

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $rolesExcluder = array_combine($this->_excludeRoles, array_fill(1, count($this->_excludeRoles), true));
        $resolver->setDefaults(array(
            'choices' => function (Options $options, $parentChoices) use ($rolesExcluder) {
                return array_diff_key($parentChoices, $rolesExcluder);
            },
        ));
    }

}