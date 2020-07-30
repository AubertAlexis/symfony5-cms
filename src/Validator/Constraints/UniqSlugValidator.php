<?php

namespace App\Validator\Constraints;

use App\Entity\Page;
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

    /**
     * @param RequestStack $requestStack
     * @param PageRepository $pageRepository
     */
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

        $editPageId = $this->requestStack->getCurrentRequest()->attributes->get('id') != null ? (int) $this->requestStack->getCurrentRequest()->attributes->get('id') : null;

        $page = $this->requestStack->getCurrentRequest()->request->get('page');

        $pageExisting = (null === $value) ? $this->pageRepository->findOneBySlug((new Slugify())->slugify($page['title'])) : $this->pageRepository->findOneBySlug($page['slug']);
        
        foreach (Page::FORBIDDEN_SLUG as $forbiddenSlug) {
            if (strpos($value, $forbiddenSlug) !== false) {
                $this->context->buildViolation($constraint->forbiddenMessage)
                    ->addViolation();

                return;
            }
        }

        if (null !== $editPageId && null !== $pageExisting) {
            if ($editPageId != $pageExisting->getId()) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        } else {
            if (null !== $pageExisting) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
