<style>
  .emoticon svg {
      width: 33px;
      height: 33px;
      position: relative;
      right: 12px;
      top: 24px;
  }
</style>

<svg style="display: none">
  <defs>
    <path transform="scale(1.5)" id="emoticon-excited" fill="#777777" d="M12,17.5C14.33,17.5 16.3,16.04 17.11,14H6.89C7.69,16.04 9.67,17.5 12,17.5M8.5,11A1.5,1.5 0 0,0 10,9.5A1.5,1.5 0 0,0 8.5,8A1.5,1.5 0 0,0 7,9.5A1.5,1.5 0 0,0 8.5,11M15.5,11A1.5,1.5 0 0,0 17,9.5A1.5,1.5 0 0,0 15.5,8A1.5,1.5 0 0,0 14,9.5A1.5,1.5 0 0,0 15.5,11M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
    <path transform="scale(1.5)" id="emoticon-happy" fill="#777777" d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z"></path>
    <path transform="scale(1.5)" id="emoticon-neutral" fill="#777777" d="M8.5,11A1.5,1.5 0 0,1 7,9.5A1.5,1.5 0 0,1 8.5,8A1.5,1.5 0 0,1 10,9.5A1.5,1.5 0 0,1 8.5,11M15.5,11A1.5,1.5 0 0,1 14,9.5A1.5,1.5 0 0,1 15.5,8A1.5,1.5 0 0,1 17,9.5A1.5,1.5 0 0,1 15.5,11M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M9,14H15A1,1 0 0,1 16,15A1,1 0 0,1 15,16H9A1,1 0 0,1 8,15A1,1 0 0,1 9,14Z"></path>
    <path transform="scale(1.5)" id="emoticon-sad" fill="#777777" d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M15.5,8C16.3,8 17,8.7 17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M12,14C13.75,14 15.29,14.72 16.19,15.81L14.77,17.23C14.32,16.5 13.25,16 12,16C10.75,16 9.68,16.5 9.23,17.23L7.81,15.81C8.71,14.72 10.25,14 12,14Z"></path>
  </defs>
</svg>

<div class="row">
  <div class="emoticon col-xs-1 col-xs-offset-8">
    <svg>
      <use xlink:href="#emoticon-excited"></use>
    </svg>
  </div>
  <div class="emoticon col-xs-1">
    <svg>
      <use xlink:href="#emoticon-happy"></use>
    </svg>
  </div>
  <div class="emoticon col-xs-1">
    <svg>
      <use xlink:href="#emoticon-neutral"></use>
    </svg>
  </div>
  <div class="emoticon col-xs-1">
    <svg>
      <use xlink:href="#emoticon-sad"></use>
    </svg>
  </div>
</div>
<?php 
    foreach ($model->answers as $department => $answers) {
        echo '<legend>' . $department . '</legend>';
        foreach ($answers as $answer) {
            echo $form->field($answer, "[{$answer->question_id}]score", [
                'template' => "<div class=\"col-xs-8\">{$answer->question->title}</div>\n{input}\n{hint}\n{error}",
                'options' => ['class' => 'row']
            ])->radioList([
                25 => null,
                50 => null,
                75 => null,
                100 => null
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