<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "country_translation".
 *
 * @property integer $id
 * @property string $language_code
 * @property integer $country_id
 * @property string $translation
 *
 * @property Country $country
 * @property Language $languageCode
 */
class CountryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['language_code'], 'string', 'max' => 2],
            [['translation'], 'string', 'max' => 48],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'language_code' => Yii::t('app', 'Language Code'),
            'country_id' => Yii::t('app', 'Country ID'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }

    /**
     * @inheritdoc
     * @return CountryTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountryTranslationQuery(get_called_class());
    }
}
