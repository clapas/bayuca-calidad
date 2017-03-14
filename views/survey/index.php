<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Surveys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-index">

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Survey'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'checkout_date',
            'apartment',
            'guest_name',
            'global_score', [
                'attribute' => 'guestCountry.name',
                'label' => $searchModel->getAttributeLabel('guest_country')
            ],
            //'source_title',
            // 'guest_email:email',
            // 'guest_address',
            // 'touroperator_name',
            // 'best_employee_name',
            // 'best_employee_group_name',
            // 'met_expectation_title',
            // 'evolution_title',
            // 'suggestions:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
