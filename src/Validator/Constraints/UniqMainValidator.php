<?php

namespace App\Validator\Constraints;

use App\Repository\NavRepository;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqMainValidator extends ConstraintValidator
{
    /**
     * @var NavRepository
     */
    private $navRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     * @param NavRepository $navRepository
     */
    public function __construct(RequestStack $requestStack, NavRepository $navRepository)
    {
        $this->navRepository = $navRepository;
        $this->requestStack = $requestStack;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqMain) {
            throw new UnexpectedTypeException($constraint, UniqMain::class);
        }
        
        if (!is_bool($value) && null != $value) {
            throw new UnexpectedValueException($value, 'boolean');
        }

        $editNavId = $this->requestStack->getCurrentRequest()->attributes->get('id') != null ? (int) $this->requestStack->getCurrentRequest()->attributes->get('id') : null;
        
        $nav = $this->requestStack->getCurrentRequest()->request->get('nav');
        
        if (true === $value) {
            $nav = $this->navRepository->findOneByMain($value);
            
            if (null !== $nav && $nav->getId() !== $editNavId) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        } 
        
    }
}
