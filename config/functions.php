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

/**
 * 引数のテキストについて改行コードをセパレータとし、配列に変換する
 * 改行コードはCRLF、CR、LFの全てに対応
 *
 * @param string|null $text
 * @return array
 */
function text2array($text = null)
{
    return explode("\n", str_replace(["\r\n", "\r", "\n"], "\n", $text));
}