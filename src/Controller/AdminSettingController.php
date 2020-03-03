<?php

namespace App\Controller;

use App\Form\LocaleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/")
 */
class AdminSettingController extends AbstractController
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
     * @Route("parametres", name="admin_setting_edit")
     * 
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_ADMIN", $user);

        $localeForm = $this->createForm(LocaleType::class, $user);
        $localeForm->handleRequest($request);

        if ($localeForm->isSubmitted() && $localeForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("warning", "<span class='font-weight-bold mr-2'><i class='fas fa-exclamation-triangle'></i></span>{$this->translator->trans("alert.setting.warning.warning", [], "alert")} </br>{$this->translator->trans("alert.setting.warning.localeChange", [], "alert")}");
        }

        return $this->render('admin/setting/edit.html.twig', [
            'localeForm' => $localeForm->createView()
        ]);
    }
}
