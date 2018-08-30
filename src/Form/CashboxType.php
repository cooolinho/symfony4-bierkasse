<?php

namespace App\Form;

use App\Entity\Cashbox;
use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CashboxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('players', EntityType::class, array(
                // looks for choices from this entity
                'class' => Player::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'fullname',

                // used to render a select box, check boxes or radios
                 'multiple' => true,
                 'expanded' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cashbox::class,
        ]);
    }
}
