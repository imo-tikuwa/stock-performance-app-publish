<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Config Entity
 *
 * @property int $id
 * @property string|null $display_only_month
 * @property string|null $display_init_record
 * @property string|null $record_total_real_color
 * @property string|null $init_record_color
 * @property array|null $display_setting
 * @property string|null $chromedriver_path
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Config extends AppEntity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<bool>
     */
    protected $_accessible = [
        'display_only_month' => true,
        'display_init_record' => true,
        'record_total_real_color' => true,
        'init_record_color' => true,
        'display_setting' => true,
        'chromedriver_path' => true,
        'created' => true,
        'modified' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'created',
        'modified',
    ];

    /**
     * 実質資産のチャートカラー 一覧、詳細画面用の文字列を返す
     *
     * @return string
     */
    protected function _getRecordTotalRealColorDisplayValue()
    {
        if (empty($this->record_total_real_color)) {
            return '';
        }

        return '#' . $this->record_total_real_color;
    }

    /**
     * 初期資産のチャートカラー 一覧、詳細画面用の文字列を返す
     *
     * @return string
     */
    protected function _getInitRecordColorDisplayValue()
    {
        if (empty($this->init_record_color)) {
            return '';
        }

        return '#' . $this->init_record_color;
    }
}
