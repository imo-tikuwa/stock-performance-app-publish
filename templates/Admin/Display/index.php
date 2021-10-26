<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Config $config
 * @var \App\Model\Entity\DailyRecord[] $daily_records
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "分析");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
$config->display_setting = $config->display_setting ?? [];
?>
<div id="display_index" class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'daily_records-search-form']) ?>
        <div class="form-inline">
          <?php if ($display_data['display_only_month'] === true) { ?>
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="mr-1">期間</label>
                  <?= $this->Form->control('month', [
                    'id' => 'month-datepicker',
                    'type' => 'text',
                    'class' => 'form-control form-control-sm rounded-0 w-75',
                    'label' => false,
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#month-datepicker',
                  ]); ?>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="mr-1">期間</label>
                  <?= $this->Form->control('day_from', [
                    'id' => 'day-from-datepicker',
                    'type' => 'text',
                    'class' => 'form-control form-control-sm rounded-0 w-100',
                    'label' => false,
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#day-from-datepicker',
                  ]); ?>
                </div>
              </div>
            </div>
            &nbsp;～&nbsp;
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="form-group mr-3">
                  <?= $this->Form->control('day_to', [
                    'id' => 'day-to-datepicker',
                    'type' => 'text',
                    'class' => 'form-control form-control-sm rounded-0 w-100',
                    'label' => false,
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#day-to-datepicker',
                  ]); ?>
                </div>
              </div>
            </div>
          <?php } ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group mr-3">
                <label class="mr-1">単位表示</label>
                <?= $this->Form->control('display_unit', [
                  'type' => 'checkbox',
                  'label' => false,
                  'data-toggle' => 'toggle',
                  'data-size' => 'sm',
                  'data-width' => 60,
                  'data-on' => 'ON',
                  'data-off' => 'OFF',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group mr-3">
                <label class="mr-1">入出金を含める</label>
                <?= $this->Form->control('include_deposit', [
                  'type' => 'checkbox',
                  'label' => false,
                  'data-toggle' => 'toggle',
                  'data-size' => 'sm',
                  'data-width' => 60,
                  'data-on' => 'ON',
                  'data-off' => 'OFF',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 col-sm-1">
              <div class="form-group mr-3">
                <button type="submit" class="btn btn-sm btn-primary rounded-0">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 col-sm-1">
              <div class="form-group">
                <button type="button" id="chart-image-download" class="btn btn-sm btn-primary rounded-0">
                  <i class="fas fa-file-download"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      <?= $this->Form->end(); ?>
    </div>
    <div class="card-body p-0">
      <div class="row">
        <div class="col-md-6">
          <div class="sticky">
            <canvas id="chart"></canvas>
          </div>
        </div>
        <div class="col-md-6">
          <div id="handsontable" class="mt-4" style="height: auto; overflow: auto;"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->Html->scriptBlock(json_encode($display_data['table_data']), ['block' => 'script', 'id' => 'table-data', 'type' => 'application/json']) ?>
<?= $this->Html->scriptBlock(json_encode($display_data['chart_data']), ['block' => 'script', 'id' => 'chart-data', 'type' => 'application/json']) ?>
<?= $this->Html->script('admin/display_index', ['block' => true, 'charset' => 'UTF-8']) ?>
