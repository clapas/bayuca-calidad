<?php

namespace app\models;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property integer $index
 * @property string $name
 *
 * @property DepartmentTranslation[] $departmentTranslations
 * @property Survey[] $surveys
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'index' => Yii::t('app', 'Index'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(DepartmentTranslation::className(), ['department_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(Survey::className(), ['best_employee_department_name' => 'name']);
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) use ($language) {
            $q->where(['language_code' => $language]);
        }])->asArray()->all();
        $departments = [];
        foreach ($res as $department) {
            $departments[$department['name']] = ArrayHelper::getValue($department, 'translations.0.translation', $department['name']);
        }
        return $departments;
    }
}
