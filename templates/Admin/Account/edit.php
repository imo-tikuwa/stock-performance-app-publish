<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin $admin
 */
$button_name = (!empty($admin) && !$admin->isNew()) ? "更新" : "登録";
$this->assign('title', "アカウント/権限{$button_name}");
// $text_class = '';
$table_class = 'table table-hover text-sm text-nowrap';
$input_class = 'form-control form-control-sm rounded-0';
$btn_class = 'btn btn-sm btn-flat btn-outline-secondary';
$label_class = 'item-label col-form-label col-form-label-sm';
$this->Form->setTemplates([
  'nestingLabel' => '{{hidden}}{{input}}<label class="form-check-label col-form-label col-form-label-sm" {{attrs}}>{{text}}</label>'
]);
$password_value = $this->getRequest()->is(['patch', 'post', 'put']) ? $this->getRequest()->getParam('password') : $admin->raw_password;
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-body">
      <?= $this->Form->create($admin) ?>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'name', 'label' => '名前', 'require' => true, 'class' => $label_class]); ?>
            <?= $this->Form->control('name', ['class' => $input_class, 'label' => false, 'maxlength' => '255', 'required' => false, 'error' => false]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'mail', 'label' => 'メールアドレス', 'require' => true, 'class' => $label_class]); ?>
            <?= $this->Form->control('mail', ['class' => $input_class, 'label' => false, 'maxlength' => '255', 'required' => false, 'error' => false]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'password', 'label' => 'パスワード', 'require' => true, 'class' => $label_class]); ?>
            <?= $this->Form->control('password', ['class' => $input_class, 'label' => false, 'maxlength' => '20', 'value' => $password_value, 'required' => false, 'error' => false]); ?>
            <label class="text-info" id="password-toggle-label"><input type="checkbox" id="password-toggle"/> パスワードを表示</label>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'use_otp', 'label' => '二段階認証', 'require' => false, 'class' => 'item-label col-form-label col-form-label-sm']); ?>
            <div class="form-check form-check-inline">
              <?= $this->Form->control('use_otp', ['type' => 'checkbox', 'id' => 'use_otp', 'value' => '1', 'class' => 'form-check-input ', 'label' => '二段階認証を使用する', 'hiddenField' => true, 'required' => false, 'error' => false]); ?>
            </div>
          </div>
        </div>
        <?php if (SUPER_USER_ID != $admin->id) { ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <?= $this->element('Parts/label', ['field' => 'privilege', 'label' => '権限', 'require' => false, 'class' => $label_class]); ?>
            <div id="privilege-form" class="form-group">
              <?= $this->makePrivilegeEditHtml($admin->privilege); ?>
            </div>
          </div>
        <?php } ?>
        <div class="col-md-12">
          <?= $this->Form->button($button_name, ['class' => $btn_class]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/account_edit', ['block' => true, 'charset' => 'UTF-8']) ?>