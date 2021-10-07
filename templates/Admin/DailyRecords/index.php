<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DailyRecord[] $daily_records
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "資産記録");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <div class="form-inline">
        <div class="btn-group mr-2" role="group">
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
          <?php } ?>
          <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="javascript:void(0);" data-toggle="modal" data-target="#daily_records-search-form-modal">検索</a>
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
          <?php } ?>
          <a class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle d-md-none" href="#" role="button" id="sp-action-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">アクション</a>
          <div class="dropdown-menu" aria-labelledby="sp-action-link">
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
            <?php } ?>
            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#daily_records-search-form-modal">検索</a>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover text-sm text-nowrap">
          <thead>
            <tr>
              <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
              <th scope="col"><?= $this->Paginator->sort('account_id', '口座名') ?></th>
              <th scope="col"><?= $this->Paginator->sort('day', '日付') ?></th>
              <th scope="col"><?= $this->Paginator->sort('record', '資産額') ?></th>
              <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
              <th scope="col" class="actions">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($daily_records as $daily_record) { ?>
              <tr>
                <td><?= $this->Html->link($daily_record->id, ['action' => ACTION_VIEW, $daily_record->id]) ?></td>
                <td><?= $daily_record->has('account') ? $this->Html->link($daily_record->account->name, ['controller' => 'Accounts', 'action' => ACTION_VIEW, $daily_record->account->id]) : '' ?></td>
                <td><?= h($daily_record?->day?->i18nFormat('yyyy/MM/dd')) ?></td>
                <td><?= $this->Number->format($daily_record->record) ?>円</td>
                <td><?= h($daily_record?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
                <td class="actions">
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                    <?= $this->Html->link('<i title="詳細" class="far fa-list-alt mr-1"></i>', ['action' => ACTION_VIEW, $daily_record->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                    <?= $this->Html->link('<i title="編集" class="fas fa-pen mr-1"></i>', ['action' => ACTION_EDIT, $daily_record->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_DELETE])) { ?>
                    <?= $this->Form->postLink('<i title="削除" class="fas fa-trash"></i>', ['action' => ACTION_DELETE, $daily_record->id], ['escape' => false, 'method' => 'delete', 'confirm' => __('ID {0} を削除します。よろしいですか？', $daily_record->id)]) ?>
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

<div class="modal search-form fade" id="daily_records-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="daily_records-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">資産記録検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'daily_records-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
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
              <div class="form-group">
                <?= $this->Form->control('account_id', [
                  'id' => 'account-id',
                  'type' => 'select',
                  'options' => $account_id_list,
                  'class' => 'form-control form-control-sm',
                  'label' => '口座名',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('day_from', [
                  'id' => 'day-from-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '日付From',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#day-from-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('day_to', [
                  'id' => 'day-to-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '日付To',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#day-to-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <div class="input number">
                  <label for="record_from" class="col-form-label col-form-label-sm">資産額From</label>
                  <div class="input-group input-group-sm">
                    <?= $this->Form->text('record_from', [
                      'id' => 'record_from',
                      'type' => 'number',
                      'class' => 'form-control form-control-sm rounded-0',
                      'label' => '資産額From',
                      'min' => '0',
                      'max' => '1000000000',
                      'step' => '1',
                    ]); ?>
                    <div class="input-group-append"><span class="input-group-text rounded-0">円</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <div class="input number">
                  <label for="record_to" class="col-form-label col-form-label-sm">資産額To</label>
                  <div class="input-group input-group-sm">
                    <?= $this->Form->text('record_to', [
                      'id' => 'record_to',
                      'type' => 'number',
                      'class' => 'form-control form-control-sm rounded-0',
                      'label' => '資産額To',
                      'min' => '0',
                      'max' => '1000000000',
                      'step' => '1',
                    ]); ?>
                    <div class="input-group-append"><span class="input-group-text rounded-0">円</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button('検索', ['class' => 'btn btn-sm btn-flat btn-outline-secondary btn-block']) ?>
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

<?= $this->Html->script('admin/daily_records_index', ['block' => true, 'charset' => 'UTF-8']) ?>
