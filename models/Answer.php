<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $survey_id
 * @property integer $score
 *
 * @property Question $question
 * @property Survey $survey
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id'], 'required'],
            [['question_id', 'survey_id', 'score'], 'integer'],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
            [['survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => Survey::className(), 'targetAttribute' => ['survey_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'survey_id' => Yii::t('app', 'Survey ID'),
            'score' => Yii::t('app', 'Score'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurvey()
    {
        return $this->hasOne(Survey::className(), ['id' => 'survey_id']);
    }

    /**
     */
    public static function listSummary($from, $to, $lang, $sum_alias = 'sum', $count_alias = 'count')
    {
        return static::find()
            ->where('score is not null')
            ->joinWith(['survey' => function($q) use ($from, $to) {
                $q->where('checkout_date between :from and :to', [
                    ':from' => $from,
                    ':to' => $to
                ]);
            }, 'question.department'])->leftJoin('department_translation dt', 'dt.department_name = department.name and dt.language_code = :language_code', [
                    ':language_code' => $lang
            ])->leftJoin('question_translation qt', 'qt.question_id = question.id and qt.language_code = :language_code', [
                    ':language_code' => $lang
            ])->select("(case dt.translation is null when false then dt.translation else department.name end) as department, (case qt.translation is null when false then qt.translation else title end) question, sum(score) as {$sum_alias}, count(*) as {$count_alias}")
            ->orderBy('department.index, question.index')
            ->groupBy('dt.translation, qt.translation, department.index, question.index, department.name, title')
            ->createCommand()->queryAll();
    }
}
