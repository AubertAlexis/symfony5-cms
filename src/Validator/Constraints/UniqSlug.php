<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqSlug extends Constraint
{
    /**
     * @var string
     */
    public $message = "";
}
