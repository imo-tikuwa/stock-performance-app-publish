<?php
use Cake\Core\Configure;

/**
 * 設定データを取得する
 *
 * @param $code_key
 * @param $default
 * @return bool|mixed
 */
function _code($code_key, $default = null)
{
    return Configure::read($code_key, $default);
}