<?php

namespace App\Tests\Controller;

use App\Entity\Reclamation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ReclamationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $reclamationRepository;
    private string $path = '/rec/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->reclamationRepository = $this->manager->getRepository(Reclamation::class);

        foreach ($this->reclamationRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reclamation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reclamation[rec]' => 'Testing',
            'reclamation[date]' => 'Testing',
            'reclamation[issuer]' => 'Testing',
            'reclamation[motif]' => 'Testing',
            'reclamation[id_rep]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->reclamationRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reclamation();
        $fixture->setRec('My Title');
        $fixture->setDate('My Title');
        $fixture->setIssuer('My Title');
        $fixture->setMotif('My Title');
        $fixture->setId_rep('My Title');

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
        $fixture = new Reclamation();
        $fixture->setRec('Value');
        $fixture->setDate('Value');
        $fixture->setIssuer('Value');
        $fixture->setMotif('Value');
        $fixture->setId_rep('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reclamation[rec]' => 'Something New',
            'reclamation[date]' => 'Something New',
            'reclamation[issuer]' => 'Something New',
            'reclamation[motif]' => 'Something New',
            'reclamation[id_rep]' => 'Something New',
        ]);

        self::assertResponseRedirects('/rec/');

        $fixture = $this->reclamationRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getRec());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getIssuer());
        self::assertSame('Something New', $fixture[0]->getMotif());
        self::assertSame('Something New', $fixture[0]->getId_rep());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reclamation();
        $fixture->setRec('Value');
        $fixture->setDate('Value');
        $fixture->setIssuer('Value');
        $fixture->setMotif('Value');
        $fixture->setId_rep('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/rec/');
        self::assertSame(0, $this->reclamationRepository->count([]));
    }
}
