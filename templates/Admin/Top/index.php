<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', '管理画面TOP');
?>
<?php if (!empty($functions) && count($functions) > 0) { ?>
  <?php foreach ($functions as $function) { ?>
    <?php if (AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_INDEX])) { ?>
      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
          <div class="card-header">
            <?= $function['label'] ?>
          </div>
          <div class="card-body">
            <p class="card-text">データ登録数：<?= $function['data_count'] ?></p>
            <div class="btn-group" role="group">
              <?php if (!method_exists("App\\Controller\\Admin\\{$function['controller']}Controller", 'index')) { ?>
                <?php if (AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_EDIT])) { ?>
                  <?= $this->Html->link('編集画面', ['controller' => $function['controller'], 'action' => ACTION_EDIT], ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
                <?php } ?>
              <?php } else { ?>
                <?= $this->Html->link('一覧画面', ['controller' => $function['controller'], 'action' => ACTION_INDEX, '?' => _code("InitialOrders.{$function['controller']}")], ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
                <?php if (AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_ADD])) { ?>
                  <?= $this->Html->link('登録画面', ['controller' => $function['controller'], 'action' => ACTION_ADD], ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
                <?php } ?>
                <?php if ($function['exist_csv_export'] === true && AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_CSV_EXPORT])) { ?>
                  <?= $this->Html->link('CSVエクスポート', ['controller' => $function['controller'], 'action' => ACTION_CSV_EXPORT], ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
                <?php } ?>
                <?php if ($function['exist_excel_export'] === true && AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_EXCEL_EXPORT])) { ?>
                  <?= $this->Html->link('Excelエクスポート', ['controller' => $function['controller'], 'action' => ACTION_EXCEL_EXPORT], ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
<?php }?>
