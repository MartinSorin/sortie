<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\Trip;
use App\Security\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;
    private Faker\Generator $faker;
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function addstates(){
        //Création des données de la base State
        $state= new State();
        $state->setWording('En creation');
        $this->manager->persist($state);
        $state1= new State();
        $state1->setWording('Ouverte');
        $this->manager->persist($state1);
        $state2= new State();
        $state2->setWording('Cloturee');
        $this->manager->persist($state2);
        $state3= new State();
        $state3->setWording('En cours');
        $this->manager->persist($state3);
        $state4= new State();
        $state4->setWording('Terminee');
        $this->manager->persist($state4);
        $state5= new State();
        $state5->setWording('Historisee');
        $this->manager->persist($state5);
        $state6= new State();
        $state6->setWording('Annulee');
        $this->manager->persist($state6);

        $this->manager->flush();

    }

    public function addCampus(){
        //Création des donnée de la base Campus
        $campus = new Campus();
        $campus->setName('Nantes');
        $this->manager->persist($campus);
        $campus1 = new Campus();
        $campus1->setName('Rennes');
        $this->manager->persist($campus1);
        $campus2 = new Campus();
        $campus2->setName('Quimper');
        $this->manager->persist($campus2);
        $campus3 = new Campus();
        $campus3->setName('Niort');
        $this->manager->persist($campus3);

        $this->manager->flush();
    }

    public function addCity(){

        //Création de donnée de la base Ville
        $city = Array();

        for ($i = 0; $i < 12; $i++) {
            $city[$i] = new City();
            $city[$i]->setName($this->faker->city);
            $city[$i]->setPostalCode($this->faker->postcode);

            $this->manager->persist($city[$i]);
        }

        $this->manager->flush();
    }

    public function addPlace(){
        $city = $this->manager->getRepository(City::class)->findAll();
        //création des lieux
        $place = Array();

        for ($i = 0; $i < 12; $i++) {
            $place[$i] = new Place();
            $place[$i]->setName($this->faker->word);
            $place[$i]->setStreet($this->faker->address);
            $place[$i]->setLatitude($this->faker->latitude);
            $place[$i]->setLongitude($this->faker->longitude);
            $place[$i]->setCity($this->faker->randomElement($city));


            $this->manager->persist($place[$i]);
        }
        $this->manager->flush();
    }

    public function addParticipant(){

        $campus = $this->manager->getRepository(Campus::class)->findAll();
        $user= new User();
        // on crée 4 auteurs avec noms et prénoms "aléatoires" en français

        $participantCaly = new Participant();
        $participantCaly->setName('Chevrier');
        $participantCaly->setFirstname('Caly');
        $participantCaly->setPhone('06 01 02 03 04');
        $participantCaly->setEmail('chevrier.caroline@camps.fr');
        $participantCaly->setPassword($this->encoder->hashPassword($participantCaly,'123456'));
        $participantCaly->setActive(true);
        $participantCaly->setIsAffectedTo($this->faker->randomElement($campus));
        $participantCaly->setRoles('ROLE_ADMIN');
        $participantCaly->setUsername('Caly');
        $this->manager->persist($participantCaly);

        $participantMarti = new Participant();
        $participantMarti->setName('Sorin');
        $participantMarti->setFirstname('Martin');
        $participantMarti->setPhone('07 01 00 00 00');
        $participantMarti->setEmail('martin@mail.com');
        $participantMarti->setPassword($this->encoder->hashPassword($participantCaly,'314'));
        $participantMarti->setActive(true);
        $participantMarti->setIsAffectedTo($this->faker->randomElement($campus));
        $participantMarti->setRoles('ROLE_ADMIN');
        $participantMarti->setUsername('Marti');
        $this->manager->persist($participantMarti);

        for ($i = 0; $i < 4; $i++) {
            $participant[$i] = new Participant();
            $participant[$i]->setName($this->faker->lastName);
            $participant[$i]->setFirstname($this->faker->firstName);
            $participant[$i]->setPhone($this->faker->phoneNumber);
            $participant[$i]->setEmail($this->faker->email);
            $participant[$i]->setPassword($this->encoder->hashPassword($participant[$i],$this->faker->password));
            $participant[$i]->setActive(true);
            $participant[$i]->setIsAffectedTo($this->faker->randomElement($campus));


            $this->manager->persist($participant[$i]);
        }
        $this->manager->flush();
    }

    public function addTrip(){

        // nouvelle boucle pour créer des livres
        $organiser =$this->manager->getRepository(Participant::class)->findAll();
        $place =$this->manager->getRepository(Place::class)->findAll();
        $campus = $this->manager->getRepository(Campus::class)->findAll();
        $state = $this->manager->getRepository(State::class)->findAll();
        $trip = Array();

        for ($i = 0; $i < 12; $i++) {
            $trip[$i] = new Trip();
            $trip[$i]->setName($this->faker->word);
            $trip[$i]->setDateTimeStart($this->faker->dateTimeBetween('-2 years','+6 month'));
            $trip[$i]->setDuration($this->faker->numberBetween(10,360));
            $trip[$i]->setDateLimitInscription($this->faker->dateTimeBetween('-2 years','+6 month'));
            $trip[$i]->setNbInscriptionsMax($this->faker->numberBetween(5,50));
            $trip[$i]->setInfoTrip($this->faker->word);
            $trip[$i]->setOrganiser($this->faker->randomElement($organiser));
            $trip[$i]->setPlace($this->faker->randomElement($place));
            $trip[$i]->setSiteOrganiser($this->faker->randomElement($campus));
            $trip[$i]->setState($this->faker->randomElement($state));
            $trip[$i]->addIsRegistered($trip[$i]->getOrganiser());
            $organise=$trip[$i]->getOrganiser();
            $organise->addTrip($trip[$i]);
            $this->manager->persist($trip[$i]);
        }


        $this->manager->flush();

    }
    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;
        $this->addstates();
        $this->addCampus();
        $this->addCity();
        $this->addPlace();
        $this->addParticipant();
        $this->addTrip();

//        $participant= new Participant();
//        $participant->setName('Chevrier');
//        $participant->setFirstname('Caly');
//        $participant->setPhone('0269853014');
//        $participant->setEmail('caroline.chevrier@campus.fr');
//        $participant->setPassword($this->encoder->hashPassword($participant,'123456'));
//        $participant->setIsAffectedTo($this->manager->getRepository(Campus::class)->find(2));
//        $participant->setActive(1);
//        $participant->setRoles(['ROLE_ADMIN']);
//        $this->manager->persist($participant);
//        $this->manager->flush();

    }
}
