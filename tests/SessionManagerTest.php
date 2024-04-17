<?php
// Auteur : Capdrake (Bastien LEUWERS)

use PHPUnit\Framework\TestCase;
use GeSign\SessionManager;

class SessionManagerTest extends TestCase
{
    public function testUserNotLoggedIn()
    {
        $sessionManager = $this->getMockBuilder(SessionManager::class)
                               ->onlyMethods(['redirectToLogin'])
                               ->getMock();

        $sessionManager->expects($this->once())
                       ->method('redirectToLogin');

        $_SESSION['user_logged_in'] = false;

        $sessionManager->checkUserLoggedIn();
    }

    public function testUserLoggedIn()
    {
        $sessionManager = $this->getMockBuilder(SessionManager::class)
                               ->onlyMethods(['redirectToDashboard'])
                               ->getMock();

        $sessionManager->expects($this->once())
                       ->method('redirectToDashboard');

        $_SESSION['user_logged_in'] = true;

        $sessionManager->checkUserLoggedIn();
    }
}
