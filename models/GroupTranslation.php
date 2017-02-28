<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group_translation".
 *
 * @property integer $id
 * @property string $language_code
 * @property integer $group_id
 * @property string $translation
 *
 * @property Group $group
 * @property Language $languageCode
 */
class GroupTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id'], 'integer'],
            [['language_code'], 'string', 'max' => 2],
            [['translation'], 'string', 'max' => 32],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
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
            'group_id' => Yii::t('app', 'Group ID'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }
}
