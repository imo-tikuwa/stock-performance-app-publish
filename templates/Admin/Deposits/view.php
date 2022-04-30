<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Deposit $deposit
 */
$this->assign('title', "入出金詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body d-grid">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
          <tr>
            <th scope="row">ID</th>
            <td><?= h($deposit->id) ?></td>
          </tr>
          <tr>
            <th scope="row">入出金日</th>
            <td><?= h($deposit?->deposit_date?->i18nFormat('yyyy/MM/dd')) ?></td>
          </tr>
          <tr>
            <th scope="row">入出金額</th>
            <td><?= $this->Number->format($deposit->deposit_amount) ?>円</td>
          </tr>
          <tr>
            <th scope="row">作成日時</th>
            <td><?= h($deposit?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($deposit?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

