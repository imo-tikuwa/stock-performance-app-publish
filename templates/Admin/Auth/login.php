<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', "ログイン");
$is_secure_login = ($this->getRequest()->getParam('action') === 'secureLogin');
?>
<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->fetch('title') ?></title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->meta('robots', ['content' => 'noindex']) ?>
  <?= $this->Html->css('vendor/bundle') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
</head>
<body>
<main class="d-flex justify-content-center align-items-center" style="height: 100vh">
  <div class="card" style="width: 400px">
    <div class="card-header">
      <div class="login-logo">
        <b><?= SITE_NAME ?></b> Login
      </div>
    </div>
    <div class="card-body">
      <?= $this->Flash->render() ?>

      <?= $this->Form->create(null) ?>
        <div class="mb-3 has-feedback">
          <?= $this->Form->control('mail', ['id' => 'login-mail', 'class' => 'form-control rounded-0', 'label' => 'ログインID']) ?>
        </div>
        <div class="mb-3 has-feedback">
          <?= $this->Form->control('password', ['id' => 'login-password', 'class' => 'form-control rounded-0', 'label' => 'パスワード']) ?>
        </div>
        <?php if ($is_secure_login) { ?>
          <div class="mb-3 has-feedback">
            <?= $this->Form->control(GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME, ['id' => 'login-secret', 'type' => 'text', 'class' => 'form-control rounded-0', 'label' => '認証コード', 'maxlength' => 6]) ?>
          </div>
        <?php } ?>
        <div class="row">
          <div class="col-12">
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-flat">ログイン</button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 mt-2 text-end">
            <?php if (!$is_secure_login) { ?>
              <?= $this->Html->link('二段階認証が有効なアカウントはこちら', ['action' => 'secureLogin', '?' => $this->getRequest()->getQueryParams()]) ?>
            <?php } else { ?>
              <?= $this->Html->link('通常のログインはこちら', ['action' => 'login', '?' => $this->getRequest()->getQueryParams()]) ?>
            <?php } ?>
          </div>
        </div>
      <?= $this->Form->end() ?>

    </div>
  </div>
</main>
<?= $this->Html->script('vendor/bundle') ?>
<?= $this->fetch('script') ?>
</body>
</html>
