<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * 
     * @param Request $request
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

            $this->addFlash("success", $this->translator->trans("alert.profile.success.edit", [], "alert"));
        }

        return $this->render('admin/profile/edit.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("profil/mot-de-passe", name="admin_profile_password")
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
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
                $this->addFlash("success", $this->translator->trans("alert.profile.success.passwordReset", [], "alert"));

                return $this->redirectToRoute("admin_profile_edit");
            }

            $this->addFlash("danger", $this->translator->trans("alert.profile.danger.passwordReset", [], "alert"));
        }

        return $this->render('admin/profile/password.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }
}