<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * @var ContainerInterface $container
     */
    private $container;

    public function __construct(UserPasswordEncoderInterface $encoder, ContainerInterface $container)
    {
        $this->encoder = $encoder;
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setFirstName("Admin");
        $user->setLastName("Istrateur");
        $user->setEmail("admin@admin.fr");
        $user->setUsername("admin");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->encoder->encodePassword($user, "admin"));
        $user->setLocale($this->container->getParameter('locale'));

        $manager->persist($user);

        $manager->flush();
    }
}
