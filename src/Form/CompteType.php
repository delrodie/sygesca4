<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Sygesca3\Region;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('statut')
            ->add('region', EntityType::class,[
	            'attr'=>['class' => 'form-control select2'],
	            'class' => Region::class,
	            'query_builder' => function(EntityRepository $er){
		            return $er->listeActive();
	            },
	            'choice_label' => 'nom',
	            'label' => 'Region'
            ])
            ->add('user', EntityType::class,[
	            'attr'=>['class' => 'form-control select2'],
	            'class' => User::class,
	            'query_builder' => function(EntityRepository $er){
		            return $er->listeRegion();
	            },
	            'choice_label' => 'username',
	            'label' => 'Utilisateur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
