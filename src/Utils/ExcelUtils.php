<?php
declare(strict_types=1);

namespace App\Utils;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

/**
 * Excel周りで使用する関数をまとめたUtilクラス
 */
class ExcelUtils
{
    /**
     * ワークシートに設定する選択形式の入力規則を作成＆返す
     *
     * @param string $formula1 入力規則
     * @return \PhpOffice\PhpSpreadsheet\Cell\DataValidation
     */
    public static function getDataValidation(string $formula1): DataValidation
    {
        return (new DataValidation())
        ->setFormula1($formula1)
        ->setType(DataValidation::TYPE_LIST)
        ->setAllowBlank(true)
        ->setShowDropDown(true)
        ->setShowErrorMessage(true)
        ->setErrorStyle(DataValidation::STYLE_STOP);
    }
}
