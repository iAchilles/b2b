<?php
namespace App\Services\Security;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

/**
 * TokenService class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class TokenService
{
    /**
     * Default expiration time for a token. It means that the token will be considered as invalid after that time.
     */
    const EXPIRATION_TIME = 1800;

    /**
     * Secret key which will be used in HMAC algorithm.
     */
    const SIGNER_KEY = 'paramountZ1W2';


    /**
     * Returns string representation of the JWT token.
     *
     * @param string $id User identifier.
     * @param int $expiration Expiration time.
     * @param string $signerKey Secret key.
     * @return string String representation of the JWT token.
     */
    public static function issue(string $id, int $expiration = self::EXPIRATION_TIME, string $signerKey = self::SIGNER_KEY) : string
    {
        $issuedTime = time();
        $expiration = time() + $expiration;

        $token = (new Builder())
            ->setNotBefore($issuedTime)
            ->setExpiration($expiration)
            ->set('uid', $id)
            ->sign(self::getSigner(), $signerKey);

        return (string) $token->getToken();
    }


    /**
     * Reissues jwt token.
     * @param string $token String representation of the current jwt token.
     * @param int $expiration Expiration time.
     * @param string $signerKey Secret key.
     * @return null|string String representation of the jwt token.
     */
    public static function reissue(string $token, int $expiration = self::EXPIRATION_TIME, string $signerKey = self::SIGNER_KEY)
    {
        $token = self::fromString($token, $signerKey);
        if (!is_null($token)) {
            $id = $token->getClaim('uid');
            return self::issue($id, $expiration, $signerKey);
        }

        return null;
    }


    /**
     * Returns Token object if the given string is a valid representation of the token, otherwise it returns null.
     * @param string $token String representation of the JWT token
     * @param string $signerKey Secret key
     * @return Token|null
     */
    public static function fromString(string $token, string $signerKey = self::SIGNER_KEY)
    {
        $parser = new Parser();

        try {
            $token = $parser->parse($token);
        } catch (\Throwable $throwable) {
            return null;
        }

        if (!self::verify($token, $signerKey)) {
            return null;
        }

        if (!self::validate($token)) {
            return null;
        }

        return $token;
    }


    /**
     * Returns true if the signature is valid, false otherwise.
     * @param Token $token
     * @param string $signerKey
     * @return bool
     */
    private static function verify(Token $token, $signerKey = self::SIGNER_KEY) : bool
    {
        return $token->verify(self::getSigner(), $signerKey);
    }


    /**
     * Returns true if claims are valid, otherwise false.
     * @param Token $token
     * @return bool
     */
    private static function validate(Token $token) : bool
    {
        $validator = new ValidationData();

        return $token->validate($validator);
    }


    /**
     * @return Sha256
     */
    private static function getSigner()
    {
        return (new Sha256());
    }
}
