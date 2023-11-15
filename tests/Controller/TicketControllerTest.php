<?php

namespace App\Test\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TicketRepository $repository;
    private string $path = '/ticket/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Ticket::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ticket index');

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
            'ticket[numTicket]' => 'Testing',
            'ticket[image]' => 'Testing',
            'ticket[prix]' => 'Testing',
            'ticket[categorie]' => 'Testing',
            'ticket[evenement]' => 'Testing',
            'ticket[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/ticket/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ticket();
        $fixture->setNumTicket('My Title');
        $fixture->setImage('My Title');
        $fixture->setPrix('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setEvenement('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ticket');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ticket();
        $fixture->setNumTicket('My Title');
        $fixture->setImage('My Title');
        $fixture->setPrix('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setEvenement('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ticket[numTicket]' => 'Something New',
            'ticket[image]' => 'Something New',
            'ticket[prix]' => 'Something New',
            'ticket[categorie]' => 'Something New',
            'ticket[evenement]' => 'Something New',
            'ticket[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ticket/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNumTicket());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getCategorie());
        self::assertSame('Something New', $fixture[0]->getEvenement());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Ticket();
        $fixture->setNumTicket('My Title');
        $fixture->setImage('My Title');
        $fixture->setPrix('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setEvenement('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/ticket/');
    }
}
