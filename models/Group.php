<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;

/**
 * This is the model class for table grp.
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
        return 'grp';
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
            'questions' => Yii::t('app', 'Questions'),
        ];
    }

    /**
     */
    public function afterFind()
    {
       parent::afterFind();
       if ($this->isRelationPopulated('translations')) {
           $translations = ArrayHelper::map($this->translations, 'language_code', 'translation');
           if (!empty($translations[Yii::$app->language]))
               $this->name = $translations[Yii::$app->language];
           else if (!empty($translations[Yii::$app->sourceLanguage]))
               $this->name = $translations[Yii::$app->sourceLanguage];
       }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(Survey::className(), ['best_employee_group_name' => 'name']);
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
            }])->leftJoin('group_translation gt', 'gt.group_id = grp.id and language_code = :language_code', [
                ':language_code' => $lang
            ])->select("(case when gt.translation is not null then gt.translation else name end) as name, sum(score) as {$sum_alias}, count(*) as {$count_alias}")
            ->where('score is not null')
            ->orderBy('grp.index')
            ->groupBy('grp.index, name, gt.translation')
            ->createCommand()->queryAll();
    }

    public static function listAll($language) {
        $res = static::find()->with(['translations' => function($q) use ($language) {
            $q->where(['language_code' => $language]);
        }])->asArray()->all();
        $groups = [];
        foreach ($res as $group) {
            $groups[$group['name']] = ArrayHelper::getValue($group, 'translations.0.translation', $group['name']);
        }
        return $groups;
    }
}
