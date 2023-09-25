<?php



namespace App\DataFixtures;

use App\Entity\Favorite;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Garden;
use DateTimeImmutable;
use App\Entity\Picture;
use Ottaviano\Faker\Gravatar;
use App\Service\UnsplashApiService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\AppProvider;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Tag;
use App\Service\NominatimApiService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{


    private $unsplashApi;
    private $nominatimApiService;
    private $userPasswordHasher;

    public function __construct(UnsplashApiService $unsplashApi, NominatimApiService $nominatimApiService, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->unsplashApi = $unsplashApi;
        $this->nominatimApiService = $nominatimApiService;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        // utilisation de library gravatar pour les avatars
        $faker->addProvider(new Gravatar($faker));
        // utilisation de notre provider pour les roles
        $faker->addProvider(new AppProvider());

        


        //! USER

        // Je crée un tableau vide
        $userList = [];
        for ($i = 0; $i < 7; $i++) {
            // j'utilise mon provider pour récupérer un $faker->
            $role = $faker->role();
            // J'instancie un nouvel objet user
            $user = new User();
            $user->setUsername($faker->userName());
            $user->setPassword($this->userPasswordHasher->hashPassword($user,$faker->password(8, 20)) );
            $user->setEmail($faker->email());
            $user->setPhone($faker->phoneNumber());
            $user->setRoles($role);
            $user->setAvatar($faker->gravatarUrl());
            $user->setCreatedAt(new DateTimeImmutable($faker->date()));

            $userList[] = $user;

            $manager->persist($user);

        }
        //! USER ADMIN
        // Ajout en dur de l'admin O'potager

        $user = new User();
        $user->setUsername("admin");
        $user->setPassword($this->userPasswordHasher->hashPassword($user,"admin") );
        $user->setEmail("opotager@gmail.com");
        $user->setPhone("0123456789");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setAvatar("https://us.123rf.com/450wm/lerarelart/lerarelart2001/lerarelart200100084/137333196-l-illustration-vectorielle-des-oignons-et-des-carottes-sont-des-amis-l%C3%A9gumes-dr%C3%B4les-de-personnages.jpg?ver=6");
        $user->setCreatedAt(new DateTimeImmutable($faker->date()));

        $manager->persist($user);

         //! USER User
        // Ajout en dur de l'user O'potager

        $user = new User();
        $user->setUsername("user");
        $user->setPassword($this->userPasswordHasher->hashPassword($user,"user") );
        $user->setEmail("opotageruser@gmail.com");
        $user->setPhone("0123456789");
        $user->setRoles(["ROLE_USER"]);
        $user->setAvatar("https://us.123rf.com/450wm/lerarelart/lerarelart2001/lerarelart200100084/137333196-l-illustration-vectorielle-des-oignons-et-des-carottes-sont-des-amis-l%C3%A9gumes-dr%C3%B4les-de-personnages.jpg?ver=6");
        $user->setCreatedAt(new DateTimeImmutable($faker->date()));

        $manager->persist($user);
        // ! Garden

        // Je crée un tableau vide
        $gardenList = [];
        for ($i = 0; $i < 100; $i++) {
            $city = $faker->cities();
            // J'instancie un nouvel objet garden
            $garden = new Garden();
            $garden->setTitle($faker->text(100));
            $garden->setDescription($faker->text(240));
            $garden->setAddress($faker->streetAddress());
            $garden->setPostalCode($faker->numberBetween(1000, 95000));
            $garden->setCity($city);
            $garden->setWater($faker->boolean());
            $garden->setTool($faker->boolean());
            $garden->setShed($faker->boolean());
            $garden->setCultivation($faker->boolean());
            $garden->setState($faker->text(10));
            $garden->setSurface($faker->numberBetween(1, 1000));
            $garden->setPhoneAccess($faker->boolean());
            $garden->setCreatedAt(new DateTimeImmutable($faker->date()));
            $garden->setUser($userList[array_rand($userList)]);
            $garden->setLat(($this->nominatimApiService->getCoordinates($city))[ "lat" ]);
            $garden->setLon(($this->nominatimApiService->getCoordinates($city))[ "lon" ]);
            $gardenList[] = $garden;

            $manager->persist($garden);
        }

        //! Favorite

        for ($i = 0; $i < 100; $i++) {
            $favorite = new Favorite();
            $favorite->setUser($userList[array_rand($userList)]);
            $favorite->setGarden($gardenList[array_rand($gardenList)]);

            $manager->persist($favorite);
        }
        //! Tags

        $tagList = [];

        for ($i = 1; $i < 10; $i++) {

            $tag = new Tag();
            $tag->setName($faker->unique()->word());

            $tagList[] = $tag;

            $manager->persist($tag);


        }
        //! Questions

        $questionList = [];

        for ($i = 1; $i < 50; $i++) {
            $question = new Question();
            $question->setTitle(rtrim($faker->unique()->sentence($nbWords = 9, $variableNbWords = true), '.') . ' ?');
            $question->setBody($faker->unique()->paragraph($nbSentences = 6, $variableNbSentences = true));
            $question->setCreatedAt(new DateTimeImmutable($faker->date()));
            $question->setVotes(0);
            $question->setUser($userList[array_rand($userList)]);

            // ça permet de faire ou pas un updatedAt selon le mt_rand
            if (mt_rand(0, 1)) {
                // date aléatoire remontant à 14 jours en arrière au plus tard
                $question->setUpdatedAt($faker->dateTimeBetween("-14 days"));
            }

            $questionList[] = $question;

            $manager->persist($question);


        }

        //! Answers

        $answersList = [];

        for ($i = 1; $i < 100; $i++) {
            $answer = new Answer();
            $answer->setBody($faker->unique()->paragraph($nbSentences = 3, $variableNbSentences = true));
            $answer->setCreatedAt($faker->unique()->dateTime($max = 'now', $timezone = null));
            $answer->setVotes(0);
            $answer->setQuestion($questionList[array_rand($questionList)]);
            $answer->setUser($userList[array_rand($userList)]);

            $answersList[] = $answer;

            $manager->persist($answer);


        }

        // Tags sur questions
        foreach ($questionList as $question) {
            // On mélange les tags et on en récupère 1 à 3 au hasard
            shuffle($tagList);
            $tagCount = mt_rand(1, 3);
            for ($i = 1; $i <= $tagCount; $i++) {
                $question->addTag($tagList[$i]);
            }
        }

        // ! Picture
        for ($i = 0; $i < 24; $i++) {
            // J'instancie un nouvel objet picture

            $picture = new Picture();
            // utilisation de l'api Unsplash pour generer des photos de garden
            $picture->setUrl($this->unsplashApi->fetchPhotosRandom("garden"));
            $picture->setCreatedAt(new DateTimeImmutable($faker->date()));
            $picture->setGarden($gardenList[array_rand($gardenList)]);

            $manager->persist($picture);
        }




        // J'execute les requetes sql
        $manager->flush();
    }
}