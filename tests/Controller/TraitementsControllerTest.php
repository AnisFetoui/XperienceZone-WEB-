<?php

namespace App\Test\Controller;

use App\Entity\Traitements;
use App\Repository\TraitementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TraitementsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TraitementRepository $repository;
    private string $path = '/traitements/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Traitements::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Traitement index');

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
            'traitement[refobj]' => 'Testing',
            'traitement[dater]' => 'Testing',
            'traitement[typer]' => 'Testing',
            'traitement[resume]' => 'Testing',
            'traitement[stat]' => 'Testing',
            'traitement[Utilisateur]' => 'Testing',
            'traitement[reclamations]' => 'Testing',
        ]);

        self::assertResponseRedirects('/traitements/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Traitements();
        $fixture->setRefobj('My Title');
        $fixture->setDater('My Title');
        $fixture->setTyper('My Title');
        $fixture->setResume('My Title');
        $fixture->setStat('My Title');
        $fixture->setUtilisateur('My Title');
        $fixture->setReclamations('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Traitement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Traitements();
        $fixture->setRefobj('My Title');
        $fixture->setDater('My Title');
        $fixture->setTyper('My Title');
        $fixture->setResume('My Title');
        $fixture->setStat('My Title');
        $fixture->setUtilisateur('My Title');
        $fixture->setReclamations('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'traitement[refobj]' => 'Something New',
            'traitement[dater]' => 'Something New',
            'traitement[typer]' => 'Something New',
            'traitement[resume]' => 'Something New',
            'traitement[stat]' => 'Something New',
            'traitement[Utilisateur]' => 'Something New',
            'traitement[reclamations]' => 'Something New',
        ]);

        self::assertResponseRedirects('/traitements/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getRefobj());
        self::assertSame('Something New', $fixture[0]->getDater());
        self::assertSame('Something New', $fixture[0]->getTyper());
        self::assertSame('Something New', $fixture[0]->getResume());
        self::assertSame('Something New', $fixture[0]->getStat());
        self::assertSame('Something New', $fixture[0]->getUtilisateur());
        self::assertSame('Something New', $fixture[0]->getReclamations());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Traitements();
        $fixture->setRefobj('My Title');
        $fixture->setDater('My Title');
        $fixture->setTyper('My Title');
        $fixture->setResume('My Title');
        $fixture->setStat('My Title');
        $fixture->setUtilisateur('My Title');
        $fixture->setReclamations('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/traitements/');
    }
}
