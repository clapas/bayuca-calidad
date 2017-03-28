<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Survey */

$this->title = Yii::t('app', 'Survey') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-view">

  <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
      <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
              'method' => 'post',
          ],
      ]) ?>
  </p>

  <div class="row">
    <div class="col-md-6">
      <h2><?= Yii::t('app', 'General questions') ?></h2>
      <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
              'id',
              'checkout_date',
              'apartment',
              'global_score',
              'source_title',
              'guest_name', [
                  'attribute' => 'guest_country_id',
                  'value' => function($model) {
                      if ($model->guestCountry_name) return $model->guestCountry_name; 
                  }
              ], 'guest_email:email',
              'guest_address',
              'touroperator_name',
              'best_employee_name',
              'best_employee_group_name',
              'good_things:ntext',
              'bad_things:ntext',
              'met_expectation_title',
              'evolution_title',
              'suggestions:ntext',
          ],
      ]) ?>
    </div>
    <div class="col-md-6 emoticon">
      <h2><?= Yii::t('app', 'Smiley questions') ?> <small><?= Yii::t('app', 'Avg.') ?> <span id="emoticons-avg"></span></small></h2>
      <style>
        .emoticon svg {
            width: 33px;
            height: 33px;
            margin-left: 20px;
            top: 4px;
            display: inline-block;
        }
      </style>
      <?= $this->render('_emoticon_defs'); ?>
      
      <?php 
      $emoticons = [
          0 => '<svg><use xlink:href="#emoticon-sad"></use></svg>',
          1 => '<svg><use xlink:href="#emoticon-neutral"></use></svg>',
          2 => '<svg><use xlink:href="#emoticon-happy"></use></svg>',
          3 => '<svg><use xlink:href="#emoticon-excited"></use></svg>',
          null => '<svg><use xlink:href="#emoticon-unknown"></use></svg>',
      ];
      $sum = 0;
      $count = 0;
      foreach($answered_questions as $department => $answers) {
          echo Html::tag('h3', $department);
          foreach($answers as $a) {
              echo $emoticons[$a->score] . $a->question->title . '<br>';
              if ($a->score !== null) {
                  $sum += $a->score; 
                  $count++;
              }
          }
      } ?>
    </div>
  </div>
</div>
<?php
if ($count !== 0) $avg = Yii::$app->formatter->asDecimal(33.333 * $sum / $count, 2);
else $avg = 0;
$script = <<< JS
    $('#emoticons-avg').html('$avg');
JS;
$this->registerJs($script);
