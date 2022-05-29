<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Utils\Encrypter;

/**
 * Admin Entity
 *
 * @property int $id
 * @property string $name
 * @property string $mail
 * @property string $password
 * @property bool $use_otp
 * @property string $otp_secret
 * @property array|null $privilege
 * @property string|null $api_token
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 */
class Admin extends AppEntity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<bool>
     */
    protected $_accessible = [
        'name' => true,
        'mail' => true,
        'password' => true,
        'use_otp' => true,
        'otp_secret' => true,
        'privilege' => true,
        'created' => true,
        'api_token' => true,
        'modified' => true,
        'deleted' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * パスワードを暗号化する
     *
     * @param string $password 暗号化されていないパスワード文字列
     * @return string|null 暗号化されたパスワード文字列 or null
     */
    protected function _setPassword($password)
    {
        if (mb_strlen($password) <= 0) {
            return null;
        }

        $encrypted_password = Encrypter::encrypt($password);
        assert($encrypted_password !== false);

        return $encrypted_password;
    }

    /**
     * 復号化されたパスワードを返す
     *
     * @return string|null 暗号化されていないパスワード文字列 or null
     */
    protected function _getRawPassword()
    {
        if (mb_strlen($this->password) <= 0) {
            return null;
        }

        $decrypted_password = Encrypter::decrypt($this->password);
        assert($decrypted_password !== false);

        return $decrypted_password;
    }

    /**
     * 二段階認証について有効/無効を返す
     *
     * @return string 有効 or 無効
     */
    protected function _getOtpStatus()
    {
        if ($this->use_otp == 1) {
            return '有効';
        }

        return '無効';
    }
}
