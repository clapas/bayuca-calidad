<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evolution_translation".
 *
 * @property integer $id
 * @property string $evolution_title
 * @property string $language_code
 * @property string $translation
 *
 * @property Evolution $evolutionTitle
 * @property Language $languageCode
 */
class EvolutionTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'evolution_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['evolution_title', 'translation'], 'string', 'max' => 48],
            [['language_code'], 'string', 'max' => 2],
            [['evolution_title'], 'exist', 'skipOnError' => true, 'targetClass' => Evolution::className(), 'targetAttribute' => ['evolution_title' => 'title']],
            [['language_code'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_code' => 'code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'evolution_title' => Yii::t('app', 'Evolution Title'),
            'language_code' => Yii::t('app', 'Language Code'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvolutionTitle()
    {
        return $this->hasOne(Evolution::className(), ['title' => 'evolution_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }
}
