<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[] $accounts
 * @var \App\Form\SearchForm $search_form
 */

use App\Utils\AuthUtils;

$this->assign('title', "アカウント/権限");
// $text_class = '';
$table_class = 'table table-hover text-sm text-nowrap';
$input_class = 'form-control form-control-sm rounded-0';
$btn_class = 'btn btn-sm btn-flat btn-outline-secondary';
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
$google_authenticator = new PHPGangsta_GoogleAuthenticator();
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <div class="form-inline">
        <div class="btn-group mr-2" role="group">
          <a class="<?= h($btn_class) ?>" href="<?= $this->Url->build(['action' => 'add']) ?>">新規登録</a>
          <a class="<?= h($btn_class) ?>" href="javascript:void(0);" data-toggle="modal" data-target="#accounts-search-form-modal">検索</a>
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
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $account->id ?>" type="button" class="<?= h($btn_class) ?> dropdown-toggle index-dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $account->id ?>">
                    <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $account->id], ['class' => 'dropdown-item']) ?>
                    <?php if ($account->use_otp && is_string($account->otp_secret) && strlen($account->otp_secret) === GOOGLE_AUTHENTICATOR_SECRET_KEY_LEN) { ?>
                      <?= $this->Html->link('二段階認証用QRコード再表示', 'javascript:void(0);', [
                        'class' => 'dropdown-item redraw-qr',
                        'data-account-id' => $account->id,
                        'data-qr-url' => $google_authenticator->getQRCodeGoogleUrl(AuthUtils::getTwoFactorQrName($account), $account->otp_secret, SITE_NAME)
                      ]) ?>
                    <?php } ?>
                    <?= $this->Form->postLink('削除', ['action' => ACTION_DELETE, $account->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $account->id)]) ?>
                  </div>
                </div>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="redraw-qr-target-id" class="mb-0"></p>
        <p>モバイル端末のGoogle Authenticatorで読み込んでください。</p>
        <p class="text-center mt-2"><img id="redraw-qr-img" class="p-2 border" src="" /></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" data-dismiss="modal">閉じる</button>
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
          <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>二段階認証で使用するQRコードが発行されました。<br />モバイル端末のGoogle Authenticatorで読み込んでください。</p>
          <p class="text-center mt-2"><img class="p-2 border" src="<?= $qr_url; ?>" /></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" data-dismiss="modal">閉じる</button>
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
              <div class="form-group">
                <?= $this->Form->control('id', [
                  'class' => $input_class,
                  'label' => 'ID',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('name', [
                  'class' => $input_class,
                  'label' => '名前',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('mail', [
                  'class' => $input_class,
                  'label' => 'メールアドレス',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button('検索', ['class' => "{$btn_class} btn-block"]) ?>
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