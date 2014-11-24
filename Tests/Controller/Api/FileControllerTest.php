<?php

namespace Melodia\FileBundle\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;


// TODO create base class with method compareObjects in RestBundle and use it in every test


class FileControllerTest extends WebTestCase
{
    /**
     * @return int
     */
    public function testGetAll()
    {
        $client = static::createClient();
        $client->request('GET', '/api/files');

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue($jsonResponse !== null);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return count($jsonResponse);
    }

    /**
     * @return \stdClass
     */
    public function testPost()
    {
        $client = static::createClient();
        $kernel = $client->getContainer()->get('kernel');
        $filePath = $kernel->locateResource('@MelodiaFileBundle/Tests/Resources/file.txt');

        $testFile = new UploadedFile($filePath, 'file.txt');

        $client->request('POST', '/api/files', array(),
            array('file' => $testFile)
        );

        $jsonResponse = json_decode($client->getResponse()->getContent(), $assoc = true);
        $this->assertTrue($jsonResponse !== null);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        return $jsonResponse;
    }

    /**
     * @depends testGetAll
     *
     * @param int $count
     * @return int
     */
    public function testCountIncremented($count)
    {
        $client = static::createClient();
        $client->request('GET', '/api/files');

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue($jsonResponse !== null);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals($count + 1, count($jsonResponse));

        return count($jsonResponse);
    }

    /**
     * @depends testPost
     *
     * @param \stdClass $object
     */
    public function testGetOne($object)
    {
        $client = static::createClient();
        $client->request('GET', '/api/files/' . $object['id']);

        $jsonResponse = json_decode($client->getResponse()->getContent(), $assoc = true);
        $this->assertTrue($jsonResponse !== null);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals($object['fileUri'], $jsonResponse['fileUri']);
    }

    /**
     * @depends testPost
     *
     * @param \stdClass $object
     */
    public function testDelete($object)
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/files/' . $object['id']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check that object has been deleted
        $client->request('GET', '/api/files/' . $object['id']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testCountIncremented
     *
     * @param int $count The number of objects after adding the new one
     */
    public function testCountDecremented($count)
    {
        $client = static::createClient();
        $client->request('GET', '/api/files');

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue($jsonResponse !== null);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($count - 1, count($jsonResponse));
    }
}
