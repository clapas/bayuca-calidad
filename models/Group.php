<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property integer $id
 * @property string $name
 *
 * @property GroupQuestion[] $groupQuestions
 * @property GroupTranslation[] $groupTranslations
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id'])
            ->viaTable(GroupQuestion::tableName(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupQuestions()
    {
        return $this->hasMany(GroupQuestion::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(GroupTranslation::className(), ['group_id' => 'id']);
    }

    /**
     */
    public static function listSummary($from, $to, $lang, $sum_alias = 'sum', $count_alias = 'count')
    {
        return static::find()
            ->joinWith(['questions.answers.survey' => function($q) use ($from, $to) {
                $q->where('checkout_date between :from and :to', [
                    ':from' => $from,
                    ':to' => $to
                ]);
            }])->leftJoin('group_translation gt', 'gt.group_id = "group".id and language_code = :language_code', [
                ':language_code' => $lang
            ])->select("(case gt.translation is null when false then gt.translation else name end) as name, sum(score) as {$sum_alias}, count(*) as {$count_alias}")
            ->orderBy('"group".index')
            ->groupBy('"group".index, name, gt.translation')
            ->createCommand()->queryAll();
    }
}
