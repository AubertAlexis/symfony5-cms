<?php

namespace App\DataFixtures;

use App\Entity\HomePage;
use App\Entity\Module;
use App\Entity\Seo;
use App\Entity\Template;
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
        $admin = new User();

        $admin->setFirstName("Admin")
            ->setLastName("Istrateur")
            ->setEmail("admin@admin.fr")
            ->setUsername("admin")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->encodePassword($admin, "admin"))
            ->setLocale($this->container->getParameter('locale'));

        $dev = new User();

        $dev->setFirstName("Deve")
            ->setLastName("Loper")
            ->setEmail("dev@dev.fr")
            ->setUsername("dev")
            ->setRoles(["ROLE_DEV"])
            ->setPassword($this->encoder->encodePassword($dev, "dev"))
            ->setLocale($this->container->getParameter('locale'));

        $internalTemplate = new Template();

        $internalTemplate->setTitle("Page interne")
            ->setKeyname("internal");

        $articleTemplate = new Template();

        $articleTemplate->setTitle("Page article")
            ->setKeyname("article");

        $seoModule = new Module();

        $seoModule->setTitle("SEO")
            ->setKeyname("seo")
            ->setEnabled(true);

        $homePage = new HomePage();
        $seo = new Seo();

        $seo
            ->setHomePage($homePage);

        $manager->persist($admin);
        $manager->persist($dev);
        $manager->persist($internalTemplate);
        $manager->persist($articleTemplate);
        $manager->persist($homePage);
        $manager->persist($seo);
        $manager->persist($seoModule);

        $manager->flush();
    }
}
