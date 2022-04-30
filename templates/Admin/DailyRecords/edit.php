<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DailyRecord $daily_record
 */
$button_name = (!empty($daily_record) && !$daily_record->isNew()) ? "更新" : "登録";
$this->assign('title', "資産記録{$button_name}");
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($daily_record) ?>
      <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'account_id', 'label' => '口座名', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('account_id', [
              'id' => 'account-id',
              'type' => 'select',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false,
              'options' => $account_id_list,
              'empty' => '　'
            ]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'day-datepicker', 'label' => '日付', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('day', [
              'type' => 'text',
              'id' => 'day-datepicker',
              'required' => false,
              'error' => false,
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'data-toggle' => 'datetimepicker',
              'data-target' => '#day-datepicker',
              'value' => $daily_record?->day?->i18nFormat('yyyy-MM-dd')
            ]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'record', 'label' => '資産額', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="input number">
              <div class="input-group input-group-sm">
                <?= $this->Form->text('record', [
                  'type' => 'number',
                  'id' => 'record',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => false,
                  'min' => '0',
                  'max' => '1000000000',
                  'step' => '1',
                  'required' => false,
                  'error' => false
                ]); ?>
                <div class="input-group-text">円</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <?= $this->Form->button($button_name, ['class' => "btn btn-sm btn-flat btn-outline-secondary"]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/daily_records_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
