<?php

namespace App\Form;

use App\Entity\ArticleTemplate;
use App\Traits\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleTemplateType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, $this->setOptions('articleTemplate.%name%', [
                'required' => false
            ]))
            ->add('content', TextareaType::class, $this->setOptions('articleTemplate.%name%', [
                "required" => false
            ]))
            ;
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleTemplate::class,
            'translation_domain' => 'articleTemplate'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
