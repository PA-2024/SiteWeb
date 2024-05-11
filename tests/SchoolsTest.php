<?php
use PHPUnit\Framework\TestCase;
use GeSign\Schools;

class SchoolsTest extends TestCase
{
    public function testFetchSchools()
    {
        // on crée un stub pour la classe Schools
        $schools = $this->getMockBuilder(Schools::class)
                        ->onlyMethods(['callApi'])
                        ->getMock();

        // on prépare le tableau de réponse attendu
        $mockedResponse = [
            ['school_Id' => 1, 'school_Name' => 'Test', 'school_token' => 'abc123', 'school_allowSite' => true]
        ];

        // on configure le stub pour retourner un tableau lors de l'appel de callApi
        $schools->method('callApi')
                ->willReturn($mockedResponse);

        // on appelle fetchSchools et on vérifie les résultats
        $result = $schools->fetchSchools();
        
        $this->assertIsArray($result);
        $this->assertEquals('string', $result[0]['school_Name']);
    }

    public function testCreateSchool()
    {
        // on crée un stub pour la classe Schools
        $schools = $this->getMockBuilder(Schools::class)
                        ->onlyMethods(['callApi'])
                        ->getMock();

        // on prépare le tableau de réponse attendu pour une création
        $mockedResponse = [
            'school_Id' => 6, 'school_Name' => 'New School', 'school_token' => 'def456', 'school_allowSite' => true
        ];

        // on configure le stub pour retourner un tableau lors de l'appel de callApi
        $schools->method('callApi')
                ->willReturn($mockedResponse);

        // on appelle createSchool et on vérifie les résultats
        $result = $schools->createSchool('New School', 'def456', true);

        $this->assertIsArray($result);
        $this->assertEquals('New School', $result['school_Name']);
        $this->assertTrue($result['school_allowSite']);
    }
}
