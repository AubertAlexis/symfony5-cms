<?php

namespace App\Handler;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

interface HandlerInterface 
{
    /**
     * @param Request $request
     * @param mixed $data
     * @param array $options
     * @return bool
     */
    public function handle(Request $request, $data, array $options): bool;

    /**
     * @param CsrfTokenManagerInterface $tokenManager
     * @param string $tokenId
     * @param string $token
     * @param mixed $data
     * @return boolean
     */
    public function validateToken(CsrfTokenManagerInterface $tokenManager, string $tokenId, string $token, $data): bool;

    /**
     * @return FormView
     */
    public function createView(): FormView;

}