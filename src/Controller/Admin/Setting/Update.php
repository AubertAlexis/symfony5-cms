<?php

namespace App\Controller\Admin\Setting;

use App\Handler\SettingHandler;
use App\Repository\ModuleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Update extends AbstractController
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
     * @Route("admin/parametres", name="admin_setting_edit")
     * @param Request $request
     * @param ModuleRepository $moduleRepository
     * @param SettingHandler $settingHandler
     * @return Response
     */
    public function update(Request $request, ModuleRepository $moduleRepository, SettingHandler $settingHandler): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted("USER_ADMIN", $user);

        if ($settingHandler->handle($request, $user)) {
            $this->addFlash("success", $this->translator->trans("alert.setting.success.localeChange", [], "alert", $user->getLocale()));
            return $this->redirectToRoute("admin_dashboard_index");
        }

        return $this->render('admin/setting/edit.html.twig', [
            'modules' => $moduleRepository->findAll(),
            'localeForm' => $settingHandler->createView()
        ]);
    }

}