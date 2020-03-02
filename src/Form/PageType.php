<?php

namespace App\Form;

use App\Entity\Page;
use App\Traits\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->setOptions('page.%name%'))
            ->add('slug', TextType::class, $this->setOptions('page.%name%', [
                "required" => false,
                "help" => "page.form.page.helpSlug"
            ]))
            ->add('content', TextareaType::class, $this->setOptions('page.%name%'))
            ->add('submit', SubmitType::class, $this->setOptions('page.%name%'))
            // ->add('createdAt')
            // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'translation_domain' => 'page'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
