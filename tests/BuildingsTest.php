<?php
//Auteur : Capdrake (Bastien LEUWERS)

use PHPUnit\Framework\TestCase;
use GeSign\Buildings;

class BuildingsTest extends TestCase
{
    private $buildings;

    protected function setUp(): void
    {
        $this->buildings = new Buildings();
    }

    public function testFetchBuildings()
    {
        $result = $this->buildings->fetchBuildings();
        $this->assertIsArray($result);
    }

    public function testCreateAndDeleteBuilding()
    {
        // Créer une école fictive qu'on supprime après
        $school = [
            'school_Id' => 0,
            'school_Name' => 'Test School',
            'school_token' => 'test123',
            'school_allowSite' => true,
            'school_Date' => date('Y-m-d\TH:i:s')
        ];

        // Test de création d'un bâtiment
        $createResult = $this->buildings->createBuilding('Test City', 'Test Building', '123 Test Address', $school);
        $this->assertIsArray($createResult);
        $this->assertEquals('Test Building', $createResult['bulding_Name']);

        // Test de récupération du bâtiment par ID
        $buildingId = $createResult['bulding_Id'];
        $fetchResult = $this->buildings->fetchBuildingById($buildingId);
        $this->assertIsArray($fetchResult);
        $this->assertEquals($buildingId, $fetchResult['bulding_Id']);

        // Test de mise à jour du bâtiment
        $updateResult = $this->buildings->updateBuilding($buildingId, 'Updated City', 'Updated Building', '456 Updated Address', $school);
        $this->assertTrue($updateResult);

        // Test de suppression du bâtiment
        $deleteResult = $this->buildings->deleteBuilding($buildingId);
        $this->assertTrue($deleteResult);
    }
}
