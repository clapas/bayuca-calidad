<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "met_expectation".
 *
 * @property string $title
 * @property integer $index
 * @property integer $score
 *
 * @property MetExpectationTranslation[] $metExpectationTranslations
 * @property Survey[] $surveys
 */
class MetExpectation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'met_expectation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['index', 'score'], 'integer'],
            [['title'], 'string', 'max' => 48],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'index' => Yii::t('app', 'Index'),
            'score' => Yii::t('app', 'Score'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(MetExpectationTranslation::className(), ['met_expectation_title' => 'title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(Survey::className(), ['met_expectation_title' => 'title']);
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) {
            $q->where(['language_code' => Yii::$app->language]);
        }])->asArray()->all();
        $met_expecs= [];
        foreach ($res as $met_expec) {
            $met_expecs[$met_expec['title']] = ArrayHelper::getValue($met_expec, 'translations.0.translation', $met_expec['title']);
        }
        return $met_expecs;
    }
}
