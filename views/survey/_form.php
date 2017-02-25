<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model->survey app\model->surveys\Survey */
/* @var $form yii\widgets\ActiveForm */

function mkFieldTemplate($icon) {
    return str_replace('{icon}', $icon, '
        {label}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-{icon}"></span>
          </span>
          {input}
        </div>
        {error}{hint}');
}
?>

<div class="survey-form">
  <div class="row">
    <div class="col-md-6">
  
      <?php $form = ActiveForm::begin(); ?>

      <?= $form->field($model->survey, 'checkout_date')->widget(DatePicker::classname(), [
          'options' => ['placeholder' => Yii::t('app', 'Enter checkout date')],
          'pluginOptions' => [
              'autoclose'=>true
          ]
      ]); ?>
  
      <?= $this->render('_radio_questions', ['model' => $model, 'form' => $form]) ?>
      <hr>
  
      <?= $form->field($model->survey, 'met_expectation_title', ['template' => mkFieldTemplate('apple')])->dropDownList($met_expectations, ['prompt' => null]) ?>
  
      <?= $form->field($model->survey, 'evolution_title', ['template' => mkFieldTemplate('signal')])->dropDownList($evolutions, ['prompt' => null]) ?>
  
      <?= $form->field($model->survey, 'global_score', ['template' => mkFieldTemplate('certificate')])->dropDownList(array_slice(range(0,10), 1, null, true), ['prompt' => null]) ?>
  
      <?= $form->field($model->survey, 'election_title', ['template' => mkFieldTemplate('magnet')])->dropDownList($elections, ['prompt' => null]) ?>
  
      <?= $form->field($model->survey, 'good_things', ['template' => mkFieldTemplate('thumbs-up')])->textArea(['maxlength' => true]) ?>
  
      <?= $form->field($model->survey, 'bad_things', ['template' => mkFieldTemplate('thumbs-down')])->textArea(['maxlength' => true]) ?>
  
      <?= $form->field($model->survey, 'apartment', ['template' => mkFieldTemplate('tag')])->textInput(['maxlength' => true]) ?>
  
      <?= $form->field($model->survey, 'guest_name', ['template' => mkFieldTemplate('user')])->textInput(['maxlength' => true])?>
  
      <?= $form->field($model->survey, 'guest_country_id')->widget(Select2::classname(), [
        'options' => [
            'prompt' => null,
        ],
        'addon' => [
            'prepend' => [
                'content' => '<span class="glyphicon glyphicon-globe"></span>'
            ]
        ],
        'data' => $countries
      ]) ?>
  
      <?= $form->field($model->survey, 'guest_email', ['template' => mkFieldTemplate('envelope')])->textInput(['maxlength' => true, 'type' => 'email']) ?>
  
      <?= $form->field($model->survey, 'guest_address', ['template' => mkFieldTemplate('home')])->textArea(['maxlength' => true]) ?>
  
      <?= $form->field($model->survey, 'touroperator_name', ['template' => mkFieldTemplate('briefcase')])->textInput(['maxlength' => true]) ?>
  
      <?= $form->field($model->survey, 'best_employee_name', ['template' => mkFieldTemplate('heart')])->textInput(['maxlength' => true]) ?>
  
      <?= $form->field($model->survey, 'best_employee_department_name', ['template' => mkFieldTemplate('heart-empty')])->dropDownList($departments, ['prompt' => null]) ?>

      <?= $form->field($model->survey, 'suggestions')->textarea(['rows' => 6]) ?>
  
      <div class="form-group">
          <?= Html::submitButton($model->survey->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->survey->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
  
      <?php ActiveForm::end(); ?>

    </div>
  </div>
</div>
