<style>
  .emoticon svg {
      width: 33px;
      height: 33px;
      position: relative;
      right: 12px;
      top: 24px;
  }
</style>

<?= $this->render('_emoticon_defs') ?>
<div class="row">
  <?= $this->render('_emoticon_cols') ?>
</div>
<?php 
    foreach ($model->answers as $department => $answers) {
        echo '<legend>' . $department . '</legend>';
        foreach ($answers as $answer) {
            echo $form->field($answer, "[{$answer->question_id}]score", [
                'template' => "<div class=\"col-xs-8\">{$answer->question->title}</div>\n{input}\n{hint}\n{error}",
                'options' => ['class' => 'row']
            ])->radioList([
                3 => null,
                2 => null,
                1 => null,
                0 => null
            ], ['itemOptions' => ['labelOptions' => ['class' => 'col-xs-1']]]);
        }
                
} ?>
<?php
$script = <<< JS
  $('input[type="radio"]').click(function() {
      var \$radio = $(this);
      if (\$radio.data('waschecked') == true) {
          \$radio.prop('checked', false);
          \$radio.data('waschecked', false);
      } else
          \$radio.data('waschecked', true);
  });
JS;
$this->registerJs($script);
?>
