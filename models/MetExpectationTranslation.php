<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "met_expectation_translation".
 *
 * @property integer $id
 * @property string $met_expectation_title
 * @property string $language_code
 * @property string $translation
 *
 * @property Language $languageCode
 * @property MetExpectation $metExpectationTitle
 */
class MetExpectationTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'met_expectation_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['met_expectation_title', 'translation'], 'string', 'max' => 48],
            [['language_code'], 'string', 'max' => 2],
            [['language_code'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_code' => 'code']],
            [['met_expectation_title'], 'exist', 'skipOnError' => true, 'targetClass' => MetExpectation::className(), 'targetAttribute' => ['met_expectation_title' => 'title']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'met_expectation_title' => Yii::t('app', 'Met Expectation Title'),
            'language_code' => Yii::t('app', 'Language Code'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetExpectationTitle()
    {
        return $this->hasOne(MetExpectation::className(), ['title' => 'met_expectation_title']);
    }
}
