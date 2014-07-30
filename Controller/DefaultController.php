<?php

namespace Acme\IpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Acme\IpBundle\Entity\ipAddresses;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeIpBundle:Default:index.html.twig', array('name' => $name));
    }

    public function listAction(Request $request)
    {

	    $em = $this->getDoctrine()->getManager();

		$ips = $em->getRepository('AcmeIpBundle:IpAddresses')->findAll();


        $ip = new ipAddresses();

        $form = $this->createFormBuilder($ip)
            ->add('description', 'text')
            ->add('ip', 'text')
            ->add('save', 'submit')
            ->getForm();

	    $form->handleRequest($request);

	    if ($form->isValid()) {

	        $em = $this->getDoctrine()->getManager();

		$em->persist($ip);	
		$em->flush();	    

		return $this->redirect($this->generateUrl('acme_ip_list'));
	    }

        return $this->render('AcmeIpBundle:Default:ipList.html.twig', array(
        	'form' => $form->createView(), 'ips' => $ips
        ));

    }
}
