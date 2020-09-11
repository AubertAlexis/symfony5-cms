<?php

namespace App\Controller\Front\Contact;

use App\Entity\Contact;
use App\Handler\ContactHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class Send extends AbstractController
{
    /**
     * @Route("contact/envoie", name="contact_send")
     * 
     * @param Request $request
     * @return Response
     */
    public function send(Request $request, ContactHandler $contactHandler, TranslatorInterface $translator): Response
    {
        $contact = new Contact();
        $contactForm = $request->request->get("contact");

        $contact
            ->setFirstName($contactForm["firstName"])
            ->setLastName($contactForm["lastName"])
            ->setEmail($contactForm["email"])
            ->setMessage($contactForm["message"])
        ;

        $contactHandler->handle($request, $contact);

        $this->addFlash("success", $translator->trans("alert.contact.success.send", [], "alert"));

        return $this->redirectToRoute("home_index");
        // return $this->render("page/{$templateName}.html.twig", array_filter(compact('page', 'template', 'articles')));
    }
}
