<?php

namespace App\Controller;

use App\Form\LocaleType;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
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
    public function edit(Request $request, ModuleRepository $moduleRepository): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_ADMIN", $user);

        $localeForm = $this->createForm(LocaleType::class, $user);
        $localeForm->handleRequest($request);

        if ($localeForm->isSubmitted() && $localeForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash("success", "{$this->translator->trans("alert.setting.success.localeChange", [], "alert", $user->getLocale())}");

            return $this->redirectToRoute("admin_dashboard_index");
        }

        return $this->render('admin/setting/edit.html.twig', [
            'modules' => $moduleRepository->findAll(),
            'localeForm' => $localeForm->createView()
        ]);
    }
}
