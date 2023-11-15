<?php

namespace App\Test\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EvenementRepository $repository;
    private string $path = '/evenement/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Evenement::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement index');

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
            'evenement[nomEvent]' => 'Testing',
            'evenement[descript]' => 'Testing',
            'evenement[dateEvent]' => 'Testing',
            'evenement[heureEvent]' => 'Testing',
            'evenement[lieuEvent]' => 'Testing',
            'evenement[nbParticipants]' => 'Testing',
            'evenement[image]' => 'Testing',
            'evenement[organisateur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/evenement/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNomEvent('My Title');
        $fixture->setDescript('My Title');
        $fixture->setDateEvent('My Title');
        $fixture->setHeureEvent('My Title');
        $fixture->setLieuEvent('My Title');
        $fixture->setNbParticipants('My Title');
        $fixture->setImage('My Title');
        $fixture->setOrganisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNomEvent('My Title');
        $fixture->setDescript('My Title');
        $fixture->setDateEvent('My Title');
        $fixture->setHeureEvent('My Title');
        $fixture->setLieuEvent('My Title');
        $fixture->setNbParticipants('My Title');
        $fixture->setImage('My Title');
        $fixture->setOrganisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'evenement[nomEvent]' => 'Something New',
            'evenement[descript]' => 'Something New',
            'evenement[dateEvent]' => 'Something New',
            'evenement[heureEvent]' => 'Something New',
            'evenement[lieuEvent]' => 'Something New',
            'evenement[nbParticipants]' => 'Something New',
            'evenement[image]' => 'Something New',
            'evenement[organisateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/evenement/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomEvent());
        self::assertSame('Something New', $fixture[0]->getDescript());
        self::assertSame('Something New', $fixture[0]->getDateEvent());
        self::assertSame('Something New', $fixture[0]->getHeureEvent());
        self::assertSame('Something New', $fixture[0]->getLieuEvent());
        self::assertSame('Something New', $fixture[0]->getNbParticipants());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getOrganisateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Evenement();
        $fixture->setNomEvent('My Title');
        $fixture->setDescript('My Title');
        $fixture->setDateEvent('My Title');
        $fixture->setHeureEvent('My Title');
        $fixture->setLieuEvent('My Title');
        $fixture->setNbParticipants('My Title');
        $fixture->setImage('My Title');
        $fixture->setOrganisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/evenement/');
    }
}
