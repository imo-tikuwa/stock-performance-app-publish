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
          <div class="table-responsive">
            <table class="table table-sm table-hover text-sm text-nowrap">
              <thead>
                <tr>
                  <?php if (in_array('date', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">日付</th>
                  <?php } ?>
                  <?php if (in_array('record_total_real', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">実質資産</th>
                  <?php } ?>
                  <?php if (in_array('prev_day_diff_value', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">前営業日比</th>
                  <?php } ?>
                  <?php if (in_array('prev_day_diff_rate', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">前営業日比(%)</th>
                  <?php } ?>
                  <?php if (in_array('prev_month_diff_value', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">単月成績</th>
                  <?php } ?>
                  <?php if (in_array('prev_month_diff_rate', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">単月成績(%)</th>
                  <?php } ?>
                  <?php if (in_array('beginning_year_diff_value', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">年初来成績</th>
                  <?php } ?>
                  <?php if (in_array('beginning_year_diff_rate', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">年初来成績(%)</th>
                  <?php } ?>
                  <?php if (in_array('deposit_day_ammount', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">入出金</th>
                  <?php } ?>
                  <?php if (in_array('record_total', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right text-center">証券口座合計</th>
                  <?php } ?>
                  <?php if (in_array('account_records', $config->display_setting, true)) { ?>
                    <?php foreach ($display_data['accounts'] as $account_id => $account_name) { ?>
                      <th scope="col" class="border-left border-right"><?= h($account_name) ?></th>
                    <?php } ?>
                  <?php } ?>
                  <?php if (in_array('account_new_link', $config->display_setting, true)) { ?>
                    <th scope="col" class="border-left border-right">
                      <small>
                        <?= $this->Html->link(
                          "<i class='fas fa-edit fa-fw ml-2'></i>",
                          ['controller' => 'Accounts', 'action' => ACTION_ADD],
                          ['escapeTitle' => false]
                        ) ?>
                      </small>
                    </th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($display_data['records'] as $date => $record) { ?>
                  <tr>
                    <?php if (in_array('date', $config->display_setting, true)) { ?>
                      <td class="border-left border-right"><?= h($date) ?></td>
                    <?php } ?>
                    <?php if (in_array('record_total_real', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['record_total_real'])) { ?>
                          <?= $this->Number->format($record['record_total_real']) ?>円
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('prev_day_diff_value', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['prev_day_diff_value'])) { ?>
                          <?= $this->Display->color($this->Number->format($record['prev_day_diff_value']) . '円') ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('prev_day_diff_rate', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['prev_day_diff_rate'])) { ?>
                          <?= $this->Display->color($this->Number->format($record['prev_day_diff_rate']) . '%') ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('prev_month_diff_value', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['prev_month_diff_value'])) { ?>
                          <?= $this->Display->color($this->Number->format($record['prev_month_diff_value']) . '円') ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('prev_month_diff_rate', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['prev_month_diff_rate'])) { ?>
                          <?= $this->Display->color($this->Number->format($record['prev_month_diff_rate']) . '%') ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('beginning_year_diff_value', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['beginning_year_diff_value'])) { ?>
                          <?= $this->Display->color($this->Number->format($record['beginning_year_diff_value']) . '円') ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('beginning_year_diff_rate', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['beginning_year_diff_rate'])) { ?>
                          <?= $this->Display->color($this->Number->format($record['beginning_year_diff_rate']) . '%') ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('deposit_day_ammount', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['deposit_day_ammount'])) { ?>
                          <?= $this->Number->format($record['deposit_day_ammount']) ?>円
                        <?php } ?>
                        <small>
                          <?php if (is_null($record["deposit_id"])) { ?>
                            <?= $this->Html->link(
                              "<i class='fas fa-edit fa-fw'></i>",
                              ['controller' => 'Deposits', 'action' => ACTION_ADD, '?' => ['deposit_date' => $date]],
                              ['escapeTitle' => false]
                            ) ?>
                          <?php } else { ?>
                            <?= $this->Html->link(
                              "<i class='fas fa-edit fa-fw'></i>",
                              ['controller' => 'Deposits', 'action' => ACTION_EDIT, $record["deposit_id"]],
                              ['escapeTitle' => false]
                            ) ?>
                          <?php } ?>
                        </small>
                      </td>
                    <?php } ?>
                    <?php if (in_array('record_total', $config->display_setting, true)) { ?>
                      <td class="border-left border-right text-right">
                        <?php if (isset($record['record_total'])) { ?>
                          <?= $this->Number->format($record['record_total']) ?>円
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (in_array('account_records', $config->display_setting, true)) { ?>
                      <?php foreach ($display_data['accounts'] as $account_id => $account_name) { ?>
                        <td class="border-left border-right text-right">
                          <?php if (isset($record["account{$account_id}_daily_record"])) { ?>
                            <?= $this->Number->format($record["account{$account_id}_daily_record"]) ?>円
                          <?php } ?>
                          <small>
                            <?php if (is_null($record["account{$account_id}_daily_record_id"])) { ?>
                              <?= $this->Html->link(
                                "<i class='fas fa-edit fa-fw'></i>",
                                ['controller' => 'DailyRecords', 'action' => ACTION_ADD, '?' => ['account_id' => $account_id, 'day' => $date]],
                                ['escapeTitle' => false]
                              ) ?>
                            <?php } else { ?>
                              <?= $this->Html->link(
                                "<i class='fas fa-edit fa-fw'></i>",
                                ['controller' => 'DailyRecords', 'action' => ACTION_EDIT, $record["account{$account_id}_daily_record_id"], '?' => ['account_id' => $account_id, 'day' => $date]],
                                ['escapeTitle' => false]
                              ) ?>
                            <?php } ?>
                          </small>
                        </td>
                      <?php } ?>
                    <?php } ?>
                    <?php if (in_array('account_new_link', $config->display_setting, true)) { ?>
                      <td class="border-left border-right"></td>
                    <?php } ?>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->Html->scriptBlock(json_encode($display_data['chartData']), ['block' => 'script', 'id' => 'chart-data', 'type' => 'application/json']) ?>
<?= $this->Html->script('admin/display_index', ['block' => true, 'charset' => 'UTF-8']) ?>
