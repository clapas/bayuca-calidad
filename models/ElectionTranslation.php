<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "election_translation".
 *
 * @property integer $id
 * @property string $election_title
 * @property string $language_code
 * @property string $translation
 *
 * @property Election $electionTitle
 * @property Language $languageCode
 */
class ElectionTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'election_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['election_title', 'translation'], 'string', 'max' => 48],
            [['language_code'], 'string', 'max' => 2],
            [['election_title'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['election_title' => 'title']],
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
            'election_title' => Yii::t('app', 'Election Title'),
            'language_code' => Yii::t('app', 'Language Code'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElectionTitle()
    {
        return $this->hasOne(Election::className(), ['title' => 'election_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }
}
