<?php
use app\assets\FullpageAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;

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
$lang = Yii::$app->language;
?>
<?= $this->render('_emoticon_defs') ?>
<div class="fullpage-menu">
  <?= Html::a(Yii::t('app', 'Get out'), ['site/index'], ['class' => 'btn-link']) ?>
  <?= Html::a(Yii::t('app', 'Restart'), '#step-0', ['class' => 'btn-link']) ?>
</div>
<div class="fullpage-footer">
  <img src="<?= Url::to('@web/img/logo-riosol.png')?>"></img>
</div>
<div class="fullpage">
  <?php $form = ActiveForm::begin([
      'validationUrl' => ['survey/guest-validate'],
      'id' => 'guest-form'
  ]); ?> <div class="section" data-anchor="step-0">
    <div class="container">
      <div class="row">
        <div class="col-md-11 col-md-offset-1 col-xs-10 col-xs-offset-1">
          <div class="row">
            <?php $langs_configs = [[
                  'lang' => 'es',
                  'label' => 'Español',
                  'classes' => 'col-xs-2 col-sm-1 col-sm-offset-0 col-xs-offset-1 language-wrapper'
              ], [
                  'lang' => 'en',
                  'label' => 'English',
                  'classes' => 'col-xs-2 col-sm-1 col-xs-offset-2 col-sm-offset-1 language-wrapper'
              ], [
                  'lang' => 'de',
                  'label' => 'Deutsch',
              ], [
                  'lang' => 'sv',
                  'label' => 'Svenska',
                  'classes' => 'col-xs-2 col-sm-1 col-xs-offset-1 col-sm-offset-1 language-wrapper'
              ], [
                  'lang' => 'da',
                  'label' => 'Dansk',
                  'classes' => 'col-xs-2 col-sm-1 col-xs-offset-2 col-sm-offset-1 language-wrapper'
              ], [
                  'lang' => 'no',
                  'label' => 'Norsk',
              ]];
              foreach ($langs_configs as $lang_config) {
                  if (isset($lang_config['classes'])) $classes = $lang_config['classes'];
                  if ($lang === $lang_config['lang']) echo Html::beginTag('div', ['class' => "{$classes} active"]); 
                  else echo Html::beginTag('a', ['href' => Url::to(['site/change-language', 'lang' => $lang_config['lang']]), 'class' => $classes]);
                  echo Html::img('@web/img/languages.png', ['class' => "language language-{$lang_config['lang']}"]);
                  echo $lang_config['label'];
                  if ($lang === $lang_config['lang']) echo Html::endTag('div'); 
                  else echo Html::endTag('a');
              } ?>
          </div>
        </div>
      </div>
      <!--
      <div class="text-center vxpad">
        <img src="<?= Url::to('@web/img/logo-riosol.png')?>"></img>
      </div>
      -->
    </div>
    <div class="vpad visible-xs-block visible-sm-block"></div>
    <div class="container-fluid front-panel">
      <div class="page-header">
        <h1 class="page-title"><?= Yii::t('app', 'Quality survey') ?></h1>
      </div>
      <p class="lead"><?= Yii::t('app', 'Dear guest') ?>,</p>
      <p class="lead"><?= Yii::t('app', 'your opinion and suggestions, even your complaints, are the best tool to improve our facilities and services, so we sincerely thank you to take a few minutes of your holiday to complete this questionnaire before leaving the hotel') ?>.</p>
      <p class="lead"><?= Yii::t('app', 'We assure you that all your contributions will be taken into account.')?></p>
      <p class="lead"><strong><?= Yii::t('app', 'The direction') ?></strong></p>
      <div class="row" style="position: relative; top: -25px">
        <div class="col-md-3 col-md-offset-9 col-xs-6 col-xs-offset-6">
          <a class="btn btn-xl btn-block btn-success" href="#step-1"><?= Yii::t('app', 'Start') ?></a>
        </div>
      </div>
    </div>
  </div>
  <?php
    $previous = $next = null;
    $current = 1;
    foreach ($model->answers as $department => $answers) {
      echo Html::beginTag('div', ['class' => 'section', 'data-anchor' => "step-{$current}"]);
        echo Html::beginTag('div', ['class' => 'container panel']);
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
    <div class="container panel">
      <?= $form->field($model->survey, 'source_title', ['template' => mkFieldTemplate('magnet')])->dropDownList($sources, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      <?= $form->field($model->survey, 'touroperator_name', ['template' => mkFieldTemplate('briefcase')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
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
      <hr>
      <div class="row">
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
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container panel">
      <?= $form->field($model->survey, 'met_expectation_title', ['template' => mkFieldTemplate('apple')])->dropDownList($met_expectations, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      <?= $form->field($model->survey, 'evolution_title', ['template' => mkFieldTemplate('signal')])->dropDownList($evolutions, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      <?= $form->field($model->survey, 'global_score', ['template' => mkFieldTemplate('certificate')])->dropDownList(range(0,10), ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      <hr>
      <div class="row">
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
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container panel">
      <?= $form->field($model->survey, 'good_things', ['template' => mkFieldTemplate('thumbs-up')])->textArea(['maxlength' => true, 'class' => 'input-lg form-control', 'rows' => 2]) ?>
      <?= $form->field($model->survey, 'bad_things', ['template' => mkFieldTemplate('thumbs-down')])->textArea(['maxlength' => true, 'class' => 'input-lg form-control', 'rows' => 2]) ?>
      <?= $form->field($model->survey, 'suggestions', ['template' => mkFieldTemplate('bullhorn')])->textarea(['class' => 'input-lg form-control', 'rows' => 3]) ?>
      <hr>
      <div class="row">
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
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div class="container panel">
      <?= $form->field($model->survey, 'guest_name', ['template' => mkFieldTemplate('user')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control'])?>
      <?= $form->field($model->survey, 'guest_email', ['template' => mkFieldTemplate('envelope')])->textInput(['maxlength' => true, 'type' => 'email', 'class' => 'input-lg form-control']) ?>
      <?= $form->field($model->survey, 'guest_address', ['template' => mkFieldTemplate('home')])->textArea(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
      <hr>
      <div class="row">
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
  </div>
  <div class="section" data-anchor="step-<?= $current ?>">
    <div id="finish-panel" class="container panel">
      <?= $form->field($model->survey, 'apartment', ['template' => mkFieldTemplate('tag')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
      <?= $form->field($model->survey, 'best_employee_name', ['template' => mkFieldTemplate('heart')])->textInput(['maxlength' => true, 'class' => 'input-lg form-control']) ?>
      <?= $form->field($model->survey, 'best_employee_group_name', ['template' => mkFieldTemplate('heart-empty')])->dropDownList($groups, ['prompt' => '', 'class' => 'input-lg form-control']) ?>
      <hr>
      <div class="row">
      <?php
        $previous = $current - 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Previous'), [
            'href' => "#step-{$previous}",
            'class' => 'btn btn-xl btn-block btn-primary'
        ]), [
            'class' => 'col-md-3 col-xs-6'
        ]);
        $next = $current + 1;
        echo Html::tag('div', Html::tag('a', Yii::t('app', 'Finish'), [
            'href' => "#step-{$next}",
            'class' => 'btn btn-xl btn-block btn-danger btn-submit'
        ]), [
            'class' => 'col-md-3 col-md-offset-6 col-xs-6'
        ]);
        $current++;
      ?>
      </div>
    </div>
    <div id="success-panel" class="container panel hidden">
      <h1 class="page-header"><?= Yii::t('app', 'Thank you!') ?></h1>
      <div class="vpad">
        <p class="lead"><?= Yii::t('app', 'We have received your answers.') ?></p>
      </div>
    </div>
    <div id="wait-panel" class="container panel hidden">
      <h1 class="page-header"><?= Yii::t('app', 'We are sending your answers') ?>&hellip;</h1>
      <div class="vpad">
        <div class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 0">
          </div>
        </div> 
      </div>
      <p><?= Yii::t('app', 'Please, wait a few moments') ?></p>
    </div>
  </div>
<?php ActiveForm::end(); ?>
</div>
  <?php Modal::begin([
      'header' => '<h4 class="modal-title">' . Yii::t('app', 'Oops! You need a security token') . '</h4>',
      'clientOptions' => ['backdrop' => 'static'],
      'size' => Modal::SIZE_SMALL,
      'closeButton' => false,
  ]); ?>
    <?= $form->field($model, 'token_code', ['enableAjaxValidation' => true])->textInput(['form' => $form->id]) ?>
    <div class="form-group">
      <button class="btn btn-warning btn-validate"><?= Yii::t('app', 'Validate') ?></button>
    </div>
    <p class="text-info"><small><?= Yii::t('app', 'You can ask for a token in reception. Thank you.') ?></small></p>
  <?php Modal::end(); ?>
<?php
$tokenValidationUrl = Url::to(['survey/guest-validate']);
$script = <<< JS
var tokenValidationUrl = '$tokenValidationUrl';
$(document).ready(function() {
  $('.fullpage').fullpage({fitToSection: false});
  $.fn.fullpage.setAutoScrolling(false);
  $.fn.fullpage.setAllowScrolling(false);
  var \$finishPanel = $('#finish-panel');
  var \$waitPanel = $('#wait-panel');
  var \$successPanel = $('#success-panel');
  var \$form = $('form');
  \$form.on('beforeSubmit', function(e, msgs, attrs) {
      return false;
  });
  \$form.on('afterValidate', function(e, msgs, attrs) {
      if (attrs.length != 0) return false;
      progress = 0;
      \$finishPanel.addClass('hidden');
      \$waitPanel.removeClass('hidden');
      progressInteval = setInterval(function() {
          progress += (100 - progress) / 100.;
          \$progressBar.css('width', (progress) + '%');
      }, 200);
      $.ajax({
          type: 'POST',
          url: \$form.attr('action'),
          data: \$form.serialize(),
          error: function(jqXHR, textStatus, errorThrown) {
              \$finishPanel.removeClass('hidden');
              \$waitPanel.removeClass('hidden');
          }, success: function(data, textStatus, jqXHR) {
              \$waitPanel.addClass('hidden');
              \$successPanel.removeClass('hidden');
          }, complete: function() {
              clearInterval(progressInterval);
          }
      });
      return false;
  });
  var \$progressBar = $('#wait-panel .progress-bar');
  var progressInterval;
  var progress;
  $('.btn-submit').on('click', function() {
      \$form.submit();
      return false;
  });
  $('input[type="radio"]').click(function() {
      var \$radio = $(this);
      if (\$radio.data('waschecked') == true) {
          \$radio.prop('checked', false);
          \$radio.data('waschecked', false);
      } else
          \$radio.data('waschecked', true);
  });
  $('input, select').on('keyup', function(e) {
      if(e.keyCode == 13) {
          $(this).blur();
          return false;
      }
  });
  if (!$('#token_code').val()) {
      $('#w0').modal('show');
  }
  \$tokenCode = $('input[name="token_code"]');
  $('.btn-validate').on('click', function() {
      \$tokenCode.closest('.form-group').removeClass('has-error');
      \$tokenCode.closest('.form-group').removeClass('has-success');
      \$tokenCode.siblings('.help-block').html('');
      $.ajax({
          url: tokenValidationUrl,
          data: $('#token_code').serialize(),
          dataType: 'json',
          method: 'post',
          success: function(data) {
              if (!data.token_code) {
                  \$tokenCode.closest('.form-group').addClass('has-success');
                  $('#w0').modal('hide');
              } else {
                  \$tokenCode.closest('.form-group').addClass('has-error');
                  \$tokenCode.siblings('.help-block').html(data.token_code);
              }
          }
      });
  });
});
JS;
$this->registerJs($script);
