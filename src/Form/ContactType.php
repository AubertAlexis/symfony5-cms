<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\FormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    use FormTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ["label" => 'Prénom'])
            ->add('lastName', TextType::class, ["label" => 'Nom'])
            ->add('email', EmailType::class, ["label" => 'Email'])
            ->add('message', TextareaType::class, ["label" => 'Message'])
            
            ->add('submit', SubmitType::class, ["label" => 'Envoyer'])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
