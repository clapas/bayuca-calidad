<?php

use app\assets\ChartjsAsset;
use yii\helpers\Json;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

ChartjsAsset::register($this);

$this->title = Yii::t('app', 'Groups comparison');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-comparison">
  <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
  <div class="well well-sm">
    <?= Html::beginForm('', 'get', ['class' => 'form form-inline']) ?>
      <?= Html::hiddenInput('label1', $periods[0]['label']) ?>
      <?= Html::hiddenInput('label2', $periods[1]['label']) ?>
      <label><?= Yii::t('app', 'Period') ?> 1</label>
      <?php $presetRanges = [
          Yii::t('app', 'Current month') => ["moment().startOf('month')", "moment().endOf('month')"],
          Yii::t('app', 'Previous month') => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
          Yii::t('app', 'Same month previous year') => ["moment().startOf('month').subtract(1, 'year')", "moment().endOf('month').subtract(1, 'year')"],
          Yii::t('app', 'Current year') => ["moment().startOf('year')", "moment().endOf('year')"],
          Yii::t('app', 'Previous year') => ["moment().subtract(1, 'year').startOf('year')", "moment().subtract(1, 'year').endOf('year')"],
          Yii::t('app', 'Trailing twelve months') => ["moment().subtract(1, 'year')", "moment()"],
      ] ?>
      <?= DateRangePicker::widget([
          'name' => 'daterange1',
          'hideInput' => true,
          'convertFormat' => true,
          'startAttribute' => 'from1',
          'callback' => 'function(startDate, endDate, label) {
              var picker2 = $(\'[name="daterange2"]\')
                  .closest(\'.drp-container\')
                  .data(\'daterangepicker\');
              var label2 = $(\'[name="label2"]\').val();
              $(\'#w0-container\').find(\'.range-value\').html(label);
              $.ajax({
                  data: {
                      from1: startDate.format(\'YYYY-MM-DD\'),
                      to1: endDate.format(\'YYYY-MM-DD\'),
                      label1: label,
                      from2: picker2.startDate.format(\'YYYY-MM-DD\'),
                      to2: picker2.endDate.format(\'YYYY-MM-DD\'),
                      label2: label2
                  }, success: function(data) {
                      updateChart(data);
                  }
              });
          }',
          'endAttribute' => 'to1',
          'startInputOptions' => ['value' => $periods[0]['from']],
          'endInputOptions' => ['value' => $periods[0]['to']],
          'pluginOptions' => [
              'locale' => ['format' => 'Y-m-d', 'separator' => ' → '],
              'autoApply' => true,
              'ranges' => $presetRanges
          ]
      ]) ?>
      <label><?= Yii::t('app', 'Period') ?> 2</label>
      <?= DateRangePicker::widget([
          'name' => 'daterange2',
          'hideInput' => true,
          'convertFormat' => true,
          'startAttribute' => 'from2',
          'callback' => 'function(startDate, endDate, label) {
              var picker1 = $(\'[name="daterange1"]\')
                  .closest(\'.drp-container\')
                  .data(\'daterangepicker\');
              var label1 = $(\'[name="label1"]\').val();
              $(\'#w1-container\').find(\'.range-value\').html(label);
              $.ajax({
                  data: {
                      from1: picker1.startDate.format(\'YYYY-MM-DD\'),
                      to1: picker1.endDate.format(\'YYYY-MM-DD\'),
                      label1: label1,
                      from2: startDate.format(\'YYYY-MM-DD\'),
                      to2: endDate.format(\'YYYY-MM-DD\'),
                      label2: label
                  }, success: function(data) {
                      updateChart(data);
                  }
              });
          }',
          'endAttribute' => 'to2',
          'startInputOptions' => ['value' => $periods[1]['from']],
          'endInputOptions' => ['value' => $periods[1]['to']],
          'pluginOptions' => [
              'locale' => ['format' => 'Y-m-d', 'separator' => ' → '],
              'autoApply' => true,
              'ranges' => $presetRanges
          ]
      ]) ?>
    <?= Html::endForm() ?>
  </div>
  <canvas style="width: 100%"></canvas>
</div>
<?php
$goalLbl = Yii::t('app', 'Goal') . ": $goal";
$groups_json = Json::encode($groups);
$script = <<<JS
  $('#w0-container').find('.range-value').html('{$periods[0]['label']}');
  $('#w1-container').find('.range-value').html('{$periods[1]['label']}');
  var horizonalLinePlugin = {
      beforeDraw: function(chartInstance) {
          var yValue;
          var yScale = chartInstance.scales["y-axis-0"];
          var canvas = chartInstance.chart;
          var ctx = canvas.ctx;
          var index;
          var line;
          var style;
          if (chartInstance.options.horizontalLine) {
              for (index = 0; index < chartInstance.options.horizontalLine.length; index++) {
                  line = chartInstance.options.horizontalLine[index];
                  if (!line.style) {
                      style = 'rgba(169,169,169, .6)';
                  } else {
                      style = line.style;
                  }
                  if (line.y) {
                      yValue = yScale.getPixelForValue(line.y);
                  } else {
                      yValue = 0;
                  }
                  ctx.lineWidth = 2;
                  if (yValue) {
                      ctx.beginPath();
                      //ctx.moveTo(0, yValue);
                      ctx.moveTo(chartInstance.chartArea.left, yValue);
                      //ctx.lineTo(canvas.width, yValue);
                      ctx.lineTo(chartInstance.chartArea.right, yValue);
                      ctx.strokeStyle = style;
                      ctx.stroke();
                  }
                  if (line.text) {
                      ctx.fillStyle = style;
                      ctx.fillText(line.text, 0, yValue - 16);
                  }
              }
          }
      }
  };
  Chart.pluginService.register(horizonalLinePlugin);
  var groups = $groups_json;
  var group_labels = Object.keys(groups);
  var ctx = $('canvas');
  var data = {
      labels: group_labels,
      datasets: [{
          label: '{$periods[0]['label']}',
          fill: false,
          backgroundColor: 'rgba(255, 99, 132, 0.4)',
          borderColor: 'rgba(255,99,132,1)',
          borderWidth: 1,
          data: group_labels.map(function(key, index) {
              return (33.333 * groups[key].smly_sum1 / groups[key].smly_cnt1).toFixed(2);
          })
      }, {
          label: '{$periods[1]['label']}',
          fill: false,
          backgroundColor: 'rgba(54, 162, 235, 0.4)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          data: group_labels.map(function(key, index) {
              return (33.333 * groups[key].smly_sum2 / groups[key].smly_cnt2).toFixed(2);
          })
      }]
  };
  function updateChart(data) {
     chart.config.data.datasets[0].label = data.periods[0].label;
     chart.config.data.datasets[0].data = group_labels.map(function(key, index) {
         return (33.333 * data.groups[key].smly_sum1 / data.groups[key].smly_cnt1).toFixed(2);
     });
     chart.config.data.datasets[1].label = data.periods[1].label;
     chart.config.data.datasets[1].data = group_labels.map(function(key, index) {
         return (33.333 * data.groups[key].smly_sum2 / data.groups[key].smly_cnt2).toFixed(2);
     });
     chart.update();
  }
  var chart = new Chart(ctx, {
      type: 'bar',
      data: data,
      options: {
          responsive: false,
          horizontalLine: [{
              y: {$goal},
              style: '#888',
              text: '{$goalLbl}'
          }], scales: {
              xAxes: [{
                  type: 'category',
                  position: 'bottom',
              }],
              yAxes: [{
                  ticks: {
                      min: 0,
                      max: 100,
                  },
              }]
          }
      }
  });
JS;
$this->registerJs($script);
