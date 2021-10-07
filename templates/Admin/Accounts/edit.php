<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Account $account
 */
$button_name = (!empty($account) && !$account->isNew()) ? "更新" : "登録";
$this->assign('title', "口座{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($account) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'name', 'label' => '口座名', 'require' => true, 'class' => 'item-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('name', [
              'type' => 'text',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false
            ]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'init_record', 'label' => '初期資産額', 'require' => true, 'class' => 'item-label col-form-label col-form-label-sm']); ?>
            <div class="input number">
              <div class="input-group input-group-sm">
                <?= $this->Form->text('init_record', [
                  'type' => 'number',
                  'id' => 'init_record',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => false,
                  'min' => '0',
                  'max' => '1000000000',
                  'step' => '1',
                  'required' => false,
                  'error' => false
                ]); ?>
                <div class="input-group-append"><span class="input-group-text rounded-0">円</span></div>
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

