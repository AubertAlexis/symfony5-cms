<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Handler\UserHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Update extends AbstractController
{
    /**
     * @Route("/admin/utilisateurs/{id}", name="admin_user_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param User $user
     * @param UserHandler $userHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function update(Request $request, User $user, UserHandler $userHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("USER_EDIT");

        if ($userHandler->handle($request, $user)) {
            $this->addFlash("success", $translator->trans("alert.user.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render('admin/user/edit.html.twig', [
            "page" => $user,
            'form' => $userHandler->createView()
        ]);
    }
}