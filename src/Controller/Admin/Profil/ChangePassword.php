<?php

namespace App\Controller\Admin\Profil;

use App\Handler\PasswordChangeHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChangePassword extends AbstractController
{
    /**
     * @Route("admin/profil/mot-de-passe", name="admin_profile_password")
     * @param Request $request
     * @param PasswordChangeHandler $passwordChangeHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function changePassword(Request $request, PasswordChangeHandler $passwordChangeHandler, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_PROFIL", $user);
        
        if($passwordChangeHandler->handle($request, $user)) {
            $this->addFlash("success", $translator->trans("alert.profile.success.passwordReset", [], "alert"));
            return $this->redirectToRoute("admin_profile_edit");
        }

        return $this->render('admin/profile/password.html.twig', [
            "user" => $user,
            "form" => $passwordChangeHandler->createView()
        ]);
    }

}