<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Survey */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-view">

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'checkout_date',
            'apartment',
            'global_score',
            'source_title',
            'guest_name',
            'guest_country_id',
            'guest_email:email',
            'guest_address',
            'touroperator_name',
            'best_employee_name',
            'best_employee_group_name',
            'good_things:ntext',
            'bad_things:ntext',
            'met_expectation_title',
            'evolution_title',
            'suggestions:ntext',
        ],
    ]) ?>

</div>
