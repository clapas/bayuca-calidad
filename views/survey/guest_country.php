<?php

use app\assets\ChartjsAsset;
use yii\helpers\Json;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

ChartjsAsset::register($this);

$this->title = Yii::t('app', 'Guest countries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="guest-countries">
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
  <canvas style="height: 100%"></canvas>
</div>
<?php
$countries_json = Json::encode($countries);
$script = <<<JS
  $('#w0-container').find('.range-value').html('{$label}');
  var countries = $countries_json;
  var countries_labels = countries.map(function(v, k) {
      return v.country_name;
  });
  backgroundColors = [];
  countries.forEach(function(e, i) {
      backgroundColors.push(rainbow(countries.length, i));
  });
  var ctx = $('canvas');
  var data = {
      labels: countries_labels,
      datasets: [{
          backgroundColor: backgroundColors,
          borderColor: '#ccc',
          borderWidth: 1,
          data: countries.map(function(v, k) {
              return v.count;
          })
      }]
  };
  function updateChart(data) {
      chart.config.data.labels = data.countries.map(function(v, k) { return v.country_name; });
      chart.config.data.datasets[0].data = data.countries.map(function(v, k) {
          return v.count;
      });
      chart.config.options.title.text = data.label;
      chart.update();
  }
  var chart = new Chart(ctx, {
      type: 'pie',
      data: data,
      options: {
          responsive: true,
          title: {
              display: true,
              text: $('.range-value').html()
          }
      }
  });
  function rainbow(numOfSteps, step) {
      // This function generates vibrant, "evenly spaced" colours (i.e. no clustering).
      // This is ideal for creating easily distinguishable vibrant markers in Google Maps and other apps.
      // Adam Cole, 2011-Sept-14
      // HSV to RBG adapted from: http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
      var r, g, b;
      var h = step / numOfSteps;
      var i = ~~(h * 6);
      var f = h * 6 - i;
      var q = 1 - f;
      switch(i % 6){
          case 0: r = 1; g = f; b = 0; break;
          case 1: r = q; g = 1; b = 0; break;
          case 2: r = 0; g = 1; b = f; break;
          case 3: r = 0; g = q; b = 1; break;
          case 4: r = f; g = 0; b = 1; break;
          case 5: r = 1; g = 0; b = q; break;
      }
      var c = "#" + ("00" + (~ ~(r * 255)).toString(16)).slice(-2) +
          ("00" + (~ ~(g * 255)).toString(16)).slice(-2) + 
          ("00" + (~ ~(b * 255)).toString(16)).slice(-2);
      return (c);
  }
JS;
$this->registerJs($script);

