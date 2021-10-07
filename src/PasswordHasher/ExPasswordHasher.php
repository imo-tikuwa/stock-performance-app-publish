<?php
namespace App\PasswordHasher;

use App\Utils\Encrypter;
use Authentication\PasswordHasher\PasswordHasherInterface;
use Cake\Auth\AbstractPasswordHasher;

/**
 * 暗号化/復号化を行えるようにするために独自定義したクラス
 * @author tikuwa
 *
 */
class ExPasswordHasher extends AbstractPasswordHasher implements PasswordHasherInterface
{
    /**
     * Default config for this object.
     *
     * ### Options
     *
     * - `hashType` - Hashing algo to use. Valid values are those supported by `$algo`
     *   argument of `password_hash()`. Defaults to null
     *
     * @var array
     */
    protected $_defaultConfig = [
        'hashType' => null,
    ];

    /**
     * パスワードの暗号化
     *
     * @see \Authentication\PasswordHasher\PasswordHasherInterface::hash()
     *
     * @param string $password Plain text password to hash.
     * @return string|false Either the password hash string or false
     */
    public function hash($password): string
    {
        return Encrypter::encrypt($password);
    }

    /**
     * パスワードの一致チェック
     *
     * @see \Authentication\PasswordHasher\PasswordHasherInterface::check()
     *
     * @param string $password フォームで入力したパスワード
     * @param string $hashed_password DBに登録してあるpassword
     * @return bool True if hashes match else false.
     */
    public function check($password, $hashed_password): bool
    {
        if (is_null($hashed_password) || $hashed_password === '') {
            return false;
        }
        return $password === Encrypter::decrypt($hashed_password);
    }
}
