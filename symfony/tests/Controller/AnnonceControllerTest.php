<?php

namespace App\Test\Controller;

use App\Entity\Annonce;
use App\Entity\Emploi;
use App\Entity\Immobilier;
use App\Entity\Modele;
use App\Repository\AnnonceRepository;
use App\Repository\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnnonceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AnnonceRepository $annonceRepository;
    private ModeleRepository $modeleRepository;
    private string $path = '/annonce';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->annonceRepository = (static::getContainer()->get('doctrine'))->getRepository(Annonce::class);
        $this->modeleRepository = (static::getContainer()->get('doctrine'))->getRepository(Modele::class);

        foreach ($this->annonceRepository->findAll() as $object) {
            $this->annonceRepository->remove($object);
        }
    }

    public function testCreation(): void
    {
        $originalNumObjectsInRepository = count($this->annonceRepository->findAll());
        $this->client->request('POST', $this->path, ['titre' => 'Test Création', 'contenu' => 'Catégorie emploi', 'categorie_id' => 1]);

        self::assertResponseStatusCodeSame(200);
        self::assertSame($originalNumObjectsInRepository + 1, count($this->annonceRepository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Annonce();
        $fixture->setTitre('Titre test');
        $fixture->setContenu('Test Contenue');
        $fixtureEmploi = new Emploi();
        $fixture->setEmploi($fixtureEmploi);

        $this->annonceRepository->add($fixture);

        $this->client->request('GET', sprintf($this->path . '/' . $fixture->getId()));
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent());

        self::assertResponseStatusCodeSame(200);
        self::assertSame($fixture->getId(), $data->id);
    }

    public function testEdit(): void
    {
        $fixture = new Annonce();
        $fixture->setTitre('titre');
        $fixture->setContenu('contenu');
        $fixtureImmobilier = new Immobilier();
        $fixture->setImmobilier($fixtureImmobilier);

        $this->annonceRepository->add($fixture);

        $this->client->request(
            'PUT',
            $this->path . '/' . $fixture->getId(),
            ['titre' => 'titre update']
        );

        $fixture = $this->annonceRepository->find($fixture->getId());

        self::assertSame('titre update', $fixture->getTitre());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->annonceRepository->findAll());

        $fixture = new Annonce();
        $fixture->setTitre('Titre test');
        $fixture->setContenu('Test Contenue');
        $fixtureEmploi = new Emploi();
        $fixture->setEmploi($fixtureEmploi);

        $this->annonceRepository->add($fixture);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->annonceRepository->findAll()));

        $this->client->request('DELETE', $this->path . '/' . $fixture->getId());

        self::assertSame($originalNumObjectsInRepository, count($this->annonceRepository->findAll()));
    }
    
    public function testCreationAutomobile(): void
    {
        /** @var Modele $modele */
        $modele = $this->modeleRepository->findOneBy(['nom' => 'Rs4']);
        $this->client->request('POST', $this->path, ['titre' => 'rs4 avant', 'contenu' => 'test', 'categorie_id' => 3]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent());

        self::assertSame($data->automobile->modele->id, $modele->getId());
        self::assertSame($data->automobile->modele->marque->id, $modele->getMarque()->getId());
    }

    public function testCreationAutomobile_2(): void
    {
        /** @var Modele $modele */
        $modele = $this->modeleRepository->findOneBy(['nom' => 'Serie 5']);
        $this->client->request('POST', $this->path, ['titre' => 'Gran Turismo Série5', 'contenu' => 'test', 'categorie_id' => 3]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent());

        self::assertSame($data->automobile->modele->id, $modele->getId());
        self::assertSame($data->automobile->modele->marque->id, $modele->getMarque()->getId());
    }

    public function testCreationAutomobile_3(): void
    {
        /** @var Modele $modele */
        $modele = $this->modeleRepository->findOneBy(['nom' => 'Ds3']);
        $this->client->request('POST', $this->path, ['titre' => 'ds 3 crossback', 'contenu' => 'test', 'categorie_id' => 3]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent());

        self::assertSame($data->automobile->modele->id, $modele->getId());
        self::assertSame($data->automobile->modele->marque->id, $modele->getMarque()->getId());
    }

    public function testCreationAutomobile_4(): void
    {
        /** @var Modele $modele */
        $modele = $this->modeleRepository->findOneBy(['nom' => 'Ds3']);
        $this->client->request('POST', $this->path, ['titre' => 'CrossBack ds 3', 'contenu' => 'test', 'categorie_id' => 3]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent());

        self::assertSame($data->automobile->modele->id, $modele->getId());
        self::assertSame($data->automobile->modele->marque->id, $modele->getMarque()->getId());
    }
}
