<?php
namespace App\DataFixtures;
use App\Entity\Conference;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\ConferenceRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
class AppFixtures extends Fixture
{
    private $userRepository;
    private $conferenceRepository;

    public function __construct(UserRepository $userRepository, ConferenceRepository $conferenceRepository,VoteRepository $voteRepository)
    {
        $this->userRepository = $userRepository;
        $this->conferenceRepository = $conferenceRepository;
        $this->voteRepository = $voteRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->regexify('[A-Z0-9][A-Z0-9.-]+\[A-Z]{2,9}'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        for ($i = 0; $i < 15; $i++) {
            $conf = new Conference();
            $conf->setDescription($faker->text(200));
            $conf->setTitle($faker->text(50));
            $conf->setUser($user);
            $manager->persist($conf);

        }
        $manager->flush();
    }
}