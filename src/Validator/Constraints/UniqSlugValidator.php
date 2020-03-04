<?php

namespace App\Validator\Constraints;

use App\Repository\PageRepository;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqSlugValidator extends ConstraintValidator
{
    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack, PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->requestStack = $requestStack;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqSlug) {
            throw new UnexpectedTypeException($constraint, UniqSlug::class);
        }

        if (!is_string($value) && null != $value) {
            throw new UnexpectedValueException($value, 'string');
        }

        $page = $this->requestStack->getCurrentRequest()->get('page');

        $pageExisting = (null === $value) ? $this->pageRepository->findOneBySlug((new Slugify())->slugify($page->getTitle())) : $this->pageRepository->findOneBySlug($page->getSlug());
        
        if (null !== $pageExisting && $page->getId() != $pageExisting->getId()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
        
    }
}
