<?php

namespace App\Test\Controller;

use App\Entity\Panier;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PanierControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PanierRepository $repository;
    private string $path = '/panier/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Panier::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Panier index');

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
            'panier[total]' => 'Testing',
            'panier[quantitePanier]' => 'Testing',
            'panier[utilisateur]' => 'Testing',
            'panier[produit]' => 'Testing',
        ]);

        self::assertResponseRedirects('/panier/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Panier();
        $fixture->setTotal('My Title');
        $fixture->setQuantitePanier('My Title');
        $fixture->setUtilisateur('My Title');
        $fixture->setProduit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Panier');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Panier();
        $fixture->setTotal('My Title');
        $fixture->setQuantitePanier('My Title');
        $fixture->setUtilisateur('My Title');
        $fixture->setProduit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'panier[total]' => 'Something New',
            'panier[quantitePanier]' => 'Something New',
            'panier[utilisateur]' => 'Something New',
            'panier[produit]' => 'Something New',
        ]);

        self::assertResponseRedirects('/panier/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getQuantitePanier());
        self::assertSame('Something New', $fixture[0]->getUtilisateur());
        self::assertSame('Something New', $fixture[0]->getProduit());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Panier();
        $fixture->setTotal('My Title');
        $fixture->setQuantitePanier('My Title');
        $fixture->setUtilisateur('My Title');
        $fixture->setProduit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/panier/');
    }
}
