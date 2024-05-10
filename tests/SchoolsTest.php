<?php
use PHPUnit\Framework\TestCase;
use GeSign\Schools;

class SchoolsTest extends TestCase
{
    public function testFetchSchools()
    {
        $schools = new Schools();

        //on va simuler un environnement où cURL retourne une réponse fictive
        $mockedResponse = json_encode([
            ['school_Id' => 1, 'school_Name' => 'Test', 'school_token' => 'abc123', 'school_allowSite' => true]
        ]);

        //on remplace la méthode de cURL par un "stub" qui retourne la réponse mockée
        $schools = $this->getMockBuilder(Schools::class)
                        ->onlyMethods(['callApi'])
                        ->getMock();
        $schools->method('callApi')
                ->willReturn($mockedResponse);

        $result = $schools->fetchSchools();
        $decoded = json_decode($result, true);

        $this->assertIsArray($decoded);
        $this->assertEquals('Test', $decoded[0]['school_Name']);
    }

    public function testCreateSchool()
    {
        $schools = new Schools();
        $mockedResponse = json_encode([
            'school_Id' => 6, 'school_Name' => 'New School', 'school_token' => 'def456', 'school_allowSite' => true
        ]);

        //mock la methode
        $schools = $this->getMockBuilder(Schools::class)
                        ->onlyMethods(['callApi'])
                        ->getMock();
        $schools->method('callApi')
                ->willReturn($mockedResponse);

        $result = $schools->createSchool('New School', 'def456', true);
        $decoded = json_decode($result, true);

        $this->assertIsArray($decoded);
        $this->assertEquals('New School', $decoded['school_Name']);
        $this->assertTrue($decoded['school_allowSite']);
    }
}
