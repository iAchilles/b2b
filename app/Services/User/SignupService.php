<?php
namespace App\Services\User;

use App\Entities\User;
use App\Services\Security\TokenService;
use Doctrine\ORM\EntityManager;

/**
 * SignupService class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class SignupService
{

    /**
     * Creates a new user in DB and returns string representation of the jwt token on success, otherwise it returns null.
     * @param array $credentials
     * @return null|string
     */
    public static function signup(array $credentials)
    {
        /**
         * @var EntityManager $manager
         */
        $manager = app('em');
        $user    = new User();
        $user->setUsername($credentials[ 'username' ]);
        $user->setPassword($credentials[ 'password' ]);

        $manager->persist($user);

        try {
            $manager->flush();
        } catch (\Throwable $throwable) {
            return null;
        }

        return TokenService::issue($user->getId()->toString());
    }
}
