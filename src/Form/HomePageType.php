<?php

namespace App\Form;

use App\Entity\HomePage;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomePageType extends AbstractType
{

    use FormTrait;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->setOptions('homePage.%name%', [
                "required" => false
            ]))
            ->add('aboutTitle', TextType::class, $this->setOptions('homePage.%name%', [
                "required" => false
            ]))
            ->add('aboutImageFile', FileType::class, $this->setOptions('homePage.%name%', [
                'required' => false
            ]))
            ->add('aboutText', TextareaType::class, $this->setOptions('homePage.%name%', [
                "required" => false
            ]))

            ->add('submit', SubmitType::class, $this->setOptions('homePage.%name%'))
        ;

        if ($this->isModuleEnabled("seo")) $builder->add('seo', SeoType::class, $this->setOptions('homePage.%name%'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomePage::class,
            'translation_domain' => 'homePage'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
