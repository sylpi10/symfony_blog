<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotNull;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('author', TextType::class, [
            //     'label' => 'Author\'s Name'
            // ])
            ->add('title', TextType::class, [
                'label' => 'Post Title:'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Text:'
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(),
                    new NotNull([
                        // validation group -> so we don't have to add a pic on update
                        "groups" => "create"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
