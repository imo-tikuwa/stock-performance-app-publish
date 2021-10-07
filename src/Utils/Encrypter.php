<?php

namespace App\Utils;

use Cake\Utility\Security;

/**
 * 暗号化/復号化を行うクラス
 * @author tikuwa
 *
 */
class Encrypter
{
    /** 暗号化用のメソッド */
    private const METHOD = 'AES-256-CBC';

    /**
     * 暗号化
     *
     * @param string $plain_password 暗号化されていないパスワード文字列
     * @return string 暗号化されたパスワード文字列
     */
    public static function encrypt($plain_password = '')
    {
        $iv_size = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($iv_size);

        // 暗号化
        $encrypted_password = openssl_encrypt($plain_password, self::METHOD, Security::getSalt(), OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv) . ':' . base64_encode($encrypted_password);
    }

    /**
     * 復号化
     *
     * @param string $encrypted_password 暗号化されたパスワード文字列
     * @return string 暗号化されていないパスワード文字列
     */
    public static function decrypt($encrypted_password = '')
    {
        $password_data = explode(':', $encrypted_password);
        $iv = base64_decode($password_data[0]);
        $encrypted = base64_decode($password_data[1]);

        // 復号化
        return openssl_decrypt($encrypted, self::METHOD, Security::getSalt(), OPENSSL_RAW_DATA, $iv);
    }
}
