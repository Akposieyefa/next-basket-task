<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreate(): void
    {
        $client = static::createClient();

        $data = [
            'email' => 'recipient@example.com',
            'firstName' => 'Orutu',
            'lastName' => 'Akposieyefa'
        ];
        $client->request('POST', '/users', [], [], [], json_encode($data));
        
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

}
