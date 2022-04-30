<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Account $account
 */
$this->assign('title', "口座詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body d-grid">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
          <tr>
            <th scope="row">ID</th>
            <td><?= h($account->id) ?></td>
          </tr>
          <tr>
            <th scope="row">口座名</th>
            <td><?= h($account->name) ?></td>
          </tr>
          <tr>
            <th scope="row">初期資産額</th>
            <td><?= $this->Number->format($account->init_record) ?>円</td>
          </tr>
          <tr>
            <th scope="row">作成日時</th>
            <td><?= h($account?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($account?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

