<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


use App\Entity\Entreprise;
use App\Entity\Annonce;
use App\Entity\Contrat;
use App\Entity\Metier;
use App\Entity\Ville;
use App\Entity\Image;

use Faker;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    private $client;

    public function __construct(HttpClientInterface $aClient)
    {
        $this->client = $aClient;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $arrVilleObj = [];
        $arrContratObj = [];
        $arrMetierObj = [];
        $arrEntrepriseObj = [];
        

        $villes = ['Paris', 'Lyon', 'Marseille', 'Bordeaux'];
        foreach($villes as $el){
            $ville = new Ville();
            $ville->setName($el);
            $manager->persist($ville);    
            
            array_push($arrVilleObj, $ville);
            
        }

        $contrats = ['CDD', 'CDI', 'Stage', 'Alternance'];
        foreach($contrats as $el){
            $contrat = new Contrat();
            $contrat->setTypeContrat($el);
            $manager->persist($contrat);   
            
            array_push($arrContratObj, $contrat);
        }

        $metiers = ['Boulanger', 'Developpeur', 'Cuisinier', 'Chef de projet', 'Chauffeur', 'Macon', 'Charpentier', 'Commercial'];
        foreach($metiers as $el){
            $metier = new Metier();
            $metier->setJobName($el);
            $manager->persist($metier);

            array_push($arrMetierObj, $metier);
        }

        for($i=0; $i<10; $i++){
            $entreprise = new Entreprise();
            $entreprise->setName($faker->word());
            $manager->persist($entreprise);

            array_push($arrEntrepriseObj, $entreprise);
        }

        for($i=0; $i<100; $i++){

            if($i%2 == 0){

                $response = $this->client->request(
                    'GET',
                    'https://some-random-api.com/animal/cat'
                );
            }
    
            $content = $response->toArray();

            $image = new Image();
            $image->setUrl($content['image']);
            //$image->setFact($content['fact']);
            $manager->persist($image);

            $annonce = new Annonce();
            $annonce->setTitle($faker->word());
            $annonce->setDescription($faker->paragraph(2));
            $annonce->setReference($faker->isbn13());
            $annonce->setVille($arrVilleObj[rand(0, count($arrVilleObj)-1)]);
            $annonce->setMetier($arrMetierObj[rand(0, count($arrMetierObj)-1)]);
            $annonce->setContrat($arrContratObj[rand(0, count($arrContratObj)-1)]);
            $annonce->setEntreprise($arrEntrepriseObj[rand(0, count($arrEntrepriseObj)-1)]);
            $annonce->setImage($image);

            $manager->persist($annonce);
        }     
        $manager->flush();
 
    }

}
