<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "evolution".
 *
 * @property string $title
 * @property integer $index
 * @property integer $score
 *
 * @property EvolutionTranslation[] $evolutionTranslations
 * @property Survey[] $surveys
 */
class Evolution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'evolution';
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
        return $this->hasMany(EvolutionTranslation::className(), ['evolution_title' => 'title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(Survey::className(), ['evolution_title' => 'title']);
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) use ($language) {
            $q->where(['language_code' => $language]);
        }])->asArray()->all();
        $evolutions= [];
        foreach ($res as $evolution) {
            $evolutions[$evolution['title']] = ArrayHelper::getValue($evolution, 'translations.0.translation', $evolution['title']);
        }
        return $evolutions;
    }
}
