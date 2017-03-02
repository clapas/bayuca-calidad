<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Summary');
$this->params['breadcrumbs'][] = $this->title;
?>
<svg style="display: none">
  <defs>
    <path transform="scale(0.6)" id="emoticon-happy" fill="#ffffff" d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z"></path>
  </defs>
</svg>
<div class="survey-index">
  <h1 class="page-header"><?= Html::encode($this->title) ?> <small><?= Yii::t('app', 'Goal') . ' ' . date('Y') . ': ' . Yii::$app->formatter->asDecimal($goal, 2) ?></small></h1>
  <p><a class="btn btn-link" data-toggle="collapse" data-target=".well">+ <?= Yii::t('app', 'Change periods') ?></a></p>
  <div class="well well-sm collapse">
    <?= Html::beginForm('', 'get', ['class' => 'form form-inline']) ?>
      <label><?= Yii::t('app', 'Period') ?> 1</label>
      <?= DateRangePicker::widget([
          'name' => 'daterange1',
          'hideInput' => true,
          'convertFormat' => true,
          'startAttribute' => 'from1',
          'endAttribute' => 'to1',
          'startInputOptions' => ['value' => $periods[0]['from']],
          'endInputOptions' => ['value' => $periods[0]['to']],
          'pluginOptions' => [
              'locale' => ['format' => 'Y-m-d', 'separator' => ' .. '],
              'autoApply' => true
          ], 'pluginEvents' => [
              'apply.daterangepicker' => 'function(e, picker1) {
                  var picker2 = $(\'[name="daterange2"]\')
                      .closest(\'.drp-container\')
                      .data(\'daterangepicker\');
                  $.pjax({container: \'#p0\', data: {
                      from1: picker1.startDate.format(\'YYYY-MM-DD\'),
                      to1: picker1.endDate.format(\'YYYY-MM-DD\'),
                      from2: picker2.startDate.format(\'YYYY-MM-DD\'),
                      to2: picker2.endDate.format(\'YYYY-MM-DD\')
                  }, scrollTo: false });
              }'
          ]
      ]) ?>
      <label><?= Yii::t('app', 'Period') ?> 2</label>
      <?= DateRangePicker::widget([
          'name' => 'daterange2',
          'hideInput' => true,
          'convertFormat' => true,
          'startAttribute' => 'from2',
          'endAttribute' => 'to2',
          'startInputOptions' => ['value' => $periods[1]['from']],
          'endInputOptions' => ['value' => $periods[1]['to']],
          'pluginOptions' => [
              'locale' => ['format' => 'Y-m-d', 'separator' => ' .. '],
              'autoApply' => true
          ], 'pluginEvents' => [
              'apply.daterangepicker' => 'function(e, picker2) {
                  var picker1 = $(\'[name="daterange1"]\')
                      .closest(\'.drp-container\')
                      .data(\'daterangepicker\');
                  $.pjax({container: \'#p0\', data: {
                      from1: picker1.startDate.format(\'YYYY-MM-DD\'),
                      to1: picker1.endDate.format(\'YYYY-MM-DD\'),
                      from2: picker2.startDate.format(\'YYYY-MM-DD\'),
                      to2: picker2.endDate.format(\'YYYY-MM-DD\'),
                  }, scrollTo: false });
              }'
          ],
      ]) ?>
      <button type="button" class="btn btn-default"><?= Yii::t('app', 'Reset') ?></button>
    <?= Html::endForm() ?>
  </div>
  <style>
    svg.emoticon {
        width: 14px;
        height: 14px;
        position: relative;
        top: 2px;
    }
  </style>
  <?php Pjax::begin(); ?>
  <div class="panel panel-default">
    <table class="table lead">
      <tbody>
        <tr>
          <td><?= $periods[0]['label'] ?></td>
          <td><span class="label label-primary"><svg class="emoticon"><use xlink:href="#emoticon-happy"></use></svg> <?= $totals['smly_cnt1'] != 0 ? Yii::$app->formatter->asDecimal(33.33 * $totals['smly_sum1']/$totals['smly_cnt1'], 2) : ' --' ?></span>
          <span class="label label-default">x<?= $totals['smly_cnt1'] ?></span></td>
          <td><span class="label label-danger"><span class="glyphicon glyphicon-certificate"></span> <?= $totals['global1']['count'] != 0 ? Yii::$app->formatter->asDecimal($totals['global1']['sum']/$totals['global1']['count'], 2) : ' --' ?></span> <span class="label label-default">x<?= $totals['global1']['count'] ?></span></td>
        </tr>
        <tr>
          <td><?= $periods[1]['label'] ?></td>
          <td><span class="label label-primary"><svg class="emoticon"><use xlink:href="#emoticon-happy"></use></svg> <?= $totals['smly_cnt2'] != 0 ? Yii::$app->formatter->asDecimal(33.33 * $totals['smly_sum2']/$totals['smly_cnt2'], 2) : ' --' ?></span>
          <span class="label label-default">x<?= $totals['smly_cnt2'] ?></span></td>
          <td><span class="label label-danger"><span class="glyphicon glyphicon-certificate"></span> <?= $totals['global2']['count'] != 0 ? Yii::$app->formatter->asDecimal($totals['global2']['sum']/$totals['global2']['count'], 2) : ' --' ?></span> <span class="label label-default">x<?= $totals['global2']['count'] ?></span></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="row">
    <div class="col-md-7">
      <h2><?= Yii::t('app', 'Smiley questions') ?></h2>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th rowspan="2" class="text-center"><?= Yii::t('app', 'Department') ?></th>
            <th rowspan="2" class="text-center"><?= Yii::t('app', 'Question') ?></th>
            <th colspan="2" class="text-center"><?= $periods[0]['label'] ?></th>
            <th colspan="2" class="text-center"><?= $periods[1]['label'] ?></th>
          </tr>
          <tr>
            <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
            <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
            <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
            <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $department => $question_grp): ?>
              <?php $first = true ?>
              <?php foreach ($question_grp as $question => $stats): ?>
                <tr>
                  <?php if ($first): ?>
                    <td rowspan="<?= count($question_grp) ?>"><?= $department ?></td>
                    <?php $first = false ?>
                  <?php endif; ?>
                  <td><?= $question ?></td>
                  <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                      33.33 * ArrayHelper::getValue($stats, 'smly_sum1', 0)/ArrayHelper::getValue($stats, 'smly_cnt1', 1), 2) ?></td>
                  <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt1', 0) ?></td>
                  <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                      33.33 * ArrayHelper::getValue($stats, 'smly_sum2', 0)/ArrayHelper::getValue($stats, 'smly_cnt2', 1), 2) ?></td>
                  <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt2', 0) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="col-md-5">
  <h2><?= Yii::t('app', 'Smiley groups') ?></h2>
  <table class="table table-condensed table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2" class="text-center"><?= Yii::t('app', 'Group') ?></th>
        <th colspan="2" class="text-center"><?= $periods[0]['label'] ?></th>
        <th colspan="3" class="text-center"><?= $periods[1]['label'] ?></th>
      </tr>
      <tr>
        <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
        <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
        <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
        <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
        <th class="text-center"><?= Yii::t('app', 'Goal') ?></th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($groups as $group=> $stats): ?>
          <tr>
            <td><?= $group ?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                33.33 * ArrayHelper::getValue($stats, 'smly_sum1', 0)/ArrayHelper::getValue($stats, 'smly_cnt1', 1), 2) ?></td>
            <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt1', 0) ?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                33.33 * ArrayHelper::getValue($stats, 'smly_sum2', 0)/ArrayHelper::getValue($stats, 'smly_cnt2', 1), 2) ?></td>
            <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt2', 0) ?></td>
            <?php $spread = 33.33 * ArrayHelper::getValue($stats, 'smly_sum2', 0)/ArrayHelper::getValue($stats, 'smly_cnt2', 1) - $goal ?>
            <td class="text-right <?= $spread < 0 ? 'text-danger' : 'text-success'?>"><?= Yii::$app->formatter->asDecimal($spread, 2) ?></td>
          </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
    </div>
  </div>
  <?php Pjax::end(); ?>
</div>
<?php
$df1 = $periods[0]['default_from'];
$dt1 = $periods[0]['default_to'];
$df2 = $periods[1]['default_from'];
$dt2 = $periods[1]['default_to'];
$script = <<< JS
  $('form .btn').on('click', function() {
      var drp1 = $('[name="daterange1"]').closest('.drp-container').data('daterangepicker');
      drp1.setStartDate('$df1');
      drp1.setEndDate('$dt1');
      drp1.callback(drp1.startDate, drp1.endDate, null);
      var drp2 = $('[name="daterange2"]').closest('.drp-container').data('daterangepicker');
      drp2.setStartDate('$df2');
      drp2.setEndDate('$dt2');
      drp2.callback(drp2.startDate, drp2.endDate, null);
      $.pjax({url: '?', container: '#p0', scrollTo: false});
  });
JS;
$this->registerJs($script);
?>
