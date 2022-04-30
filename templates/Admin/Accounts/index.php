<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Account[] $accounts
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "口座");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header bg-body">
      <div class="row">
        <div class="col-auto">
          <div class="btn-group" role="group">
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
            <?php } ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#accounts-search-form-modal">検索</a>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
            <?php } ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle d-lg-none" href="#" role="button" id="sp-action-link" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">アクション</a>
            <div class="dropdown-menu" aria-labelledby="sp-action-link">
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
                <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
              <?php } ?>
              <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#accounts-search-form-modal">検索</a>
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
                <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-0 d-grid">
      <div class="table-responsive">
        <table class="table table-sm table-hover text-sm text-nowrap">
          <thead>
            <tr>
              <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
              <th scope="col"><?= $this->Paginator->sort('name', '口座名') ?></th>
              <th scope="col"><?= $this->Paginator->sort('init_record', '初期資産額') ?></th>
              <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
              <th scope="col" class="actions">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($accounts as $account) { ?>
              <tr>
                <td><?= $this->Html->link($account->id, ['action' => ACTION_VIEW, $account->id]) ?></td>
                <td><?= h($account->name) ?></td>
                <td><?= $this->Number->format($account->init_record) ?>円</td>
                <td><?= h($account?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
                <td class="actions">
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                    <?= $this->Html->link('<i title="詳細" class="far fa-list-alt me-1"></i>', ['action' => ACTION_VIEW, $account->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                    <?= $this->Html->link('<i title="編集" class="fas fa-pen me-1"></i>', ['action' => ACTION_EDIT, $account->id], ['escape' => false]) ?>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?= $this->element('pager') ?>
</div>

<div class="modal search-form fade" id="accounts-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="accounts-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">口座検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'accounts-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('id', [
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'ID',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('name', [
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '口座名',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <div class="input number">
                  <label for="init_record_from" class="col-form-label col-form-label-sm">初期資産額From</label>
                  <div class="input-group input-group-sm">
                    <?= $this->Form->text('init_record_from', [
                      'id' => 'init_record_from',
                      'type' => 'number',
                      'class' => 'form-control form-control-sm rounded-0',
                      'label' => '初期資産額From',
                      'min' => '0',
                      'max' => '1000000000',
                      'step' => '1',
                    ]); ?>
                    <div class="input-group-text">円</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <div class="input number">
                  <label for="init_record_to" class="col-form-label col-form-label-sm">初期資産額To</label>
                  <div class="input-group input-group-sm">
                    <?= $this->Form->text('init_record_to', [
                      'id' => 'init_record_to',
                      'type' => 'number',
                      'class' => 'form-control form-control-sm rounded-0',
                      'label' => '初期資産額To',
                      'min' => '0',
                      'max' => '1000000000',
                      'step' => '1',
                    ]); ?>
                    <div class="input-group-text">円</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3 d-grid">
                <?= $this->Form->button('検索', ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
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

