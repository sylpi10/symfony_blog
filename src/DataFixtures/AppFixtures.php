<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 60; $i++) { 
        $post = new Post();
        $post->setTitle($faker->sentence($nbWords = 2, $variableNbWords = true));
        $post->setContent($faker->text());
        $post->setAuthor($faker->name());
        $post->setCreatedAt(new \DateTime());
        
        $manager->persist($post);
        
        for ($j=0; $j < rand(4,15); $j++) { 
            $com = new Comment();
            $com->setAuthor($faker->name());
            $com->setContent($faker->text());
            $com->setPostedAt(new \DateTime);
            $com->setPost($post);
        
            $manager->persist($com);
        }
        }
        $manager->flush();
    }
}
