<?php

use app\assets\ChartjsAsset;
use yii\helpers\Json;
use yii\helpers\Html;
use app\models\Group;
use kartik\daterange\DateRangePicker;

ChartjsAsset::register($this);

$this->title = Yii::t('app', 'Group evolution');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-evolution">
  <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
  <div class="well well-sm">
    <?= Html::beginForm('', 'get', ['class' => 'form form-inline']) ?>
      <div class="input-group">
        <span class="input-group-addon"><?= Yii::t('app', 'Group') ?></span>
        <?= Html::dropDownList('group', null, Group::listAll(Yii::$app->language), ['class' => 'form-control', 'prompt' => '-- ' . Yii::t('app', 'Select a group') . ' --']) ?>
      </div>
      <?php $presetRanges = [
          Yii::t('app', 'Current quarter') => ["moment().startOf('Q')", "moment().endOf('Q')"],
          Yii::t('app', 'Previous quarter') => ["moment().subtract(1, 'Q').startOf('Q')", "moment().subtract(1, 'Q').endOf('Q')"],
          Yii::t('app', 'Same quarter previous year') => ["moment().startOf('Q').subtract(1, 'year')", "moment().endOf('Q').subtract(1, 'year')"],
          Yii::t('app', 'Current year') => ["moment().startOf('year')", "moment().endOf('year')"],
          Yii::t('app', 'Previous year') => ["moment().subtract(1, 'year').startOf('year')", "moment().subtract(1, 'year').endOf('year')"],
          Yii::t('app', 'Trailing twelve months') => ["moment().subtract(1, 'year').add(1, 'day')", "moment()"],
      ] ?>
      <?= DateRangePicker::widget([
          'name' => 'daterange1',
          'hideInput' => true,
          'convertFormat' => true,
          'startAttribute' => 'from',
          'callback' => 'function(startDate, endDate, label) {
              var group = $(\'[name="group"]\').val();
              $(\'#w0-container\').find(\'.range-value\').html(label);
              $.ajax({
                  data: {
                      from: startDate.format(\'YYYY-MM-DD\'),
                      to: endDate.format(\'YYYY-MM-DD\'),
                      label: label,
                      group: group
                  }, success: function(data) {
                      updateChart(data);
                  }
              });
          }',
          'endAttribute' => 'to1',
          'startInputOptions' => ['value' => $from],
          'endInputOptions' => ['value' => $to],
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
$months_json = Json::encode($months);
$script = <<<JS
  $('#w0-container').find('.range-value').html('{$label}');
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
  var months = $months_json;
  var month_labels = months.map(function(v, k) {
      return v.month;
  });
  var ctx = $('canvas');
  var data = {
      labels: month_labels,
      datasets: [{
          label: '{$label}',
          borderWidth: 1,
          pointStyle: 'rect',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          data: months.map(function(v, k) {
              if (v.count > 0) return (33.333 * v.sum / v.count).toFixed(2);
              else return 0;
          })
      }]
  };
  $('select[name="group"]').on('change', function() {
      var group = $('[name="group"]').val();
      var picker = $('#w0-container').data('daterangepicker');
      $.ajax({
          data: {
              from: picker.startDate.format('YYYY-MM-DD'),
              to: picker.endDate.format('YYYY-MM-DD'),
              group: group
          }, success: function(data) {
              updateChart(data);
          }
      });
  });
  function updateChart(data) {
     chart.config.data.labels = data.months.map(function(v, k) { return v.month });
     chart.config.data.datasets[0].label = data.label;
     chart.config.data.datasets[0].data = data.months.map(function(v, k) {
         if (v.count != 0) return (33.333 * v.sum / v.count).toFixed(2);
         else return 0;
     });
     chart.update();
  }
  var chart = new Chart(ctx, {
      type: 'line',
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
