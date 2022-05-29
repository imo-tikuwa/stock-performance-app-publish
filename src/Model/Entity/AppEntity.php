<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * App Entity
 */
class AppEntity extends Entity
{
    /**
     * エラーメッセージの配列を取得する
     *
     * 関連テーブルのエラーはメッセージの先頭にテーブルの項目名と番号を付加する
     *
     * @return array|null エラーメッセージの配列
     */
    public function getErrorMessages()
    {
        if (!$this->hasErrors()) {
            return null;
        }

        $error_messages = [];
        // @phpstan-ignore-next-line
        $related_entity_names = $this->related_entity_names;
        foreach ($this->getErrors() as $field_name => $error) {
            if (is_array($related_entity_names) && array_key_exists($field_name, $related_entity_names)) {
                $error_messages = array_merge($error_messages, $this->getRelationErrorMessages($error, $related_entity_names[$field_name]));
            } else {
                $error_messages[] = $this->getEachErrorMessage($error);
            }
        }

        return $error_messages;
    }

    /**
     * 関連テーブルのエラーメッセージを返す
     *
     * @param array $related_errors 関連テーブルのエラー情報
     * @param string $entity_name 関連テーブルのエンティティオブジェクト
     * @return array 関連テーブルのエラーメッセージの配列
     */
    private function getRelationErrorMessages($related_errors, $entity_name)
    {
        $error_messages = [];
        foreach ($related_errors as $child_index => $related_error) {
            foreach ($related_error as $each_error) {
                $child_num = $child_index + 1;
                // @phpstan-ignore-next-line
                $error_messages[] = "{$entity_name}{$child_num} - " . $this->getEachErrorMessage($each_error);
            }
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
