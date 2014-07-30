<?php
namespace Acme\IpBundle\Security\Authorization\Voter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Doctrine\ORM\EntityManager;

use Acme\IpBundle\Entity\IpAddresses;

use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;


class ClientIpVoter implements VoterInterface
{
    private $container;

    private $em;

    private $blacklistedIp;

    //public function __construct(ContainerInterface $container, array $blacklistedIp = array())
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;

		$ips = $em->getRepository('AcmeIpBundle:IpAddresses')->findAll();

        $arr = array();

        foreach($ips as $ip){
        	$arr[] = $ip->getIp();
        }

        $this->blacklistedIp = $arr;

    }

    public function supportsAttribute($attribute)
    {
        // non verifichiamo l'attributo utente, quindi restituiamo true
        return true;

        /*
        if(in_array('ROLE_ADMIN', $attribute) || in_array('ROLE_SUPER_ADMIN', $attribute) || in_array('ROLE_SCIABOLA', $attribute)){
        	return true;
        }else{
        	return false;
        }
        */
    }

    public function supportsClass($class)
    {
        // il nostro votante supporta ogni tipo di classe token, quindi restituiamo true
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $request = $this->container->get('request');
        if ($this->supportsAttribute($attributes) && !in_array($request->getClientIp(), $this->blacklistedIp)) {
            echo "AAAAA";
            return VoterInterface::ACCESS_DENIED;
        }

        

        return VoterInterface::ACCESS_ABSTAIN;
    }
}