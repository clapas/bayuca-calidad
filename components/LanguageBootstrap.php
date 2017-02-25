<?php
namespace app\components;

use Yii;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

use app\models\Language;

class LanguageBootstrap implements BootstrapInterface {
    /**
    * Bootstrap method to be called during application bootstrap stage.
    * @param Application $app the application currently running
    */
    public function bootstrap($app) {
        $languages = ArrayHelper::map(Language::find()->all(), 'code', 'name');
        Yii::$app->params['languages'] = $languages;
        //ArrayHelper::remove($languages, Yii::$app->sourceLanguage);
        if (Yii::$app->session->get('lang'))
            Yii::$app->language = Yii::$app->session->get('lang');
    }
}
