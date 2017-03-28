<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Survey */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Survey',
]) . $model->survey->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey') . ' #' . $model->survey->id, 'url' => ['view', 'id' => $model->survey->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="survey-update">

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'sources' => $sources,
        'countries' => $countries,
        'groups' => $groups,
        'evolutions' => $evolutions,
        'met_expectations' => $met_expectations
    ]) ?>

</div>
