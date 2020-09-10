<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Handler\UserHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Create extends AbstractController
{
    /**
     * @Route("admin/utilisateurs/nouveau", name="admin_user_add")
     * @param Request $request
     * @param UserHandler $userHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function create(Request $request, UserHandler $userHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("USER_ADD");

        $user = new User();

        if ($userHandler->handle($request, $user)) {
            $this->addFlash("success", $translator->trans("alert.user.success.add", [], "alert"));
            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render('admin/user/add.html.twig', [
            'form' => $userHandler->createView()
        ]);
    }
}