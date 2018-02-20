<?php
namespace App\Services\User;

use App\Entities\User;
use App\Services\Security\TokenService;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

/**
 * SigninService class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class SigninService
{


    /**
     * Returns string representation of the jwt token on success, otherwise false.
     * @param array $credentials Associative array with the following required keys: username, password.
     * @return string
     */
    public static function signin(array $credentials)
    {
        /**
         * @var User $user
         */
        $user = self::findUser($credentials);

        if (is_null($user)) {
            abort(401);
        }

        if (self::checkCredentials($user, $credentials)) {
            return TokenService::issue($user->getId()->toString());
        }

        abort(401);
    }


    /**
     * Checks if the hash of the given password is equal with the stored hash in DB.
     * @param User $user
     * @param array $credentials
     * @return bool
     */
    private static function checkCredentials(User $user, array $credentials) : bool
    {
        $storedPassword = $user->getPassword();
        $givenPassword  = $credentials[ 'password' ];

        return Hash::check($givenPassword, $storedPassword);
    }


    /**
     * Returns User object if it is found, otherwise it returns false.
     * @param array $credentials
     * @return null|object
     */
    private static function findUser(array $credentials)
    {
        /**
         * @var EntityManager $manager
         */
        $manager  = app('em');
        $username = $credentials[ 'username' ];

        return $manager->getRepository(User::class)->findOneBy([ 'username' => $username ]);
    }
}
