<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Identifier;

use App\PasswordHasher\ExPasswordHasher;
use Authentication\Identifier\PasswordIdentifier;
use PHPGangsta_GoogleAuthenticator;

/**
 * SecurePassword Identifier
 */
class SecurePasswordIdentifier extends PasswordIdentifier
{
    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     *   - `password`: password field.
     * - `resolver` The resolver implementation to use.
     * - `passwordHasher` Password hasher class. Can be a string specifying class name
     *    or an array containing `className` key, any other keys will be passed as
     *    config to the class. Defaults to 'Default'.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            self::CREDENTIAL_USERNAME => 'mail',
            self::CREDENTIAL_PASSWORD => 'password',
        ],
        'resolver' => [
            'className' => 'Authentication.Orm',
            'userModel' => 'Admins',
            'finder' => 'auth',
        ],
        'passwordHasher' => ExPasswordHasher::class,
    ];

    /**
     * @inheritdoc
     */
    public function identify(array $data) // @phpstan-ignore-line
    {
        if (!isset($data[self::CREDENTIAL_USERNAME])) {
            return null;
        }

        $identity = $this->_findIdentity($data[self::CREDENTIAL_USERNAME]);
        if (array_key_exists(self::CREDENTIAL_PASSWORD, $data)) {
            $password = $data[self::CREDENTIAL_PASSWORD];
            if (!$this->_checkPassword($identity, $password)) {
                return null;
            }
        }

        /** @var \App\Model\Entity\Admin $identity */
        // 二段階認証が無効なのに、認証コード付きのフォームから送信されてるときエラーとしてnullを返す
        if (!$identity->use_otp && isset($data[GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME])) {
            return null;
        }

        // 二段階認証が有効かつDBにシークレットキーが登録されているとき認証コードチェック
        if ($identity->use_otp && is_string($identity->otp_secret) && strlen($identity->otp_secret) === GOOGLE_AUTHENTICATOR_SECRET_KEY_LEN) {
            // シークレットキーが存在しなかったらエラーとしてnullを返す
            if (!array_key_exists(GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME, $data)) {
                return null;
            }

            $google_authenticator = new PHPGangsta_GoogleAuthenticator();
            if (!$google_authenticator->verifyCode($identity->otp_secret, $data[GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME])) {
                return null;
            }
        }

        return $identity;
    }
}
