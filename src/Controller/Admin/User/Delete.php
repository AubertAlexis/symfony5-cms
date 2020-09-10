<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Handler\UserHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class Delete extends AbstractController
{
    /**
     * @Route("admin/utilisateurs/{id}/suppression", name="admin_user_delete", requirements={"id": "\d+"}, methods="POST")
     * @param Request $request
     * @param User $user
     * @param UserHandler $userHandler
     * @param CsrfTokenManagerInterface $tokenManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(
        Request $request, 
        User $user, 
        UserHandler $userHandler, 
        CsrfTokenManagerInterface $tokenManager,
        TranslatorInterface $translator
    ): Response 
    {
        $this->denyAccessUnlessGranted("USER_DELETE");

        if ($userHandler->validateToken($tokenManager, "delete-user", $request->request->get('token'), $user)) {
            $this->addFlash("success", $translator->trans("alert.user.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_user_index");
        }

        $this->addFlash("danger", $translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_user_index");
    }
}