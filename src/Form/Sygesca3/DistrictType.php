<?php

namespace App\Form\Sygesca3;

use App\Entity\Sygesca3\District;
use App\Entity\Sygesca3\Region;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            //->add('doyenne')
            //->add('slug')
            //->add('publiePar')
            //->add('modifiePar')
            //->add('publieLe')
            //->add('modifieLe')
            ->add('region', EntityType::class,[
	            'attr'=>['class' => 'form-control select2'],
	            'class' => Region::class,
	            'query_builder' => function(EntityRepository $er){
		            return $er->liste();
	            },
	            'choice_label' => 'nom',
	            'label' => 'Region'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => District::class,
        ]);
    }
}
