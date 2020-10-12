<?php

namespace App\Controller\Admin\Profil;

use App\Handler\ProfilHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Update extends AbstractController
{
    /**
    * @Route("admin/profil", name="admin_profile_edit")
    * @param Request $request
    * @param ProfilHandler $profilHandler
    * @param TranslatorInterface $translator
    * @return Response
    */
    public function update(Request $request, ProfilHandler $profilHandler, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_PROFIL", $user);

        if($profilHandler->handle($request, $user)) {
            $this->addFlash("success", $translator->trans("alert.profile.success.edit", [], "alert"));
        }

        return $this->render('admin/profile/edit.html.twig', [
            "user" => $user,
            "form" => $profilHandler->createView()
        ]);
    }

}