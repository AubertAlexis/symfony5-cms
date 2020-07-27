<?php

namespace App\Form;

use App\Entity\NavLink;
use App\Entity\Page;
use App\Traits\FormTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavLinkType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('internal', ChoiceType::class, $this->setOptions('navLink.%name%', [
                'choices' => [
                    'nav.form.navLink.yes' => true,
                    'nav.form.navLink.no' => false
                ],
                'attr' => [
                    'class' => 'short-choices'
                ]
            ]))
            ->add('title', TextType::class, $this->setOptions('navLink.%name%'), [
                'required' => false,
                'help' => 'navLink.helpTitle'
            ])
            ->add('link', TextType::class, $this->setOptions('navLink.%name%', [
                'required' => false
            ]))
            ->add('position', HiddenType::class)
            ->add('page', EntityType::class, $this->setOptions('navLink.%name%', [
                'required' => false,
                'class' => Page::class,
                'choice_label' => 'title'
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NavLink::class,
            'translation_domain' => 'nav'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
