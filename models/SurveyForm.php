<?php

namespace app\models;

use app\models\Survey;
use app\models\Answer;
use Yii;
use yii\base\Model;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

class SurveyForm extends Model {
    private $_survey;
    private $_empty_answers;
    private $_filled_answers;
    public $token_code;

    public function rules() {
        return [
            [['Survey'], 'required'],
            [['Answer'], 'safe'],
            ['token_code', 'required', 'on' => 'guest-survey'],
            ['token_code', 'exist', 'targetClass' => GuestToken::className(), 'targetAttribute' => ['token_code' => 'code']]
        ];
    }

    public function formName() {
        return '';
    }
    public function afterValidate() {
        if (!static::validateMultiple($this->getAllModels())) {
            foreach ($this->_survey->errors as $attr => $attrErrors)
                foreach ($attrErrors as $error)
                    $this->addError("Survey[{$attr}]", $error); // add an empty error to prevent saving
        }
        parent::afterValidate();
    }

    public function save() {
        if (!$this->validate()) {
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->survey->save()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->saveAnswers()) {
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }
    public function saveAnswers() {
        $keep = [];
        foreach ($this->_filled_answers as $answer) {
            $answer->survey_id = $this->survey->id;
            if (!$answer->save(false)) {
                return false;
            }
            $keep[] = $answer->id;
        }
        $query = Answer::find()->where(['survey_id' => $this->survey->id]);
        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }
        foreach ($query->all() as $answer) {
            $answer->delete();
        }        
        return true;
    }

    public function getSurvey()
    {
        return $this->_survey;
    }

    public function setSurvey($survey) {
        if ($survey instanceof Survey) {
            $this->_survey = $survey;
        } else if (is_array($survey)) {
            $this->_survey->setAttributes($survey);
        }
    }

    public function getAnswers() {
        if ($this->_empty_answers === null) {
            if ($this->survey->isNewRecord) {
                $lang = Yii::$app->language;
                $departments = Question::find()
                    ->select('question.*, (case when dt.id is null then question.department_name else dt.translation end) as department_name')
                    ->with('translations')
                    ->joinWith('department')
                    ->leftJoin('department_translation dt', 'dt.department_name = department.name and language_code = :language_code', [
                        ':language_code' => $lang
                    ])->orderBy('department.index, question.index')->all();
                $departments = ArrayHelper::index($departments, null, 'department_name');
                $this->_empty_answers = [];
                foreach ($departments as $dept => $questions) {
                    $this->_empty_answers[$dept] = [];
                    foreach ($questions as $question) {
                        $answer = new Answer([
                            'question_id' => $question->id
                        ]);
                        $answer->setQuestion($question);
                        $this->_empty_answers[$dept][] = $answer;
                    }
                }
            } else $this->_empty_answers = $this->survey->answers;
        }
        return $this->_empty_answers;
    }

    private function getAnswer($key) {
        $answer = $key && strpos($key, 'new') === false ? Answer::findOne($key) : false;
        if (!$answer) {
            $answer = new Answer();
            $answer->loadDefaultValues();
        }
        return $answer;
    }

    public function setAnswer($answers) {
        $this->_filled_answers = [];
        if (is_array($answers)) foreach ($answers as $question_id => $answer) {
            $this->_filled_answers[] = new Answer([
                'question_id' => $question_id,
                'score' => $answer['score']
            ]);
        }
    }

    public function setAnswers($answers) {
        unset($answers['__id__']); // remove the hidden "new Answer" row
        $this->_empty_answers = [];
        foreach ($answers as $key => $answer) {
            if (is_array($answer)) {
                $this->_empty_answers[$key] = $this->getAnswer($key);
                $this->_empty_answers[$key]->setAttributes($answer);
            } elseif ($answer instanceof Answer) {
                $this->_empty_answers[$answer->id] = $answer;
            }
        }
    }

    public function errorSummary($form) {
        $errorLists = [];
        foreach ($this->getAllModels() as $id => $model) {
            $errorList = $form->errorSummary($model, [
              'header' => '<p>Please fix the following errors for <b>' . $id . '</b></p>',
            ]);
            $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error
            $errorLists[] = $errorList;
        }
        return implode('', $errorLists);
    }

    private function getAllModels() {
        $models = [
            'Survey' => $this->survey,
        ];
        foreach ($this->answers as $dept => $answers) {
            foreach ($answers as $id => $answer) {
                $models['Answer.' . $id] = $answers[$id];
            }
        }
        return $models;
    }
}
