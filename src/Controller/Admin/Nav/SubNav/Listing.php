<?php

namespace App\Controller\Admin\Nav\SubNav;

use App\Entity\NavLink;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Listing extends AbstractController
{
   /**
    * @Route("sous-menu/liens/{id}", name="admin_sub_nav_details", requirements={"id": "\d+"})
    * @param NavLink $navLink
    * @return Response
    * @throws NotFoundResourceException
    */
    public function listing(NavLink $navLink): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        $navLinkId = null;

        if (null !== $navLink->getSubNav()) {
            $navLinkName = "sub_nav";
            if (null !== $navLink->getSubNav()->getParent()) $navLinkId = $navLink->getSubNav()->getParent()->getId();
        } else {
            $navLinkName = "nav";
            if (null !== $navLink->getNav()) $navLinkId = $navLink->getNav()->getId();
        }

        if (null === $navLinkId) throw new NotFoundResourceException();

        $returnUrl = $this->generateUrl(sprintf("admin_%s_details", $navLinkName), [
            "id" => $navLinkId
        ]);
        
        return $this->render('admin/nav/subnav/index.html.twig', [
            'returnUrl' => $returnUrl,
            'navLink' => $navLink,
            'subNavs' => $navLink->getSubNavs()
        ]);
    }
}