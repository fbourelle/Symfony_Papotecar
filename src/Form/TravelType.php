<?php
/**
 * Created by Tanguy CHENIER.
 * Date: 23/04/2018
 * Time: 13:43
 */

namespace App\Form;


use App\Entity\Travel;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class TravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ["label" => "Titre du trajet"])
            ->add('starthour', DateTimeType::class, ["label" => "Heure de départ"])
            ->add('startaddress', TextType::class, ["label" => "D’où partez-vous (adresse)?"])
            ->add('startcity', TextType::class, ["label" => "D’où partez-vous (ville)?"])
            ->add('startzipcode', IntegerType::class, ["label" => "D’où partez-vous (Code postal)"])
            ->add('endaddress', TextType::class, ["label" => "Où allez - vous ? (adresse)"])
            ->add('endcity', TextType::class, ["label" => "Où allez - vous ? (ville)?"])
            ->add('endzipcode', IntegerType::class, ["label" => "Où allez - vous ? (Code postal)"])
            ->add('price', IntegerType::class, [
                "label" => "A quel prix ?"
            ])
            ->add('submit', SubmitType::class, ["label" => "continuer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }
}