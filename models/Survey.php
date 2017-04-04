<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;

/**
 * This is the model class for table "survey".
 *
 * @property integer $id
 * @property string $checkout_date
 * @property string $apartment
 * @property integer $global_score
 * @property string $source_title
 * @property string $guest_name
 * @property integer $guest_country_id
 * @property string $guest_email
 * @property string $good_things
 * @property string $bad_things
 * @property string $guest_address
 * @property string $touroperator_name
 * @property string $best_employee_name
 * @property integer $best_employee_group_name
 * @property string $met_expectation_title
 * @property string $evolution_title
 * @property string $suggestions
 *
 * @property Answer[] $answers
 * @property Country $guestCountry
 * @property Department $bestEmployeeGroup
 * @property Source $sourceTitle
 * @property Evolution $evolutionTitle
 * @property MetExpectation $metExpectationTitle
 * @property Touroperator $touroperatorName
 */
class Survey extends \yii\db\ActiveRecord
{
    public $guestCountry_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apartment', 'guest_name'], 'required'],
            ['checkout_date', 'default', 'value' => date('Y-m-d')],
            [['checkout_date'], 'safe'],
            [['global_score', 'guest_country_id'], 'integer'],
            [['suggestions'], 'string'],
            [['apartment'], 'string', 'max' => 5],
            [['best_employee_group_name'], 'string', 'max' => 32],
            [['source_title', 'best_employee_group_name', 'touroperator_name', 'evolution_title', 'met_expectation_title'], 'default', 'value' => null],
            [['source_title', 'guest_name', 'touroperator_name', 'best_employee_name', 'met_expectation_title', 'evolution_title'], 'string', 'max' => 48],
            [['guest_email', 'good_things', 'bad_things'], 'string', 'max' => 128],
            [['guest_address'], 'string', 'max' => 255],
            [['guest_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['guest_country_id' => 'id']],
            [['best_employee_group_name'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['best_employee_group_name' => 'name']],
            [['source_title'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['source_title' => 'title']],
            [['evolution_title'], 'exist', 'skipOnError' => true, 'targetClass' => Evolution::className(), 'targetAttribute' => ['evolution_title' => 'title']],
            [['met_expectation_title'], 'exist', 'skipOnError' => true, 'targetClass' => MetExpectation::className(), 'targetAttribute' => ['met_expectation_title' => 'title']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'checkout_date' => Yii::t('app', 'Checkout date'),
            'apartment' => Yii::t('app', 'Apartment'),
            'global_score' => Yii::t('app', 'Global score'),
            'source_title' => Yii::t('app', 'Why did you choose our hotel?'),
            'guest_name' => Yii::t('app', 'Guest name'),
            'guest_country_id' => Yii::t('app', 'Guest country'),
            'guest_email' => Yii::t('app', 'Guest email'),
            'good_things' => Yii::t('app', 'Good things'),
            'bad_things' => Yii::t('app', 'Bad things'),
            'guest_address' => Yii::t('app', 'Guest address'),
            'touroperator_name' => Yii::t('app', 'Touroperator'),
            'best_employee_name' => Yii::t('app', 'Best employee'),
            'best_employee_group_name' => Yii::t('app', 'Best employee group'),
            'met_expectation_title' => Yii::t('app', 'How did we meet your expectations?'),
            'evolution_title' => Yii::t('app', 'If you are a repeating guest, how do we evolve?'),
            'suggestions' => Yii::t('app', 'Suggestions'),
        ];
    }

    /**
     */
    public function afterFind()
    {
       parent::afterFind();
       if ($this->isRelationPopulated('source') and
           $this->source and $this->source->isRelationPopulated('translations')) {
           $translations = ArrayHelper::map($this->source->translations, 'language_code', 'translation');
           if (!empty($translations[Yii::$app->language]))
               $this->source_title = $translations[Yii::$app->language];
           else if (!empty($translations[Yii::$app->sourceLanguage]))
               $this->source_title = $translations[Yii::$app->sourceLanguage];
       }
       if ($this->isRelationPopulated('bestEmployeeGroup') and $this->bestEmployeeGroup and
           $this->bestEmployeeGroup->isRelationPopulated('translations')) {
           $translations = ArrayHelper::map(
               $this->bestEmployeeGroup->translations, 'language_code', 'translation');
           if (!empty($translations[Yii::$app->language]))
               $this->best_employee_group_name = $translations[Yii::$app->language];
           else if (!empty($translations[Yii::$app->bestEmployeeGroupLanguage]))
               $this->best_employee_group_name = $translations[Yii::$app->sourceLanguage];
       }
       if ($this->isRelationPopulated('evolution') and
           $this->evolution and $this->evolution->isRelationPopulated('translations')) {
           $translations = ArrayHelper::map($this->evolution->translations, 'language_code', 'translation');
           if (!empty($translations[Yii::$app->language]))
               $this->evolution_title = $translations[Yii::$app->language];
           else if (!empty($translations[Yii::$app->evolutionLanguage]))
               $this->evolution_title = $translations[Yii::$app->sourceLanguage];
       }
       if ($this->isRelationPopulated('metExpectation') and
           $this->metExpectation and $this->metExpectation->isRelationPopulated('translations')) {
           $translations = ArrayHelper::map(
               $this->metExpectation->translations, 'language_code', 'translation');
           if (!empty($translations[Yii::$app->language]))
               $this->met_expectation_title = $translations[Yii::$app->language];
           else if (!empty($translations[Yii::$app->metExpectationLanguage]))
               $this->met_expectation_title = $translations[Yii::$app->sourceLanguage];
       }
       if ($this->isRelationPopulated('guestCountry') and
           $this->guestCountry and $this->guestCountry->isRelationPopulated('translations')) {
           $translations = ArrayHelper::map(
               $this->guestCountry->translations, 'language_code', 'translation');
           if (!empty($translations[Yii::$app->language]))
               $this->guestCountry_name = $translations[Yii::$app->language];
           else if (!empty($translations[Yii::$app->guestCountryLanguage]))
               $this->guestCountry_name = $translations[Yii::$app->sourceLanguage];
       }
    }
    /**
     */
    public static function getMetExpectationEvolution($from, $to, $sum_alias = 'sum', $count_alias = 'count')
    {
        $ansi_from = str_replace('-', '', $from);
        if (Yii::$app->db->getDriverName() == 'pgsql') return Yii::$app->db->createCommand("
            select 
                ym as month,
                sum(score) as $sum_alias,
                count(*) as $count_alias 
            from survey s
                 join met_expectation me on (me.title = s.met_expectation_title)
                 right join (
                     select to_char(generate_series(:arg1::date, :arg2, '1 month'), 'yyyy-mm') as ym) gs
                             on ym = to_char(checkout_date, 'yyyy-mm')
            group by ym 
            order by ym", [
                ':arg1' => $from,
                ':arg2' => $to
            ])->queryAll();
        else return Yii::$app->db->createCommand("
            select 
                ym as month,
                sum(score) as $sum_alias,
                count(*) as $count_alias 
            from survey s
                 join met_expectation me on (me.title = s.met_expectation_title)
                 right join (
                     select convert(varchar(7), dateadd(month, d.intvalue, :arg1), 20) as ym
                     from generate_series(0, datediff(m, :arg2, :arg3), 1) d) gs on ym = convert(varchar(7), checkout_date, 20)
            group by ym 
            order by ym", [
            ':arg1' => $ansi_from,
            ':arg2' => $from,
            ':arg3' => $to
        ])->queryAll();
    }
    /**
     */
    public static function getHotelEvolution($from, $to, $sum_alias = 'sum', $count_alias = 'count')
    {
        $ansi_from = str_replace('-', '', $from);
        if (Yii::$app->db->getDriverName() == 'pgsql') return Yii::$app->db->createCommand("
            select 
                ym as month,
                sum(score) as $sum_alias,
                count(*) as $count_alias 
            from survey s
                 join evolution e on (e.title = s.evolution_title)
                 right join (
                     select to_char(generate_series(:arg1::date, :arg2, '1 month'), 'yyyy-mm') as ym) gs
                             on ym = to_char(checkout_date, 'yyyy-mm')
            group by ym 
            order by ym", [
                ':arg1' => $from,
                ':arg2' => $to
            ])->queryAll();
        else return Yii::$app->db->createCommand("
            select 
                ym as month,
                sum(score) as $sum_alias,
                count(*) as $count_alias 
            from survey s
                 join evolution e on (e.title = s.evolution_title)
                 right join (
                     select convert(varchar(7), dateadd(month, d.intvalue, :arg1), 20) as ym
                     from generate_series(0, datediff(m, :arg2, :arg3), 1) d) gs on ym = convert(varchar(7), checkout_date, 20)
            group by ym 
            order by ym", [
            ':arg1' => $ansi_from,
            ':arg2' => $from,
            ':arg3' => $to
        ])->queryAll();
    }
    /**
     */
    public static function getScoreEvolution($from, $to, $sum_alias = 'sum', $count_alias = 'count')
    {
        $ansi_from = str_replace('-', '', $from);
        if (Yii::$app->db->getDriverName() == 'pgsql') return Yii::$app->db->createCommand("
            select 
                ym as month,
                sum(global_score) as $sum_alias,
                count(*) as $count_alias 
            from survey s
                 right join (
                     select to_char(generate_series(:arg1::date, :arg2, '1 month'), 'yyyy-mm') as ym) gs
                             on ym = to_char(checkout_date, 'yyyy-mm')
            where global_score is not null
            group by ym 
            order by ym", [
                ':arg1' => $from,
                ':arg2' => $to
            ])->queryAll();
        else return Yii::$app->db->createCommand("
            select 
                ym as month,
                sum(global_score) as $sum_alias,
                count(*) as $count_alias 
            from survey s
                 right join (
                     select convert(varchar(7), dateadd(month, d.intvalue, :arg1), 20) as ym
                     from generate_series(0, datediff(m, :arg2, :arg3), 1) d) gs on ym = convert(varchar(7), checkout_date, 20)
            where global_score is not null
            group by ym 
            order by ym", [
            ':arg1' => $ansi_from,
            ':arg2' => $from,
            ':arg3' => $to
        ])->queryAll();
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['survey_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuestCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'guest_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBestEmployeeGroup()
    {
        return $this->hasOne(Group::className(), ['name' => 'best_employee_group_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['title' => 'source_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvolution()
    {
        return $this->hasOne(Evolution::className(), ['title' => 'evolution_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetExpectation()
    {
        return $this->hasOne(MetExpectation::className(), ['title' => 'met_expectation_title']);
    }

}
