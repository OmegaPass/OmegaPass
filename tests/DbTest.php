<?php

use PHPUnit\Framework\TestCase;

$phpUnitTestMode = true;

require_once __DIR__ . '/../db.php';

class PasswordManagerTest extends TestCase {

    public function testAddUser() {
        $result = add_user('testuser', 'testpassword');
        $this->assertEquals($result, "Success");
    }

    public function testDuplicateUsername() {
        add_user('testuser', 'testpassword');
        $result = add_user('testuser', 'testpassword');
        $this->assertEquals($result, "Username already taken");
    }

    public function testLogin() {
        add_user('testuser', 'testpassword');
        $result1 = login('testuser', 'testpassword');
        $result2 = login('testuser', 'wrongpassword');
        $this->assertEquals($result1, "Success");
        $this->assertEquals($result2, "Wrong password");
    }

    public function testAddPassword() {
        add_user('testuser', 'testpassword');
        login('testuser', 'testpassword');
        $_SESSION['masterpass'] = 'testmasterpass';
        $userid = getUserId();
        add_password($userid, 'testwebsite', 'testusername', 'testpassword');
        $data = get_all_entries($userid);
        $this->assertEquals(count($data), 1);
        $this->assertEquals($data[0]['website'], 'testwebsite');
        $this->assertEquals($data[0]['username'], 'testusername');
        $this->assertEquals(decrypt($data[0]['password'], $_SESSION['masterpass']), 'testpassword');
    }

    public function testChangeUsername() {
        add_user('testuser', 'testpassword');
        login('testuser', 'testpassword');
        $userid = getUserId();
        changeUsername($userid, 'newusername');
        $_SESSION['username'] = 'newusername';
        $result = login('newusername', 'testpassword');
        $this->assertEquals($result, "Success");
    }

}
?>