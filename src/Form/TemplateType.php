<?php

namespace App\Form;

use App\Entity\Template;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->setOptions("template.%name%"))
            ->add('keyname', TextType::class, $this->setOptions("template.%name%"))

            ->add('submit', SubmitType::class, $this->setOptions('template.%name%'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
            'translation_domain' => 'template'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
