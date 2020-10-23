<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($i=1; $i <= 10; $i++) { 
            $user = new User();
            $user->setEmail(
                sprintf("email_%d@email.com", $i)
            );
            $user->setPseudo($faker->name());
            $user->setPassword($this->encoder->encodePassword($user, "password"));
            $manager->persist($user);
            $this->setReference(sprintf("user-%d", $i), $user);
        }
        $manager->flush();
    }

}