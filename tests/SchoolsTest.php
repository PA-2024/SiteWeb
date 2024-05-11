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

    public function testCreateSchool()
    {
        // Appel direct de createSchool
        $result = $this->schools->createSchool('New School', 'def456', true);

        $this->assertIsArray($result);
        // on vérifie que l'école créée a les bonnes propriétés
        $this->assertEquals('New School', $result['school_Name']);
        $this->assertTrue($result['school_allowSite']);
    }
}
