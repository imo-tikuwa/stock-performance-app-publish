<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Deposit $deposit
 */
$button_name = (!empty($deposit) && !$deposit->isNew()) ? "更新" : "登録";
$this->assign('title', "入出金{$button_name}");
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($deposit) ?>
      <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'deposit-date-datepicker', 'label' => '入出金日', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('deposit_date', [
              'type' => 'text',
              'id' => 'deposit-date-datepicker',
              'required' => false,
              'error' => false,
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'data-toggle' => 'datetimepicker',
              'data-target' => '#deposit-date-datepicker',
              'value' => $deposit?->deposit_date?->i18nFormat('yyyy-MM-dd')
            ]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'deposit_amount', 'label' => '入出金額', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="input number">
              <div class="input-group input-group-sm">
                <?= $this->Form->text('deposit_amount', [
                  'type' => 'number',
                  'id' => 'deposit_amount',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => false,
                  'min' => '-100000000',
                  'max' => '100000000',
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

<?= $this->Html->script('admin/deposits_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
