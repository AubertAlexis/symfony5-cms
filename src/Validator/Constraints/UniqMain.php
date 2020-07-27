<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqMain extends Constraint
{
    /**
     * @var string
     */
    public $message = "";
}
