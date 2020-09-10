<?php

namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\RoleTransformer;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    use FormTrait;

    /**
     * @var RoleTransformer
     */
    private $roleTransformer;

    public function __construct(RoleTransformer $roleTransformer)
    {
        $this->roleTransformer = $roleTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->setOptions('user.%name%'))
            ->add('lastName', TextType::class, $this->setOptions('user.%name%'))
            ->add('email', EmailType::class, $this->setOptions('user.%name%'))
            ->add('username', TextType::class, $this->setOptions('user.%name%'))
            ->add('roles', ChoiceType::class, $this->setOptions('user.%name%', [
                "choices" => [
                    "Administrateur" => User::ADMIN,
                    "Développeur" => User::DEV,
                    "Utilisateur" => User::USER
                ]
            ]))

            ->add('submit', SubmitType::class, $this->setOptions('user.%name%'));

            $builder->get("roles")->addModelTransformer($this->roleTransformer);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();
        
                if (!$user || null === $user->getId()) {
                    $form->add('password', PasswordType::class, $this->setOptions('user.%name%'));
                }
            });
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
