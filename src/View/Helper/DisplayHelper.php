<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Display helper
 */
class DisplayHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Bootstrap4のカラーCSSのクラスを付与した文字列を返す
     * マイナスで始まるとき赤字となる
     *
     * @param string $value 数値文字列
     * @return string
     */
    public function color($value)
    {
        $color_code = 'text-success';
        if (str_starts_with($value, '-')) {
            $color_code = 'text-danger';
        }
        return "<span class='{$color_code}'>{$value}</span>";
    }
}
