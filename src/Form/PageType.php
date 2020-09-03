<?php

namespace App\Form;

use App\Entity\Page;
use App\Entity\Template;
use App\Form\FormTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('enabled', CheckboxType::class, $this->setOptions('page.%name%', [
                "required" => false,
                "help" => "page.form.page.helpEnabled"
            ]))
            
            ->add('submit', SubmitType::class, $this->setOptions('page.%name%'))
        ;

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {$this->manageElements($event);});
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'translation_domain' => 'page'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }

    protected function manageElements(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $templateName = (null !== $data->getTemplate()) ? $data->getTemplate()->getKeyname() : Template::INTERNAL;

        if ($data->getId() === null) {
            $form 
                ->add('template', EntityType::class, $this->setOptions('page.%name%', [
                    'class' => Template::class,
                    'choice_label' => 'title',
                ]));
        } else {

            if ($this->isModuleEnabled("seo")) $form->add('seo', SeoType::class, $this->setOptions('homePage.%name%'));
                
            if ($templateName === Template::INTERNAL) {
                $form
                    ->add("internalTemplate", InternalTemplateType::class, $this->setOptions(false, [
                        "data" => $data->getInternalTemplate()
                    ]));
            } else if ($templateName === Template::ARTICLE) {
                $form
                    ->add("articleTemplate", ArticleTemplateType::class, $this->setOptions(false, [
                        "data" => $data->getArticleTemplate()
                    ]));
            }
        }
    }
}
