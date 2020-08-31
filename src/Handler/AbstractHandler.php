<?php

namespace App\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @return string
     */
    abstract protected function getFormType(): string;

    /**
     * @param $data
     * @return void
     */
    abstract protected function process($data): void;

    /**
     * @inheritDoc
     */
    function handle(Request $request, $data, array $options = []): bool
    {
        $this->form = $this->formFactory->create($this->getFormType(), $data)->handleRequest($this->request);

        if($this->form->isSubmitted() && $this->form->isValid()) {
            $this->process($data);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    function remove(CsrfTokenManagerInterface $tokenManager, $token, $data): bool
    {
        if($tokenManager->isTokenValid($token)) {
            $this->process($data);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function createView(): FormView
    {
        return $this->form->createView();
    }

    /**
     * @required
     * @param FormFactoryInterface  $formFactory
     * @return self
     */ 
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @required
     * @param RequestStack $request
     * @return self
     */ 
    public function setRequest(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();

        return $this;
    }

    /**
     * @return Request
     */ 
    public function getRequest()
    {
        return $this->request;
    }
}