<?php

namespace App\Form;

use App\Entity\Offense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'offensant' => 'Offensant',
                    'raciste' => 'Raciste',
                    'haineux' => 'Haineux'
                ),
                    'required' => true,
                    'label' => 'Type'

            ))
            ->add('button', SubmitType::class, array(
                'label' => 'Valider'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offense::class,
        ]);
    }
}
