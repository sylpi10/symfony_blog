<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * class CommentType
 * @package App\Form
 */
class CommentType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // parent::buildForm($builder, $options);
        $builder
        // ->add('author', TextType::class, [
        //     'label' => false,
        //     'attr' => [
        //         'placeholder' => 'Pseudo'
        //     ]
        // ])
        ->add('content', TextareaType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Your Comment'
            ]
        ])
       ;
    }

    /**
     * 
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", Comment::class);
    }

}
