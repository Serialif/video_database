<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $fakeUsers = [
            [
                'email' => 'admin@admin.fr',
                'pseudo' => 'Admin',
                'password' => 'admin',
                'roles' => ['ROLE_ADMIN']
            ],
            [
                'email' => 'user@user.fr',
                'pseudo' => 'user',
                'password' => 'user',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'demo@demo.fr',
                'pseudo' => 'demo',
                'password' => 'demo',
                'roles' => ['ROLE_DEMO']
            ]
        ];

        foreach ($fakeUsers as $fakeUser) {
            $user = new User();
            $user->setEmail($fakeUser['email']);
            $user->setPseudo($fakeUser['pseudo']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $fakeUser['password']));
            $user->setRoles($fakeUser['roles']);
            $manager->persist($user);
            $manager->flush();
        }
    }
}
