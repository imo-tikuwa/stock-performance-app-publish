<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[] $accounts
 * @var \App\Form\SearchForm $search_form
 */

use App\Utils\AuthUtils;

$this->assign('title', "アカウント/権限");
// $text_class = '';
$table_class = 'table table-sm table-hover text-sm text-nowrap';
$input_class = 'form-control form-control-sm rounded-0';
$btn_class = 'btn btn-sm btn-flat btn-outline-secondary';
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
$google_authenticator = new PHPGangsta_GoogleAuthenticator();
?>
<div class="col">
  <div class="card rounded-0">
    <div class="card-header bg-body">
      <div class="row">
        <div class="col-auto">
          <div class="btn-group me-2" role="group">
            <a class="<?= h($btn_class) ?>" href="<?= $this->Url->build(['action' => 'add']) ?>">新規登録</a>
            <a class="<?= h($btn_class) ?>" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#accounts-search-form-modal">検索</a>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="<?= h($table_class) ?>">
        <thead>
          <tr>
            <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
            <th scope="col"><?= $this->Paginator->sort('name', '名前') ?></th>
            <th scope="col"><?= $this->Paginator->sort('mail', 'メールアドレス') ?></th>
            <th scope="col"><?= $this->Paginator->sort('use_otp', '二段階認証') ?></th>
            <th scope="col"><?= $this->Paginator->sort('privilege', '権限') ?></th>
            <th scope="col"><?= $this->Paginator->sort('created', '作成日時') ?></th>
            <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
            <th scope="col" class="actions">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($accounts as $account) { ?>
            <tr>
              <td><?= h($account->id) ?></td>
              <td><?= h($account->name) ?></td>
              <td><?= h($account->mail) ?></td>
              <td><?= h($account->otp_status) ?></td>
              <td><?= $this->makePrivilegeListHtml($account->privilege)?></td>
              <td><?= h($account?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
              <td><?= h($account?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
              <td class="actions">
                <?= $this->Html->link('<i title="編集" class="fas fa-pen me-1"></i>', ['action' => ACTION_EDIT, $account->id], ['escape' => false]) ?>
                <?php if ($account->use_otp && is_string($account->otp_secret) && strlen($account->otp_secret) === GOOGLE_AUTHENTICATOR_SECRET_KEY_LEN) { ?>
                  <?= $this->Html->link('<i title="二段階認証用QRコード再表示" class="fas fa-qrcode me-1"></i>', 'javascript:void(0);', [
                    'class' => 'redraw-qr',
                    'data-account-id' => $account->id,
                    'data-qr-url' => $google_authenticator->getQRCodeGoogleUrl(AuthUtils::getTwoFactorQrName($account), $account->otp_secret, SITE_NAME),
                    'escape' => false
                  ]) ?>
                <?php } ?>
                <?= $this->Form->postLink('<i title="削除" class="fas fa-trash"></i>', ['action' => ACTION_DELETE, $account->id], ['escape' => false, 'confirm' => __('ID {0} を削除します。よろしいですか？', $account->id)]) ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <?= $this->element('pager') ?>
</div>

<div class="modal" id="redraw-qr-modal" tabindex="-1" role="dialog" aria-labelledby="redraw-qr-modal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-0">
      <div class="modal-header">
        <h5 class="modal-title">二段階認証用QRコード再表示</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
      </div>
      <div class="modal-body">
        <p id="redraw-qr-target-id" class="mb-0"></p>
        <p>モバイル端末のGoogle Authenticatorで読み込んでください。</p>
        <p class="text-center mt-2"><img id="redraw-qr-img" class="p-2 border" src="" /></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" data-bs-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($qr_url)) { ?>
  <div class="modal" id="qr-modal" tabindex="-1" role="dialog" aria-labelledby="qr-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title">二段階認証用QRコード発行</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>
        <div class="modal-body">
          <p>二段階認証で使用するQRコードが発行されました。<br />モバイル端末のGoogle Authenticatorで読み込んでください。</p>
          <p class="text-center mt-2"><img class="p-2 border" src="<?= $qr_url; ?>" /></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" data-bs-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<div class="modal search-form fade" id="accounts-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="tikuwa_estates-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">アカウント/権限検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'accounts-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('id', [
                  'class' => $input_class,
                  'label' => 'ID',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('name', [
                  'class' => $input_class,
                  'label' => '名前',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('mail', [
                  'class' => $input_class,
                  'label' => 'メールアドレス',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3 d-grid">
                <?= $this->Form->button('検索', ['class' => "{$btn_class}"]) ?>
              </div>
            </div>
          </div>
          <?= $this->Form->hidden('sort') ?>
          <?= $this->Form->hidden('direction') ?>
        <?= $this->Form->end() ?>
      </div>
      <div class="modal-footer">　</div>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/account_index', ['block' => true, 'charset' => 'UTF-8']) ?>
