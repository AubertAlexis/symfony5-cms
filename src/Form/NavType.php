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

class NavType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                "data" => true,
                "help" => "nav.form.nav.helpEnabled"
            ]))
            ->add('submit', SubmitType::class, $this->setOptions('nav.%name%'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nav::class,
            'translation_domain' => 'nav'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
