<?php

namespace App\Handler;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

interface HandlerInterface 
{
    /**
     * @param Request $request
     * @param mixed $data
     * @return bool
     */
    public function handle(Request $request, $data, array $options): bool;

    /**
     * @return FormView
     */
    public function createView(): FormView;

}