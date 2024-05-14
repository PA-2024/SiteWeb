<?php
use PHPUnit\Framework\TestCase;
use GeSign\Schools;

class SchoolsTest extends TestCase
{
    private $schools;

    protected function setUp(): void
    {
        $this->schools = new Schools();
    }

    public function testFetchSchools()
    {
        // Appel direct de fetchSchools
        $result = $this->schools->fetchSchools();
        
        $this->assertIsArray($result);
        // on s'assure que le tableau n'est pas vide pour confirmer que des données sont retournées
        $this->assertNotEmpty($result);
        // on vérifie un champ
        $this->assertArrayHasKey('school_Name', $result[0]);
    }

    public function testCreateAndDeleteSchool()
    {
        // Test de création d'une école
        $createResult = $this->schools->createSchool('Test School', 'test123', true);
        $this->assertIsArray($createResult);
        $this->assertEquals('Test School', $createResult['school_Name']);
        $this->assertTrue($createResult['school_allowSite']);

        // Test de suppression de l'école créée
        if (isset($createResult['school_Id'])) {
            $deleteResult = $this->schools->deleteSchool($createResult['school_Id']);
            $this->assertTrue($deleteResult);
        } else {
            $this->fail('School ID is not set in the createSchool response.');
        }
    }
	
    public function testUpdateSchool()
    {
        // Test de création d'une école pour avoir une école à mettre à jour
        $createResult = $this->schools->createSchool('Test School', 'test123', true);
        $this->assertIsArray($createResult);
        $this->assertEquals('Test School', $createResult['school_Name']);
        $this->assertTrue($createResult['school_allowSite']);

        // Test de mise à jour de l'école
        if (isset($createResult['school_Id'])) {
            $schoolId = $createResult['school_Id'];
            $updateResult = $this->schools->updateSchool($schoolId, 'Updated School', 'updated123', false);
            $this->assertIsArray($updateResult);
            $this->assertEquals('Updated School', $updateResult['school_Name']);
            $this->assertEquals('updated123', $updateResult['school_token']);
            $this->assertFalse($updateResult['school_allowSite']);

            // Test de suppression de l'école mise à jour
            $deleteResult = $this->schools->deleteSchool($schoolId);
            $this->assertTrue($deleteResult);
        } else {
            $this->fail('School ID is not set in the createSchool response.');
        }
    }
}
