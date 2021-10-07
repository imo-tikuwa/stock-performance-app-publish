<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DailyRecord $daily_record
 */
$this->assign('title', "資産記録詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
          <tr>
            <th scope="row">ID</th>
            <td><?= h($daily_record->id) ?></td>
          </tr>
          <tr>
            <th scope="row">口座名</th>
            <td><?= $daily_record->has('account') ? h($daily_record->account->name) : '' ?></td>
          </tr>
          <tr>
            <th scope="row">日付</th>
            <td><?= h($daily_record?->day?->i18nFormat('yyyy/MM/dd')) ?></td>
          </tr>
          <tr>
            <th scope="row">資産額</th>
            <td><?= $this->Number->format($daily_record->record) ?>円</td>
          </tr>
          <tr>
            <th scope="row">作成日時</th>
            <td><?= h($daily_record?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($daily_record?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

