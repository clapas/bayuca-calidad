<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Survey */

$this->title = Yii::t('app', 'Create Survey');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-create">

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'elections' => $elections,
        'countries' => $countries,
        'departments' => $departments,
        'evolutions' => $evolutions,
        'met_expectations' => $met_expectations
    ]) ?>

</div>
