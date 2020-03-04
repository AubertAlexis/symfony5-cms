<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Annotation
 */
class UniqSlug extends Constraint
{
    /**
     * @var string
     */
    public $message = "Le lien est déjà utilisé pour une autre page, vérifier le lien ou le titre.";
}
