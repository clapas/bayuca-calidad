<?php
use app\assets\FullpageAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

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
FullpageAsset::register($this);
?>
<svg style="display: none">
  <defs>
    <path id="emoticon-excited" transform="scale(1.8)" fill="#dddddd" d="M12,17.5C14.33,17.5 16.3,16.04 17.11,14H6.89C7.69,16.04 9.67,17.5 12,17.5M8.5,11A1.5,1.5 0 0,0 10,9.5A1.5,1.5 0 0,0 8.5,8A1.5,1.5 0 0,0 7,9.5A1.5,1.5 0 0,0 8.5,11M15.5,11A1.5,1.5 0 0,0 17,9.5A1.5,1.5 0 0,0 15.5,8A1.5,1.5 0 0,0 14,9.5A1.5,1.5 0 0,0 15.5,11M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
    <path id="emoticon-happy" transform="scale(1.8)" fill="#dddddd" d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z" />
    <path id="emoticon-neutral" transform="scale(1.8)" fill="#dddddd" d="M8.5,11A1.5,1.5 0 0,1 7,9.5A1.5,1.5 0 0,1 8.5,8A1.5,1.5 0 0,1 10,9.5A1.5,1.5 0 0,1 8.5,11M15.5,11A1.5,1.5 0 0,1 14,9.5A1.5,1.5 0 0,1 15.5,8A1.5,1.5 0 0,1 17,9.5A1.5,1.5 0 0,1 15.5,11M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M9,14H15A1,1 0 0,1 16,15A1,1 0 0,1 15,16H9A1,1 0 0,1 8,15A1,1 0 0,1 9,14Z" />
    <path id="emoticon-sad" transform="scale(1.8)" fill="#dddddd" d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M15.5,8C16.3,8 17,8.7 17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M12,14C13.75,14 15.29,14.72 16.19,15.81L14.77,17.23C14.32,16.5 13.25,16 12,16C10.75,16 9.68,16.5 9.23,17.23L7.81,15.81C8.71,14.72 10.25,14 12,14Z" />
  </defs>
</svg>
<div class="fullpage-menu">
  <?= Html::a(Yii::t('app', 'Home'), ['site/index'], ['class' => 'btn-link']) ?>
</div>
<div class="fullpage">
 <?php $form = ActiveForm::begin(); ?>
  <div class="section" data-anchor="step-0">
    <div class="container">
      <h1 class="page-header">Idioma <small>Language / Sprache / Langage / Lingua / Язык / Språk</small></h1>
      <div class="languages text-center" >
        <img src="<?= Url::to('@web/img/front.jpg')?>" height="240px">
      </div>
      <p class="lead">Estimado cliente,</p>
      <p class="lead">sus opiniones y sugerencias, incluso sus quejas, son la mejor herramienta para mejorar nuestras instalaciones y servicios, por ello le agradecemos sinceramente que dedique unos minutos de sus vacaciones a rellenar este cuestionario antes de abandonar el hotel.</p>
      <p class="lead">Le aseguramos que todas sus aportaciones serán tenidas muy en cuenta.</p>
      <p class="lead"><strong>La dirección</strong></p>
      <hr>
      <div class="row">
        <div class="col-md-3 col-md-offset-9 col-xs-6 col-xs-offset-6">
          <a class="btn btn-xl btn-block btn-primary" href="#step-1">Siguiente</a>
        </div>
      </div>
    </div>
  </div>
  <?php
    $previous = $next = null;
    $current = 1;
    foreach ($model->answers as $department => $answers) {
      echo Html::beginTag('div', ['class' => 'section', 'data-anchor' => "step-{$current}"]);
        echo Html::beginTag('div', ['class' => 'container']);
          echo Html::tag('h1', $department, ['class' => 'page-header']);
          echo Html::beginTag('div', ['class' => 'row']);
            echo $this->render('_emoticon_cols');
            foreach ($answers as $answer) {
              echo $form->field($answer, "[{$answer->question_id}]score", [
                'template' => "<div class=\"col-xs-8\">{$answer->question->title}</div>\n{input}\n{hint}\n{error}",
              ])->radioList([
                  3 => null,
                  2 => null,
                  1 => null,
                  0 => null
              ], ['itemOptions' => ['labelOptions' => ['class' => 'col-xs-1']]]);
            }
          echo Html::endTag('div');
          echo Html::tag('hr');
          echo Html::beginTag('div', ['class' => 'row']);
            $previous = $current - 1;
            echo Html::tag('div', Html::tag('a', Yii::t('app', 'Previous'), [
                'href' => "#step-{$previous}",
                'class' => 'btn btn-xl btn-block btn-primary'
            ]), [
                'class' => 'col-md-3 col-xs-6'
            ]);
            $next = $current + 1;
            echo Html::tag('div', Html::tag('a', Yii::t('app', 'Next'), [
                'href' => "#step-{$next}",
                'class' => 'btn btn-xl btn-block btn-primary'
            ]), [
                'class' => 'col-md-3 col-md-offset-6 col-xs-6'
            ]);
          echo Html::endTag('div');
        echo Html::endTag('div');
      echo Html::endTag('div');
      $current++;
    }
  ?>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container">
      <div class="vpad">
        <?= $form->field($model->survey, 'source_title', ['template' => mkFieldTemplate('magnet')])->dropDownList($sources, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'met_expectation_title', ['template' => mkFieldTemplate('apple')])->dropDownList($met_expectations, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'evolution_title', ['template' => mkFieldTemplate('signal')])->dropDownList($evolutions, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'global_score', ['template' => mkFieldTemplate('certificate')])->dropDownList(array_slice(range(0,10), 1, null, true), ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      </div>
      <hr>
      <?php
        $previous = $current - 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Previous'), [
            'href' => "#step-{$previous}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-xs-6'
        ]);
        $next = $current + 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Next'), [
            'href' => "#step-{$next}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-md-offset-6 col-xs-6'
        ]);
        $current++;
      ?>
    </div>
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container">
      <div class="vpad">
        <?= $form->field($model->survey, 'good_things', ['template' => mkFieldTemplate('thumbs-up')])->textArea(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'bad_things', ['template' => mkFieldTemplate('thumbs-down')])->textArea(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'suggestions', ['template' => mkFieldTemplate('bullhorn')])->textarea(['class' => 'input-lg form-control', 'rows' => 6]) ?>
      </div>
      <hr>
      <?php
        $previous = $current - 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Previous'), [
            'href' => "#step-{$previous}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-xs-6'
        ]);
        $next = $current + 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Next'), [
            'href' => "#step-{$next}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-md-offset-6 col-xs-6'
        ]);
        $current++;
      ?>
    </div>
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container">
      <div class="vpad">
        <?= $form->field($model->survey, 'guest_name', ['template' => mkFieldTemplate('user')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control'])?>
        <?= $form->field($model->survey, 'guest_country_id')->widget(Select2::classname(), [
            'size' => 'lg',
            'options' => [
                'prompt' => '',
            ], 'addon' => [
                'prepend' => [
                    'content' => '<span class="glyphicon glyphicon-globe"></span>'
                ]
            ], 'data' => $countries
        ]) ?>
        <?= $form->field($model->survey, 'guest_email', ['template' => mkFieldTemplate('envelope')])->textInput(['maxlength' => true, 'type' => 'email', 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'guest_address', ['template' => mkFieldTemplate('home')])->textArea(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
      </div>
      <hr>
      <?php
        $previous = $current - 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Previous'), [
            'href' => "#step-{$previous}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-xs-6'
        ]);
        $next = $current + 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Next'), [
            'href' => "#step-{$next}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-md-offset-6 col-xs-6'
        ]);
        $current++;
      ?>
    </div>
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container">
      <div class="vpad">
        <?= $form->field($model->survey, 'apartment', ['template' => mkFieldTemplate('tag')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'touroperator_name', ['template' => mkFieldTemplate('briefcase')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'best_employee_name', ['template' => mkFieldTemplate('heart')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
        <?= $form->field($model->survey, 'best_employee_group_name', ['template' => mkFieldTemplate('heart-empty')])->dropDownList($groups, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      </div>
      <hr>
      <?php
        $previous = $current - 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Previous'), [
            'href' => "#step-{$previous}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-xs-6'
        ]);
        $next = $current + 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Next'), [
            'href' => "#step-{$next}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-md-offset-6 col-xs-6'
        ]);
        $current++;
      ?>
    </div>
  </div>
 <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS
$(document).ready(function() {
  $('.fullpage').fullpage({
      paddingTop: '20px',
  });
  $('.riosol-logo').attr('src', $('#riosol-logo').attr('src'));
});
JS;
$this->registerJs($script);
