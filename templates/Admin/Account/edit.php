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
$label_class = 'form-label col-form-label col-form-label-sm';
$this->Form->setTemplates([
  'nestingLabel' => '{{hidden}}{{input}}<label class="form-check-label col-form-label col-form-label-sm" {{attrs}}>{{text}}</label>'
]);
$password_value = ($this->getRequest()->getParam('action') === 'edit' && $this->getRequest()->is(['get'])) ? $admin->raw_password : $this->getRequest()->getParam('password');
?>
<div class="col">
  <div class="card rounded-0">
    <div class="card-body">
      <?= $this->Form->create($admin) ?>
      <!-- dummy input. -->
      <input type="password" class="d-none" />
      <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'name', 'label' => '名前', 'require' => true, 'class' => $label_class]); ?>
            <?= $this->Form->control('name', ['class' => $input_class, 'label' => false, 'maxlength' => '255', 'required' => false, 'error' => false]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'mail', 'label' => 'メールアドレス', 'require' => true, 'class' => $label_class]); ?>
            <?= $this->Form->control('mail', ['class' => $input_class, 'label' => false, 'maxlength' => '255', 'required' => false, 'error' => false]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'password', 'label' => 'パスワード', 'require' => true, 'class' => $label_class]); ?>
            <?= $this->Form->control('password', ['class' => $input_class, 'label' => false, 'maxlength' => '20', 'value' => $password_value, 'required' => false, 'error' => false]); ?>
            <label class="text-info" id="password-toggle-label"><input type="checkbox" id="password-toggle"/> パスワードを表示</label>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'use_otp', 'label' => '二段階認証', 'require' => false, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="form-check">
              <?= $this->Form->control('use_otp', [
                'type' => 'checkbox',
                'id' => 'use_otp',
                'value' => '1',
                'class' => 'adm-checkbox',
                'label' => '二段階認証を使用する',
                'hiddenField' => true,
                'required' => false,
                'error' => false
              ]); ?>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'update-api-token', 'label' => 'OpenAPIトークン', 'require' => false, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->select('mode_api_token', $api_token_selections, [
              'id' => 'update-api-token',
              'value' => true,
              'class' => $input_class,
              'label' => false,
              'empty' => '　',
              'required' => false,
              'error' => false
            ]); ?>
          </div>
        </div>
        <?php if (SUPER_USER_ID != $admin->id) { ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <?= $this->element('Parts/label', ['field' => 'privilege', 'label' => '権限', 'require' => false, 'class' => $label_class]); ?>
            <div id="privilege-form" class="mb-3">
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
