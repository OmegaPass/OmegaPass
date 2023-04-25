<?php

use PHPUnit\Framework\TestCase;

$phpUnitTestMode = true;

require_once __DIR__ . '/../db.php';

class DbTest extends TestCase {
    private DataBase $database;

    public function setUp(): void
    {
        $this->database = new DataBase();
    }

    public function testAddUser() {
        $result = $this->database->add_user('testuser', 'testpassword');
        $this->assertEquals("Success", $result);
    }

    public function testDuplicateUsername() {
        $this->database->add_user('testuser', 'testpassword');
        $result = $this->database->add_user('testuser', 'testpassword');
        $this->assertEquals("Username already taken", $result);
    }

    public function testLogin() {
        $this->database->add_user('testuser', 'testpassword');
        $result1 = $this->database->login('testuser', 'testpassword');
        $result2 = $this->database->login('testuser', 'wrongpassword');
        $this->assertEquals("Success", $result1);
        $this->assertEquals("Wrong password", $result2);
    }

    public function testAddPassword() {
        $this->database->add_user('testuser', 'testpassword');
        $this->database->login('testuser', 'testpassword');
        $_SESSION['username'] = 'testuser';
        $_SESSION['masterpass'] = 'testpassword';
        $userid = $this->database->getUserId();
        $this->database->add_password($userid, 'testwebsite', 'testusername', 'testpassword');
        $data = $this->database->get_all_entries($userid);
        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('testwebsite', $data[0]['website']);
        $this->assertEquals('testusername', $data[0]['username']);
        $this->assertEquals('testpassword', $data[0]['password']);
    }

    public function testChangeUsername() {
        $this->database->add_user('testuser', 'testpassword');
        $this->database->login('testuser', 'testpassword');
        $userid = $this->database->getUserId();
        $this->database->changeUsername($userid, 'newusername');
        $_SESSION['username'] = 'newusername';
        $result = $this->database->login('newusername', 'testpassword');
        $this->assertEquals("Success", $result);
    }
}
?>