<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department_translation".
 *
 * @property integer $id
 * @property string $language_code
 * @property string $department_name
 * @property string $translation
 *
 * @property Department $departmentName
 * @property Language $languageCode
 */
class DepartmentTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_code'], 'string', 'max' => 2],
            [['department_name', 'translation'], 'string', 'max' => 32],
            [['department_name'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_name' => 'name']],
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
            'department_name' => Yii::t('app', 'Department Name'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentName()
    {
        return $this->hasOne(Department::className(), ['name' => 'department_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageCode()
    {
        return $this->hasOne(Language::className(), ['code' => 'language_code']);
    }
}
