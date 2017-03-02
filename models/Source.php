<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "source".
 *
 * @property string $title
 * @property integer $index
 *
 * @property SourceTranslation[] $sourceTranslations
 * @property Survey[] $surveys
 */
class Source extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['index'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(SourceTranslation::className(), ['source_title' => 'title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(Survey::className(), ['source_title' => 'title']);
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) use ($language) {
            $q->where(['language_code' => $language]);
        }])->asArray()->all();
        $sources = [];
        foreach ($res as $source) {
            $sources[$source['title']] = ArrayHelper::getValue($source, 'translations.0.translation', $source['title']);
        }
        return $sources;
    }
}
