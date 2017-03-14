<h1 class="page-header"><?= Yii::t('app', 'Guest survey tokens') ?></h1>
<dl class="dl-horizontal lead">
  <dt><?= Yii::t('app', 'Code') ?>*</dt>
  <dd><code><?= $model->code ?></code></dd>
  <dt><?= Yii::t('app', 'Valid until') ?></dt>
  <dd><?= Yii::$app->formatter->asDatetime($model->valid_until, 'long') ?></dd>
</dl>
<p>* <?= Yii::t('app', 'The code is case sensitive') ?></p>
