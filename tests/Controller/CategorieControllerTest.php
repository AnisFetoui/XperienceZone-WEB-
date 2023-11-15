<?php

namespace App\Test\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategorieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CategorieRepository $repository;
    private string $path = '/categorie/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Categorie::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Categorie index');

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
            'categorie[nomCategorie]' => 'Testing',
            'categorie[descriptionCategorie]' => 'Testing',
            'categorie[typeCategorie]' => 'Testing',
        ]);

        self::assertResponseRedirects('/categorie/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setNomCategorie('My Title');
        $fixture->setDescriptionCategorie('My Title');
        $fixture->setTypeCategorie('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Categorie');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setNomCategorie('My Title');
        $fixture->setDescriptionCategorie('My Title');
        $fixture->setTypeCategorie('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'categorie[nomCategorie]' => 'Something New',
            'categorie[descriptionCategorie]' => 'Something New',
            'categorie[typeCategorie]' => 'Something New',
        ]);

        self::assertResponseRedirects('/categorie/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomCategorie());
        self::assertSame('Something New', $fixture[0]->getDescriptionCategorie());
        self::assertSame('Something New', $fixture[0]->getTypeCategorie());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Categorie();
        $fixture->setNomCategorie('My Title');
        $fixture->setDescriptionCategorie('My Title');
        $fixture->setTypeCategorie('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/categorie/');
    }
}
