<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * Password Encoder
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {
        $Faker = new Factory();
        $fakeData = $Faker->create('fr_FR');
        for($i = 0; $i < 10; $i++) {
            $Category = new Category();
            $Category->setTitle($fakeData->sentence(mt_rand(3, 4)));
            $Category->setDescription($fakeData->realText(200,2));
            $manager->persist($Category);

            for ($j = 0; $j < mt_rand(15, 30); $j++) {
                $Article = new Article();
                $Article->setTitle($fakeData->sentence(mt_rand(3, 4)));
                $Article->setIntro($fakeData->paragraph(mt_rand(3, 4), false));
                $Article->setContent($fakeData->realText(500,2));
                $Article->setCover($fakeData->imageUrl(640, 480, 'Cover'));
                $Article->setCategory($Category);
                $manager->persist($Article);
            }
        }
        $manager->flush();
    }
}
