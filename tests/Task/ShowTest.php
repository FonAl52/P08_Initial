<?php

namespace App\Tests\Task;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;

class ShowTest extends WebTestCase
{
    /**
     * @test
     */
    public function task_should_be_displayed()
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $user = $entityManager->getRepository(User::class)->findOneBy([]);
        $client->loginUser($user);

        /*
            on récupère le router pour générer directement une url
            car plus tard l'url peut évolué donc on ne veut pas l'écrire en dure
        */
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_list")
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(20, $crawler->filter('.thumbnail'));
    }
}
