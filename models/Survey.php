<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "survey".
 *
 * @property integer $id
 * @property string $checkout_date
 * @property string $apartment
 * @property integer $global_score
 * @property string $election_title
 * @property string $guest_name
 * @property integer $guest_country_id
 * @property string $guest_email
 * @property string $good_things
 * @property string $bad_things
 * @property string $guest_address
 * @property string $touroperator_name
 * @property string $best_employee_name
 * @property integer $best_employee_department_name
 * @property string $met_expectation_title
 * @property string $evolution_title
 * @property string $suggestions
 *
 * @property Answer[] $answers
 * @property Country $guestCountry
 * @property Department $bestEmployeeDepartment
 * @property Election $electionTitle
 * @property Evolution $evolutionTitle
 * @property MetExpectation $metExpectationTitle
 * @property Touroperator $touroperatorName
 */
class Survey extends \yii\db\ActiveRecord
{
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
            [['checkout_date', 'apartment', 'global_score', 'election_title'], 'required'],
            [['checkout_date'], 'safe'],
            [['global_score', 'guest_country_id'], 'integer'],
            [['suggestions'], 'string'],
            [['apartment'], 'string', 'max' => 5],
            [['best_employee_department_name'], 'string', 'max' => 32],
            [['best_employee_department_name', 'touroperator_name', 'evolution_title', 'met_expectation_title'], 'default', 'value' => null],
            [['election_title', 'guest_name', 'touroperator_name', 'best_employee_name', 'met_expectation_title', 'evolution_title'], 'string', 'max' => 48],
            [['guest_email', 'good_things', 'bad_things'], 'string', 'max' => 128],
            [['guest_address'], 'string', 'max' => 255],
            [['guest_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['guest_country_id' => 'id']],
            [['best_employee_department_name'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['best_employee_department_name' => 'name']],
            [['election_title'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['election_title' => 'title']],
            [['evolution_title'], 'exist', 'skipOnError' => true, 'targetClass' => Evolution::className(), 'targetAttribute' => ['evolution_title' => 'title']],
            [['met_expectation_title'], 'exist', 'skipOnError' => true, 'targetClass' => MetExpectation::className(), 'targetAttribute' => ['met_expectation_title' => 'title']],
            [['touroperator_name'], 'exist', 'skipOnError' => true, 'targetClass' => Touroperator::className(), 'targetAttribute' => ['touroperator_name' => 'name']],
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
            'election_title' => Yii::t('app', 'Why did you choose our hotel?'),
            'guest_name' => Yii::t('app', 'Guest name'),
            'guest_country_id' => Yii::t('app', 'Guest country'),
            'guest_email' => Yii::t('app', 'Guest email'),
            'good_things' => Yii::t('app', 'Good things'),
            'bad_things' => Yii::t('app', 'Bad things'),
            'guest_address' => Yii::t('app', 'Guest address'),
            'touroperator_name' => Yii::t('app', 'Touroperator'),
            'best_employee_name' => Yii::t('app', 'Best employee'),
            'best_employee_department_name' => Yii::t('app', 'Best employee department'),
            'met_expectation_title' => Yii::t('app', 'How did we meet your expectations?'),
            'evolution_title' => Yii::t('app', 'If you are a repeating guest, how does Riosol evolve?'),
            'suggestions' => Yii::t('app', 'Suggestions'),
        ];
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
    public function getBestEmployeeDepartment()
    {
        return $this->hasOne(Department::className(), ['name' => 'best_employee_department_name']);
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
    public function getEvolutionTitle()
    {
        return $this->hasOne(Evolution::className(), ['title' => 'evolution_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetExpectationTitle()
    {
        return $this->hasOne(MetExpectation::className(), ['title' => 'met_expectation_title']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTouroperatorName()
    {
        return $this->hasOne(Touroperator::className(), ['name' => 'touroperator_name']);
    }
}
