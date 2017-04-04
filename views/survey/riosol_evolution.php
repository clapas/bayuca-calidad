<?php

use app\assets\ChartjsAsset;
use yii\helpers\Json;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

ChartjsAsset::register($this);

$this->title = Yii::t('app', 'Riosol evolution');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riosol-evolution">
  <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
  <div class="well well-sm">
    <?= Html::beginForm('', 'get', ['class' => 'form form-inline']) ?>
      <?php $presetRanges = [
          Yii::t('app', 'Current quarter') => ["moment().startOf('Q')", "moment().endOf('Q')"],
          Yii::t('app', 'Previous quarter') => ["moment().subtract(1, 'Q').startOf('Q')", "moment().subtract(1, 'Q').endOf('Q')"],
          Yii::t('app', 'Same quarter previous year') => ["moment().startOf('Q').subtract(1, 'year')", "moment().endOf('Q').subtract(1, 'year')"],
          Yii::t('app', 'Current year') => ["moment().startOf('year')", "moment().endOf('year')"],
          Yii::t('app', 'Previous year') => ["moment().subtract(1, 'year').startOf('year')", "moment().subtract(1, 'year').endOf('year')"],
          Yii::t('app', 'Trailing twelve months') => ["moment().subtract(1, 'year').add(1, 'month')", "moment()"],
      ] ?>
      <?= DateRangePicker::widget([
          'name' => 'daterange1',
          'hideInput' => true,
          'convertFormat' => true,
          'startAttribute' => 'from',
          'callback' => 'function(startDate, endDate, label) {
              $(\'#w0-container\').find(\'.range-value\').html(label);
              $.ajax({
                  data: {
                      from: startDate.format(\'YYYY-MM-DD\'),
                      to: endDate.format(\'YYYY-MM-DD\'),
                      label: label,
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
$improvesLbl = Yii::t('app', 'Has improved');
$maintainsLbl = Yii::t('app', 'Maintains its level');
$worsensLbl = Yii::t('app', 'Has worsened');
$months_json = Json::encode($months);
$script = <<<JS
  $('#w0-container').find('.range-value').html('{$label}');
  var months = $months_json;
  var month_labels = months.map(function(v, k) {
      return moment(v.month).format('MMM \'YY');
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
              if (v.count != 0 && v.sum != null) return (v.sum / v.count).toFixed(2);
              else return null;
          })
      }]
  };
  function updateChart(data) {
      chart.config.data.labels = data.months.map(function(v, k) { return moment(v.month).format('MMM \'YY') });
      chart.config.data.datasets[0].label = data.label;
      chart.config.data.datasets[0].data = data.months.map(function(v, k) {
          if (v.count != 0 && v.sum != null) return (v.sum / v.count).toFixed(2);
          else return null;
      });
      chart.update();
  }
  var chart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: {
          responsive: false,
          scales: {
              xAxes: [{
                  type: 'category',
                  position: 'bottom',
              }],
              yAxes: [{
                  ticks: {
                      min: 0,
                      max: 2,
                      maxTicksLimit: 3,
                      callback: function (value, index, values) {
                          switch (index) {
                              case 0: return '$improvesLbl';
                              case 1: return '$maintainsLbl';
                              case 2: return '$worsensLbl';
                          }
                      }
                  },
              }]
          }
      }
  });
JS;
$this->registerJs($script);

