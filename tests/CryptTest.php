<?php

require_once 'vendor/autoload.php';
require_once  __DIR__ . '/../crypt.php';

use PHPUnit\Framework\TestCase;

class CryptTest extends TestCase
{
    public function testGeneratePassword()
    {
        $password = generate_password(10, true, false);
        $this->assertEquals(10, strlen($password), 'Generated password length is not 10');
        $this->assertRegExp('/[a-zA-Z]+/', $password, 'Generated password does not contain letters');
        $this->assertRegExp('/[0-9]+/', $password, 'Generated password does not contain digits');
    }

    public function testGenerateUserId()
    {
        $userId = generate_userid();
        $this->assertEquals(12, strlen($userId), 'Generated user ID length is not 12');
        $this->assertRegExp('/^[a-zA-Z0-9]+$/', $userId, 'Generated user ID contains invalid characters');
    }

    public function testHashPw()
    {
        $password = 'test1234';
        $hashed = hash_pw($password);
        $this->assertTrue(password_verify($password, $hashed), 'Password hash verification failed');
    }

    public function testCheckPw()
    {
        $password = 'test1234';
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $this->assertTrue(check_pw($password, $hashed), 'Password check failed');
    }

    public function testCheckPasswordStrength()
    {
        $strength = check_password_strength('abc123');
        $this->assertEquals('very weak', $strength, 'Password strength check failed');
    }

    public function testEncryptDecrypt()
    {
        $password = 'test1234';
        $masterPass = 'secret';
        $encrypted = encrypt($password, $masterPass);
        $decrypted = decrypt($encrypted, $masterPass);
        $this->assertEquals($password, $decrypted, 'Encryption/decryption failed');
    }
}
