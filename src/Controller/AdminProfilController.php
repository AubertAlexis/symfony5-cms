<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("admin/")
 */
class AdminProfilController extends AbstractController
{
    /**
     * @Route("profil", name="admin_profil_edit")
     * 
     * @return Response
     */
    public function edit(Request $request) : Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_PROFIL", $user);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "Vos informations ont bien été modifiées.");
        }

        return $this->render('admin/profil/edit.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("profil/mot-de-passe", name="admin_profil_password")
     * 
     * @return Response
     */
    public function passwordReset(Request $request, UserPasswordEncoderInterface $encoder) : Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_PROFIL", $user);

        $form = $this->createForm(PasswordUserType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get("password_user");

            if($encoder->isPasswordValid($user, $data["password"]) && ($data["newPassword"]["first"] === $data["newPassword"]["second"])) {
                $user->setPassword($encoder->encodePassword($user, $data["newPassword"]["first"]));

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Votre mot de passe a bien été mis à jour.");

                return $this->redirectToRoute("admin_profil_edit");
            }

            $this->addFlash("danger", "Le mot de passe actuel est erroné, merci de le corriger.");
        }

        return $this->render('admin/profil/password.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }
}