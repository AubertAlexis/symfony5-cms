<?php

namespace App\Form;

use App\Entity\Nav;
use App\Traits\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class NavType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->isEnabled = $options['isEnabled'];

        $builder
            ->add('keyname', TextType::class, $this->setOptions('nav.%name%', [
                "help" => "nav.form.nav.helpKeyname"
            ]))
            ->add('title', TextType::class, $this->setOptions('nav.%name%'))
            ->add('main', CheckboxType::class, $this->setOptions('nav.%name%', [
                "required" => false,
                "help" => "nav.form.nav.helpMain"
            ]))
            ->add('enabled', CheckboxType::class, $this->setOptions('nav.%name%', [
                "required" => false,
                "data" => $this->isEnabled,
                "help" => "nav.form.nav.helpEnabled"
            ]))

            ->add('navLinks', CollectionType::class, $this->setOptions(false , [
                "entry_type" => NavLinkType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                "by_reference" => false
            ]))

            ->add('submit', SubmitType::class, $this->setOptions('nav.%name%'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nav::class,
            'translation_domain' => 'nav',
            'isEnabled' => null
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
