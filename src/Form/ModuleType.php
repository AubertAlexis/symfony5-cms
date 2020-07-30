<?php

namespace App\Form;

use App\Entity\Module;
use App\Traits\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->setOptions("module.%name%"))
            ->add('keyname', TextType::class, $this->setOptions("module.%name%", [
                "help" => "module.form.module.helpKeyname"
            ]))
            ->add('enabled', CheckboxType::class, $this->setOptions('module.%name%', [
                "required" => false,
                "help" => "module.form.module.helpEnabled"
            ]))

            ->add('submit', SubmitType::class, $this->setOptions('module.%name%'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
            'translation_domain' => 'module'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
