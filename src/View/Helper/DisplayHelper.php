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
     * @param int|float $sort_value Handsontableのソートに使用する値
     * @return string
     */
    public function color($value, $sort_value = null)
    {
        $color_code = 'text-success';
        if (str_starts_with($value, '-')) {
            $color_code = 'text-danger';
        }
        $sort_value = !is_null($sort_value) ? " data-sort-val='{$sort_value}'" : '';
        return "<span class='{$color_code}'{$sort_value}>{$value}</span>";
    }

    /**
     * Handontableで'renderer' => 'html'のカラムをソートするための<span>要素を返す
     *
     * @param string $value 数値文字列
     * @param int|float $sort_value Handsontableのソートに使用する値
     * @return string
     */
    public function span($value, $sort_value = null)
    {
        $sort_value = !is_null($sort_value) ? " data-sort-val='{$sort_value}'" : '';
        return "<span{$sort_value}>{$value}</span>";
    }
}
