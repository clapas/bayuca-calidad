<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SurveySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="survey-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'checkout_date') ?>

    <?= $form->field($model, 'apartment') ?>

    <?= $form->field($model, 'global_score') ?>

    <?= $form->field($model, 'source_title') ?>

    <?php // echo $form->field($model, 'client_name') ?>

    <?php // echo $form->field($model, 'client_country_id') ?>

    <?php // echo $form->field($model, 'client_email') ?>

    <?php // echo $form->field($model, 'client_address') ?>

    <?php // echo $form->field($model, 'touroperator_name') ?>

    <?php // echo $form->field($model, 'best_employee_name') ?>

    <?php // echo $form->field($model, 'best_employee_group_name') ?>

    <?php // echo $form->field($model, 'friend_email') ?>

    <?php // echo $form->field($model, 'friend_name') ?>

    <?php // echo $form->field($model, 'friend_address') ?>

    <?php // echo $form->field($model, 'met_expectation_title') ?>

    <?php // echo $form->field($model, 'evolution_title') ?>

    <?php // echo $form->field($model, 'suggestions') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
