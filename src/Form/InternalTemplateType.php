<?php

namespace App\Form;

use App\Entity\InternalTemplate;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InternalTemplateType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, $this->setOptions('internalTemplate.%name%', [
                "required" => false
            ]))
            ;
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InternalTemplate::class,
            'translation_domain' => 'internalTemplate'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
