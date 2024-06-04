<?php
// Auteur : Capdrake (Bastien LEUWERS)

use PHPUnit\Framework\TestCase;
use GeSign\Buildings;
use GeSign\Schools;
use GeSign\Auth;

class BuildingsTest extends TestCase
{
    private $buildings;
    private $schools;
    private $auth;
    private $token;

    protected function setUp(): void
    {
        $this->auth = new Auth();
        $loginResponse = $this->auth->login('test3@gmail.com', 'test');
        if (isset($loginResponse['token'])) {
            $this->token = $loginResponse['token'];
        } else {
            throw new \Exception('Authentification échouée : ' . json_encode($loginResponse));
        }
        $this->buildings = new Buildings($this->token);
        $this->schools = new Schools();
    }

    public function testFetchBuildings()
    {
        $result = $this->buildings->fetchBuildings();
        $this->assertIsArray($result);
    }

    public function testFetchBuildingsBySchoolId()
    {
        // Créer une école fictive qu'on supprime après
        $school = $this->schools->createSchool('Test School', 'test123', true);
        $schoolId = $school['school_Id'];

        // Test de récupération des bâtiments par ID d'école
        $result = $this->buildings->fetchBuildingsBySchoolId($schoolId);
        $this->assertIsArray($result);

        // Suppression de l'école créée
        $deleteSchoolResult = $this->schools->deleteSchool($schoolId);
        $this->assertTrue($deleteSchoolResult);
    }

    public function testCreateAndDeleteBuilding()
    {
        // Créer une école fictive qu'on supprime après
        $school = $this->schools->createSchool('Test School', 'test123', true);
        $schoolId = $school['school_Id'];

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

        // Suppression de l'école créée
        $deleteSchoolResult = $this->schools->deleteSchool($schoolId);
        $this->assertTrue($deleteSchoolResult);
    }
}
