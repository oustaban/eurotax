<?php
namespace Application\Sonata\ClientBundle\Security\Authorization\Voter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AllClientsVoter implements VoterInterface
{
    public function __construct(ContainerInterface $container)
    {
        $this->container     = $container;
    }

   /**
    * {@InheritDoc}
    */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return $attribute === 'ROLE_EDIT_ALL_CLIENTS';
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container->get('request');

        foreach ($attributes as $attribute) {
            if ($this->supportsAttribute($attribute)) {
                return ($request->getPathInfo() != '/' || $request->cookies->get('show_all_clients')) ? self::ACCESS_GRANTED : self::ACCESS_DENIED;
            }
        }

        return self::ACCESS_ABSTAIN;
    }
}