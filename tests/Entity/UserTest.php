<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user->setEmail('email');
        $user->setRoles(['role']);
        $user->setPassword('password');
        $user->setPseudo('pseudo');

        $this->assertTrue($user->getEmail() === 'email');
        $this->assertTrue($user->getRoles() === ['role', 'ROLE_USER']);
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getPseudo() === 'pseudo');
    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user->setEmail('email');
        $user->setRoles(['role']);
        $user->setPassword('password');
        $user->setPseudo('pseudo');


        $this->assertFalse($user->getEmail() === 'false');
        $this->assertFalse($user->getRoles() === ['false']);
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getPseudo() === 'false');
    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPseudo());
    }

    public function testIsNotEmpty(): void
    {
        $user = new User();

        $this->assertNotEmpty($user->getRoles());
    }
}
