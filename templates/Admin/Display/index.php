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
    <div class="card-header bg-body">
      <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'daily_records-search-form']) ?>
        <div class="row row-cols-sm-auto align-items-center">
          <?php if ($display_data['display_only_month'] === true) { ?>
            <label class="col-form-label col-12 pe-0 me-1" for="month-datepicker">期間</label>
            <div class="col-12 px-0">
              <div class="form-group">
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
          <?php } else { ?>
            <label class="col-form-label col-12 pe-0 me-1" for="day-from-datepicker">期間</label>
            <div class="col-12 px-0">
              <div class="form-group">
                <?= $this->Form->control('day_from', [
                  'id' => 'day-from-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0 w-110',
                  'label' => false,
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#day-from-datepicker',
                ]); ?>
              </div>
            </div>
            &nbsp;～&nbsp;
            <div class="col-12 px-0">
              <div class="form-group me-3">
                <?= $this->Form->control('day_to', [
                  'id' => 'day-to-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0 w-110',
                  'label' => false,
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#day-to-datepicker',
                ]); ?>
              </div>
            </div>
          <?php } ?>
          <label class="col-form-label col-12 px-0 me-1" for="display-unit">単位表示</label>
          <div class="col-12 px-0">
            <div class="form-group me-3">
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
          <label class="col-form-label col-12 px-0 me-1" for="include-deposit">入出金を含める</label>
          <div class="col-12 px-0">
            <div class="form-group me-3">
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
          <div class="col-12 px-0">
            <div class="form-group me-3">
              <button type="submit" class="btn btn-sm btn-primary rounded-0">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
          <div class="col-12 px-0">
            <div class="form-group">
              <button type="button" id="chart-image-download" class="btn btn-sm btn-primary rounded-0">
                <i class="fas fa-file-download"></i>
              </button>
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
