<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "election".
 *
 * @property string $title
 * @property integer $index
 *
 * @property ElectionTranslation[] $electionTranslations
 * @property Survey[] $surveys
 */
class Election extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'election';
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
        return $this->hasMany(ElectionTranslation::className(), ['election_title' => 'title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(Survey::className(), ['election_title' => 'title']);
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) use ($language) {
            $q->where(['language_code' => $language]);
        }])->asArray()->all();
        $elections = [];
        foreach ($res as $election) {
            $elections[$election['title']] = ArrayHelper::getValue($election, 'translations.0.translation', $election['title']);
        }
        return $elections;
    }
}
