<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,['attr'=>['autocomplete'=>'off']])
            ->add('roles', ChoiceType::class,[
	            'choices'=>[
		            'Administrateur' => 'ROLE_ADMIN',
		            'Finance' => 'ROLE_FINANCE',
		            'EquipeNationale' => 'ROLE_NATION',
		            'EquipeRégionale' => 'ROLE_REGION',
		            'EquipeDistrict' => 'ROLE_DISTRICT',
		            'Utilisateur' => 'ROLE_USER'
	            ],
	            'multiple'=> true,
	            'expanded'=>true,
            ])
            ->add('password', PasswordType::class)
            ->add('email', EmailType::class)
            //->add('loginCount')
            //->add('lastConnection')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
