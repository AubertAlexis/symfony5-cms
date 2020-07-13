<?php

namespace App\Form;

use App\Traits\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUserType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, $this->setOptions("resetPassword.%name%", [
                "mapped" => false
            ]))
            ->add('newPassword', RepeatedType::class, $this->setOptions("", [
                "mapped" => false,
                "type" => PasswordType::class,
                "invalid_message" => "Les mots de passe doivent être identiques.",
                "required" => true,
                "first_options"  => ["label_format" => "user.form.resetPassword.newPassword.%name%"],
                "second_options" => ["label_format" => "user.form.resetPassword.newPassword.%name%"],
            ]))
            ->add('submit', SubmitType::class, $this->setOptions("resetPassword.%name%"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'user'
        ]);

        $this->defaultOptions($resolver->resolve()["translation_domain"]);
    }
}
