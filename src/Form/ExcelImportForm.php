<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Exception;
use Laminas\Diactoros\UploadedFile;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Excelインポートフォーム
 */
class ExcelImportForm extends Form
{
    /**
     * 呼び出し元のコントローラ名
     *
     * @var string
     */
    private string $controller;

    /**
     * アップロードされたExcelファイルから読み取ったSpreadsheetオブジェクト
     * バージョンチェックでエラーなしのとき、セットされる
     *
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    private Spreadsheet $spreadsheet;

    /**
     * アップロードされたExcelファイルから読み取ったSpreadsheetオブジェクトを返す
     *
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    /**
     * @inheritDoc
     */
    public function __construct(?string $controller = null, ?EventManager $eventManager = null)
    {
        parent::__construct($eventManager);
        if (is_null($controller)) {
            throw new Exception('ExcelImportForm invalid construct');
        }
        $this->controller = $controller;
    }

    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        $schema->addField('excel_import_file', ['type' => 'file']);

        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('excel_import_file', true, 'ファイルを選択してください')
            ->add('excel_import_file', 'file', [
                'rule' => ['mimeType', [EXCEL_CONTENT_TYPE]],
                'message' => 'アップロードされたファイルのタイプが正しくありません',
                'last' => true,
            ])
            ->add('excel_import_file', 'checkExcelVersion', [
                'rule' => function (UploadedFile $file) {
                    $spreadsheet = (new XlsxReader())->load($file->getStream()->getMetadata('uri'));
                    $version_sheet = $spreadsheet->getSheetByName('VERSION');
                    if (is_null($version_sheet)) {
                        return false;
                    }

                    // プロパティファイルに記録されているExcelバージョンとExcelファイル内VERSIONシートから読み取ったバージョンテキストを比較
                    $baked_version = _code("ExcelOptions.{$this->controller}.version");
                    $excel_version = $version_sheet->getCell('A1')->getValue();
                    $valid = (!empty($baked_version) && !empty($excel_version) && $baked_version === $excel_version);

                    // バリデーションエラーなしのとき、読み込んだ$spreadsheetを呼び出し元で参照できるようにする
                    // $spreadsheetを当該クラス内のメンバ変数としてセットする
                    if ($valid) {
                        $this->spreadsheet = $spreadsheet;
                    }

                    return $valid;
                },
                'message' => 'アップロードされたExcelファイルのバージョンが一致しませんでした',
                'last' => true,
            ]);

        return $validator;
    }

    /**
     * Defines what to execute once the Form is processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }

    /**
     * エラーメッセージの配列を取得する
     *
     * @return array|null エラーメッセージの配列
     */
    public function getErrorMessages()
    {
        $errors = $this->getErrors();
        if (!$errors) {
            return null;
        }

        $error_messages = [];
        foreach ($errors as $error) {
            $error_messages[] = $this->getEachErrorMessage($error);
        }

        return $error_messages;
    }

    /**
     * 1個辺りの項目のエラーメッセージを返す
     *
     * @param array $each_error 1項目辺りのエラー情報
     * @return string|array
     */
    private function getEachErrorMessage($each_error)
    {
        // @phpstan-ignore-next-line
        foreach ($each_error as $error_obj) {
            if (is_array($error_obj)) {
                return $this->getEachErrorMessage($error_obj);
            } else {
                return $error_obj;
            }
        }
    }
}
