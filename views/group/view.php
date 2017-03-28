<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\assets\MultiselectAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Group */

$this->title = Yii::t('app', 'Group') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

MultiselectAsset::register($this);
?>
<div class="group-view">

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

    <!--
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
    -->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

    <?php $form = ActiveForm::begin(); ?>
      <?= $form->field($model, 'questions')->listBox($selectable, ['multiple' => true])->label('<h2>' . $model->getAttributeLabel('questions'). '</h2>') ?>
      <div class="form-group">
          <?= Html::submitButton(Yii::t('app', 'Save'), ['class' =>'btn btn-success']) ?>
      </div>
  
    <?php ActiveForm::end(); ?>

<?php
$selectableLbl = Yii::t('app', 'Selectable questions');
$selectedLbl = Yii::t('app', 'Selected into this group');
$script = <<< JS
    $('#group-questions').multiSelect({
        selectableHeader: "<h3>$selectableLbl</h3>",
        selectionHeader: "<h3>$selectedLbl</h3>",
    });
JS;
$this->registerJs($script);
?>
