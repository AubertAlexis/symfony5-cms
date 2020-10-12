<?php

namespace App\Services;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class Mailer {

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Swift_Mailer $mailer, Environment $templating, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->container = $container;
    }

    /**
     * Send a message
     *
     * @param Swift_Message $message
     */
    public function send(Swift_Message $message)
    {
        $this->mailer->send($message);
    }

    /**
     * Make a message
     *
     * @param string $subject
     * @param string $to
     * @param string $viewPath
     * @param array $options
     * @return Swift_Message
     */
    public function makeMessage(string $subject, string $to, string $viewPath, array $options): Swift_Message
    {
        return (new Swift_Message($subject))
        ->setFrom($this->container->getParameter("app.email"))
        ->setTo($to)
        ->setBody(
            $this->templating->render(
                $viewPath,
                $options
            ),
            'text/html'
        );
    }

}
