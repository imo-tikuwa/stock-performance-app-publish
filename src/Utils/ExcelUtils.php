<?php
namespace App\Utils;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Excel周りで使用する関数をまとめたUtilクラス
 */
class ExcelUtils
{
    /**
     * ワークシートに設定する選択形式の入力規則を作成＆返す
     * @param string $formula1 入力規則
     * @return DataValidation
     */
    public static function getDataValidation($formula1 = null): DataValidation
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
