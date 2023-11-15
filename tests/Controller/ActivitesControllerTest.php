<?php

namespace App\Test\Controller;

use App\Entity\Activites;
use App\Repository\ActivitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ActivitesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ActivitesRepository $repository;
    private string $path = '/activites/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Activites::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Activite index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'activite[nomAct]' => 'Testing',
            'activite[description]' => 'Testing',
            'activite[organisateur]' => 'Testing',
            'activite[lieuAct]' => 'Testing',
            'activite[adresse]' => 'Testing',
            'activite[images]' => 'Testing',
            'activite[placeDiso]' => 'Testing',
            'activite[prixAct]' => 'Testing',
            'activite[duree]' => 'Testing',
            'activite[periode]' => 'Testing',
            'activite[idUser]' => 'Testing',
        ]);

        self::assertResponseRedirects('/activites/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Activites();
        $fixture->setNomAct('My Title');
        $fixture->setDescription('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setLieuAct('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setImages('My Title');
        $fixture->setPlaceDiso('My Title');
        $fixture->setPrixAct('My Title');
        $fixture->setDuree('My Title');
        $fixture->setPeriode('My Title');
        $fixture->setIdUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Activite');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Activites();
        $fixture->setNomAct('My Title');
        $fixture->setDescription('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setLieuAct('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setImages('My Title');
        $fixture->setPlaceDiso('My Title');
        $fixture->setPrixAct('My Title');
        $fixture->setDuree('My Title');
        $fixture->setPeriode('My Title');
        $fixture->setIdUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'activite[nomAct]' => 'Something New',
            'activite[description]' => 'Something New',
            'activite[organisateur]' => 'Something New',
            'activite[lieuAct]' => 'Something New',
            'activite[adresse]' => 'Something New',
            'activite[images]' => 'Something New',
            'activite[placeDiso]' => 'Something New',
            'activite[prixAct]' => 'Something New',
            'activite[duree]' => 'Something New',
            'activite[periode]' => 'Something New',
            'activite[idUser]' => 'Something New',
        ]);

        self::assertResponseRedirects('/activites/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomAct());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getOrganisateur());
        self::assertSame('Something New', $fixture[0]->getLieuAct());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getImages());
        self::assertSame('Something New', $fixture[0]->getPlaceDiso());
        self::assertSame('Something New', $fixture[0]->getPrixAct());
        self::assertSame('Something New', $fixture[0]->getDuree());
        self::assertSame('Something New', $fixture[0]->getPeriode());
        self::assertSame('Something New', $fixture[0]->getIdUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Activites();
        $fixture->setNomAct('My Title');
        $fixture->setDescription('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setLieuAct('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setImages('My Title');
        $fixture->setPlaceDiso('My Title');
        $fixture->setPrixAct('My Title');
        $fixture->setDuree('My Title');
        $fixture->setPeriode('My Title');
        $fixture->setIdUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/activites/');
    }
}
