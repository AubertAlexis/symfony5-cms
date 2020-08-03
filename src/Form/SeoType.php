<?php

namespace App\Form;

use App\Entity\Seo;
use App\Traits\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeoType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('metaTitle', TextType::class, $this->setOptions('seo.%name%', [
                "required" => false
            ]))
            ->add('metaDescription', TextType::class, $this->setOptions('seo.%name%', [
                "required" => false
            ]))
            ->add('metaKeywords', TextType::class, $this->setOptions('seo.%name%', [
                "required" => false
            ]))
            ->add('noIndex', CheckboxType::class, $this->setOptions('seo.%name%', [
                "required" => false,
                "help" => "seo.form.seo.helpNoIndex"
            ]))
            ->add('noFollow', CheckboxType::class, $this->setOptions('seo.%name%', [
                "required" => false,
                "help" => "seo.form.seo.helpNoFollow"
            ]))
            ->add('hideOnSitemap', CheckboxType::class, $this->setOptions('seo.%name%', [
                "required" => false,
                "help" => "seo.form.seo.helpHideOnSitemap"
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seo::class,
            'translation_domain' => 'seo'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
