<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property integer $index
 * @property string $department_name
 * @property string $title
 *
 * @property Answer[] $answers
 * @property GroupQuestion[] $groupQuestions
 * @property Department $departmentName
 * @property QuestionTranslation[] $questionTranslations
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index'], 'integer'],
            [['department_name'], 'required'],
            [['department_name', 'title'], 'string', 'max' => 32],
            [['department_name'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_name' => 'name']],
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
            'department_name' => Yii::t('app', 'Department Name'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasMany(GroupQuestion::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['name' => 'department_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(QuestionTranslation::className(), ['question_id' => 'id']);
    }
    /**
     */
    public static function listWithGroup($group_id, $lang)
    {
        return static::find()
            ->select('question.*, (case when qt.translation is not null then qt.translation else title end) as title, (case when dt.translation is not null then dt.translation else name end) as department_name')
            ->asArray()
            ->joinWith(['department' => function($q) use ($lang) {
                $q->leftJoin('department_translation dt', 'dt.department_name = department.name and dt.language_code = :language_code', [
                    ':language_code' => $lang
                ]);
            }])->leftJoin('question_translation qt', 'question.id = qt.question_id and qt.language_code = :language_code', [
                ':language_code' => $lang
            ])->with(['group' => function($q) use($group_id) {
                $q->where(['group_id' => $group_id]);
            }])
            ->orderBy('department.index, question.index')
            ->all();
    }
}
