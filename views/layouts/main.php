<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$this->beginContent('@app/views/layouts/holder.php');
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Hotel Riosol',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $altLangs = [];
    foreach (Yii::$app->params['languages'] as $code => $lang) {
        if ($code != Yii::$app->language) $altLangs[] = [
            'label' => $lang,
            'url' => Url::to(['site/change-language', 'lang' => $code])
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => Yii::t('app', 'Home'), 'url' => Yii::$app->homeUrl, 'active' => false],
            ['label' => Yii::t('app', 'Surveys'), 'active' => Yii::$app->controller->id == 'survey', 'items' => [
                ['label' => Yii::t('app', 'List'), 'url' => ['survey/index']],
                ['label' => Yii::t('app', 'Create (internal)'), 'url' => ['survey/create']],
                ['label' => Yii::t('app', 'Create (guest)'), 'url' => ['survey/guest-create']],
                ['label' => Yii::t('app', 'Summary'), 'url' => ['survey/summary']],
                ['label' => Yii::t('app', 'New token'), 'url' => ['survey/new-token']],
            ]], ['label' => Yii::t('app', 'Groups'), 'url' => ['group/index']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),[
                'label' => Yii::$app->language,
                'url' => '#',
                'items' => $altLangs
            ]
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Riosol (Riversun Touristic) <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endContent(); ?>
