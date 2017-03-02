<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "source_translation".
 *
 * @property integer $id
 * @property string $source_title
 * @property string $language_code
 * @property string $translation
 *
 * @property Source $sourceTitle
 * @property Language $languageCode
 */
class SourceTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_title', 'translation'], 'string', 'max' => 48],
            [['language_code'], 'string', 'max' => 2],
            [['source_title'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['source_title' => 'title']],
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
            'source_title' => Yii::t('app', 'Source'),
            'language_code' => Yii::t('app', 'Language'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceTitle()
    {
        return $this->hasOne(Source::className(), ['title' => 'source_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }
}
