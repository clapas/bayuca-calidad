<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * @property string $code
 * @property string $name
 *
 * @property OfferTitle[] $offerTitles
 * @property OfferDescription[] $offerDescriptions
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
        ];
    }
    /**
     */
    public function getTranslations() {
        return $this->hasMany(CountryTranslation::className(), ['country_id' => 'id']);
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) {
            $q->where(['language_code' => Yii::$app->language]);
        }])->asArray()->all();
        $countries = [];
        foreach ($res as $country) {
            $countries[$country['id']] = ArrayHelper::getValue($country, 'translations.0.translation', $country['name']);
        }
        return $countries;
    }
}
