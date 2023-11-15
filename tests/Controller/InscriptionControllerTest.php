<?php

namespace App\Test\Controller;

use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InscriptionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private InscriptionRepository $repository;
    private string $path = '/inscription/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Inscription::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Inscription index');

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
            'inscription[dateIns]' => 'Testing',
            'inscription[heureIns]' => 'Testing',
            'inscription[nbrTickes]' => 'Testing',
            'inscription[fraitAbonnement]' => 'Testing',
            'inscription[activiteId]' => 'Testing',
            'inscription[userId]' => 'Testing',
        ]);

        self::assertResponseRedirects('/inscription/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inscription();
        $fixture->setDateIns('My Title');
        $fixture->setHeureIns('My Title');
        $fixture->setNbrTickes('My Title');
        $fixture->setFraitAbonnement('My Title');
        $fixture->setActiviteId('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Inscription');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inscription();
        $fixture->setDateIns('My Title');
        $fixture->setHeureIns('My Title');
        $fixture->setNbrTickes('My Title');
        $fixture->setFraitAbonnement('My Title');
        $fixture->setActiviteId('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'inscription[dateIns]' => 'Something New',
            'inscription[heureIns]' => 'Something New',
            'inscription[nbrTickes]' => 'Something New',
            'inscription[fraitAbonnement]' => 'Something New',
            'inscription[activiteId]' => 'Something New',
            'inscription[userId]' => 'Something New',
        ]);

        self::assertResponseRedirects('/inscription/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateIns());
        self::assertSame('Something New', $fixture[0]->getHeureIns());
        self::assertSame('Something New', $fixture[0]->getNbrTickes());
        self::assertSame('Something New', $fixture[0]->getFraitAbonnement());
        self::assertSame('Something New', $fixture[0]->getActiviteId());
        self::assertSame('Something New', $fixture[0]->getUserId());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Inscription();
        $fixture->setDateIns('My Title');
        $fixture->setHeureIns('My Title');
        $fixture->setNbrTickes('My Title');
        $fixture->setFraitAbonnement('My Title');
        $fixture->setActiviteId('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/inscription/');
    }
}
