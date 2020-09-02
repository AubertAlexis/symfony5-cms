<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\User;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', ChoiceType::class, $this->setOptions('locale.%name%', [
                'choices' => [
                    "user.form.locale.localeChoices.french" => "fr",
                    "user.form.locale.localeChoices.english" => "en"
                ]
            ]))

            ->add('submit', SubmitType::class, $this->setOptions('locale.%name%'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'user'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
