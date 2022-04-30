<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Deposit[] $deposits
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "入出金");
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
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deposits-search-form-modal">検索</a>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
            <?php } ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle d-lg-none" href="#" role="button" id="sp-action-link" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">アクション</a>
            <div class="dropdown-menu" aria-labelledby="sp-action-link">
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
                <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
              <?php } ?>
              <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deposits-search-form-modal">検索</a>
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
              <th scope="col"><?= $this->Paginator->sort('deposit_date', '入出金日') ?></th>
              <th scope="col"><?= $this->Paginator->sort('deposit_amount', '入出金額') ?></th>
              <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
              <th scope="col" class="actions">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($deposits as $deposit) { ?>
              <tr>
                <td><?= $this->Html->link($deposit->id, ['action' => ACTION_VIEW, $deposit->id]) ?></td>
                <td><?= h($deposit?->deposit_date?->i18nFormat('yyyy/MM/dd')) ?></td>
                <td><?= $this->Number->format($deposit->deposit_amount) ?>円</td>
                <td><?= h($deposit?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
                <td class="actions">
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                    <?= $this->Html->link('<i title="詳細" class="far fa-list-alt me-1"></i>', ['action' => ACTION_VIEW, $deposit->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                    <?= $this->Html->link('<i title="編集" class="fas fa-pen me-1"></i>', ['action' => ACTION_EDIT, $deposit->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_DELETE])) { ?>
                    <?= $this->Form->postLink('<i title="削除" class="fas fa-trash"></i>', ['action' => ACTION_DELETE, $deposit->id], ['escape' => false, 'method' => 'delete', 'confirm' => __('ID {0} を削除します。よろしいですか？', $deposit->id)]) ?>
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

<div class="modal search-form fade" id="deposits-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="deposits-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">入出金検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'deposits-search-form']) ?>
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
                <?= $this->Form->control('deposit_date_from', [
                  'id' => 'deposit-date-from-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '入出金日From',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#deposit-date-from-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('deposit_date_to', [
                  'id' => 'deposit-date-to-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '入出金日To',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#deposit-date-to-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <div class="input number">
                  <label for="deposit_amount_from" class="col-form-label col-form-label-sm">入出金額From</label>
                  <div class="input-group input-group-sm">
                    <?= $this->Form->text('deposit_amount_from', [
                      'id' => 'deposit_amount_from',
                      'type' => 'number',
                      'class' => 'form-control form-control-sm rounded-0',
                      'label' => '入出金額From',
                      'min' => '-100000000',
                      'max' => '100000000',
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
                  <label for="deposit_amount_to" class="col-form-label col-form-label-sm">入出金額To</label>
                  <div class="input-group input-group-sm">
                    <?= $this->Form->text('deposit_amount_to', [
                      'id' => 'deposit_amount_to',
                      'type' => 'number',
                      'class' => 'form-control form-control-sm rounded-0',
                      'label' => '入出金額To',
                      'min' => '-100000000',
                      'max' => '100000000',
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

<?= $this->Html->script('admin/deposits_index', ['block' => true, 'charset' => 'UTF-8']) ?>
