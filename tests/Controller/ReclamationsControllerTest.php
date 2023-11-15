<?php

namespace App\Test\Controller;

use App\Entity\Reclamations;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReclamationsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReclamationRepository $repository;
    private string $path = '/reclamations/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reclamations::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reclamation index');

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
            'reclamation[daterec]' => 'Testing',
            'reclamation[typerec]' => 'Testing',
            'reclamation[refobject]' => 'Testing',
            'reclamation[utilisateur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reclamations/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reclamations();
        $fixture->setDaterec('My Title');
        $fixture->setTyperec('My Title');
        $fixture->setRefobject('My Title');
        $fixture->setUtilisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reclamation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reclamations();
        $fixture->setDaterec('My Title');
        $fixture->setTyperec('My Title');
        $fixture->setRefobject('My Title');
        $fixture->setUtilisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reclamation[daterec]' => 'Something New',
            'reclamation[typerec]' => 'Something New',
            'reclamation[refobject]' => 'Something New',
            'reclamation[utilisateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reclamations/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDaterec());
        self::assertSame('Something New', $fixture[0]->getTyperec());
        self::assertSame('Something New', $fixture[0]->getRefobject());
        self::assertSame('Something New', $fixture[0]->getUtilisateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reclamations();
        $fixture->setDaterec('My Title');
        $fixture->setTyperec('My Title');
        $fixture->setRefobject('My Title');
        $fixture->setUtilisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reclamations/');
    }
}
