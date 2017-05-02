<?php
use yii\helpers\ArrayHelper;
?>

<div class="row">
  <div class="col-md-7 col-xs-7">
    <h2><?= Yii::t('app', 'Smiley questions') ?></h2>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th rowspan="2" class="text-center"><?= Yii::t('app', 'Department') ?></th>
          <th rowspan="2" class="text-center"><?= Yii::t('app', 'Question') ?></th>
          <th colspan="2" class="text-center"><?= $periods[0]['label'] ?></th>
          <th colspan="2" class="text-center"><?= $periods[1]['label'] ?></th>
        </tr>
        <tr>
          <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
        </tr>
      </thead>
      <tbody>
          <?php foreach ($questions as $department => $question_grp): ?>
            <?php $first = true ?>
            <?php foreach ($question_grp as $question => $stats): ?>
              <tr>
                <?php if ($first): ?>
                  <td rowspan="<?= count($question_grp) ?>"><?= $department ?></td>
                  <?php $first = false ?>
                <?php endif; ?>
                <td><?= $question ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                    33.333 * ArrayHelper::getValue($stats, 'smly_sum1', 0)/ArrayHelper::getValue($stats, 'smly_cnt1', 1), 2) ?></td>
                <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt1', 0) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                    33.333 * ArrayHelper::getValue($stats, 'smly_sum2', 0)/ArrayHelper::getValue($stats, 'smly_cnt2', 1), 2) ?></td>
                <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt2', 0) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-md-5 col-xs-5">
    <h2><?= Yii::t('app', 'Smiley groups') ?></h2>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th rowspan="2" class="text-center"><?= Yii::t('app', 'Group') ?></th>
          <th colspan="2" class="text-center"><?= $periods[0]['label'] ?></th>
          <th colspan="3" class="text-center"><?= $periods[1]['label'] ?></th>
        </tr>
        <tr>
          <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Avg.') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Count') ?></th>
          <th class="text-center"><?= Yii::t('app', 'Goal') ?></th>
        </tr>
      </thead>
      <tbody>
          <?php foreach ($groups as $group=> $stats): ?>
            <tr>
              <td><?= $group ?></td>
              <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                  33.333 * ArrayHelper::getValue($stats, 'smly_sum1', 0)/ArrayHelper::getValue($stats, 'smly_cnt1', 1), 2) ?></td>
              <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt1', 0) ?></td>
              <td class="text-right"><?= Yii::$app->formatter->asDecimal(
                  33.333 * ArrayHelper::getValue($stats, 'smly_sum2', 0)/ArrayHelper::getValue($stats, 'smly_cnt2', 1), 2) ?></td>
              <td class="text-right"><?= ArrayHelper::getValue($stats, 'smly_cnt2', 0) ?></td>
              <?php $spread = 33.333 * ArrayHelper::getValue($stats, 'smly_sum2', 0)/ArrayHelper::getValue($stats, 'smly_cnt2', 1) - $goal ?>
              <td class="text-right <?= $spread < 0 ? 'text-danger' : 'text-success'?>"><?= Yii::$app->formatter->asDecimal($spread, 2) ?></td>
            </tr>
          <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
