<?php

namespace App\Controller;

use App\Handler\ProfilHandler;
use App\Handler\PasswordChangeHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/")
 */
class AdminProfileController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
    * @Route("profil", name="admin_profile_edit")
    * @param Request $request
    * @param ProfilHandler $profilHandler
    * @return Response
    */
    public function edit(Request $request, ProfilHandler $profilHandler): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_PROFIL", $user);

        if($profilHandler->handle($request, $user)) {
            $this->addFlash("success", $this->translator->trans("alert.profile.success.edit", [], "alert"));
        }

        return $this->render('admin/profile/edit.html.twig', [
            "user" => $user,
            "form" => $profilHandler->createView()
        ]);
    }

    /**
     * @Route("profil/mot-de-passe", name="admin_profile_password")
     * @param Request $request
     * @param PasswordChangeHandler $passwordChangeHandler
     * @return Response
     */
    public function passwordChange(Request $request, PasswordChangeHandler $passwordChangeHandler): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_PROFIL", $user);
        
        if($passwordChangeHandler->handle($request, $user)) {
            $this->addFlash("success", $this->translator->trans("alert.profile.success.passwordReset", [], "alert"));
            return $this->redirectToRoute("admin_profile_edit");
        }

        return $this->render('admin/profile/password.html.twig', [
            "user" => $user,
            "form" => $passwordChangeHandler->createView()
        ]);
    }
}