<?php

namespace App\Form;

use App\Entity\SubNav;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubNavType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('navLinks', CollectionType::class, $this->setOptions(false , [
                "entry_type" => NavLinkType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ]))

            ->add('submit', SubmitType::class, $this->setOptions('subNav.%name%'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubNav::class,
            'translation_domain' => 'nav'
        ]);
    
        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
