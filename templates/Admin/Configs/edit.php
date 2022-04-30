<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Config $config
 */
$button_name = (!empty($config) && !$config->isNew()) ? "更新" : "登録";
$this->assign('title', "設定{$button_name}");
$this->Form->setTemplates([
  'nestingLabel' => '{{hidden}}{{input}}<label class="form-check-label col-form-label col-form-label-sm" {{attrs}}>{{text}}</label>'
]);
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($config) ?>
      <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'display_only_month', 'label' => '月ごと表示モード', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('display_only_month', [
              'type' => 'radio',
              'options' => _code('Codes.Configs.display_only_month'),
              'label' => false,
              'required' => false,
              'error' => false,
              'default' => '02',
              'hiddenField' => false
            ]); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'display_init_record', 'label' => '初期資産額表示', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('display_init_record', [
              'type' => 'radio',
              'options' => _code('Codes.Configs.display_init_record'),
              'label' => false,
              'required' => false,
              'error' => false,
              'default' => '02',
              'hiddenField' => false
            ]); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'record_total_real_color', 'label' => '実質資産のチャートカラー', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="input text">
              <div class="input-group input-group-sm">
                <div class="input-group-text">#</div>
                <?= $this->Form->text('record_total_real_color', [
                  'type' => 'text',
                  'id' => 'record-total-real-color',
                  'class' => 'form-control form-control-sm rounded-0 ',
                  'label' => false,
                  'required' => false,
                  'error' => false,
                  'maxlength' => '6'
                ]); ?>
                <button class="btn btn-outline-secondary" type="button" id="record-total-real-color-colorpicker-btn"><i class="fas fa-palette"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'init_record_color', 'label' => '初期資産のチャートカラー', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="input text">
              <div class="input-group input-group-sm">
                <div class="input-group-text">#</div>
                <?= $this->Form->text('init_record_color', [
                  'type' => 'text',
                  'id' => 'init-record-color',
                  'class' => 'form-control form-control-sm rounded-0 ',
                  'label' => false,
                  'required' => false,
                  'error' => false,
                  'maxlength' => '6'
                ]); ?>
                <button class="btn btn-outline-secondary" type="button" id="init-record-color-colorpicker-btn"><i class="fas fa-palette"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'chromedriver_path', 'label' => 'ChromeDriverのパス', 'require' => false, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('chromedriver_path', [
              'type' => 'text',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false
            ]); ?>
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

<?= $this->Html->script('admin/configs_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
