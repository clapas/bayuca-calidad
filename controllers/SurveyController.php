<?php

namespace app\controllers;

use Yii;
use app\models\Department;
use app\models\Source;
use app\models\Answer;
use app\models\Configuration;
use app\models\Group;
use app\models\GuestToken;
use app\models\Evolution;
use app\models\Survey;
use app\models\SurveyForm;
use app\models\Country;
use app\models\MetExpectation;
use app\models\SurveySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use \yii\helpers\ArrayHelper;

/**
 * SurveyController implements the CRUD actions for Survey model.
 */
class SurveyController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@']
                ], [
                    'actions' => ['guest-create', 'guest-validate'],
                    'allow' => true,
                    'roles' => ['?']
                ]]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     */
    public function actionNewToken()
    {
        $token = new GuestToken();
        $token->save();
        return $this->render('new_token', [
            'model' => $token
        ]);
    }

    /**
     * Lists all Survey models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SurveySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Survey model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, true);
        $answers = ArrayHelper::map($model->getAnswers()->asArray()->all(), 'question_id', 'score');
        $form_model = new SurveyForm();
        $form_model->survey = new Survey();
        $answered_questions = $form_model->getAnswers();
        foreach ($answered_questions as $department => $dept_answers)
            foreach ($dept_answers as $answer) $answer->score = $answers[$answer->question_id];
        return $this->render('view', [
            'model' => $model,
            'answered_questions' => $answered_questions
        ]);
    }

    protected function getDefaultPeriods($from1 = null, $to1 = null, $label1 = null, $from2 = null, $to2 = null, $label2 = null)
    {
        if (!$label1) $label1 = Yii::t('app', 'Current month');
        if (!$label2) $label2 = Yii::t('app', 'Current year');
        $periods = [[
            'label' => $label1,
            'default_from' => date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))),
            'default_to' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('t'), date('Y')))
        ], [
            'label' => $label2,
            'default_from' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y'))),
            'default_to' => date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y')))
        ]];
        if (!$from1) $from1 = $periods[0]['default_from'];
        else if (!$periods[0]['label'] and $from1 != $periods[0]['default_from'])
            $periods[0]['label'] = "$from1 .. $to1";

        if (!$to1) $to1 = $periods[0]['default_to'];
        else if (!$periods[0]['label'] and $to1 != $periods[0]['default_to'])
            $periods[0]['label'] = "$from1 .. $to1";

        $periods[0]['from'] = $from1;
        $periods[0]['to'] = $to1;

        if (!$from2) $from2 = $periods[1]['default_from'];
        else if (!$periods[1]['label'] and $from2 != $periods[1]['default_from'])
            $periods[1]['label'] = "$from2 .. $to2";

        if (!$to2) $to2 = $periods[1]['default_to'];
        else if (!$periods[1]['label'] and $to2 != $periods[1]['default_to'])
            $periods[1]['label'] = "$from2 .. $to2";

        $periods[1]['from'] = $from2;
        $periods[1]['to'] = $to2;

        return $periods;
    }

    /**
     */
    public function actionSummary($from1 = null, $to1 = null, $label1 = null, $from2 = null, $to2 = null, $label2 = null)
    {
        $periods = $this->getDefaultPeriods($from1, $to1, $label1, $from2, $to2, $label2);

        $lang = Yii::$app->language;
        $aux1 = ArrayHelper::index(
            Answer::listSummary($periods[0]['from'], $periods[0]['to'], $lang, 'smly_sum1', 'smly_cnt1'), 'question', 'department');
        $aux2 = ArrayHelper::index(
            Answer::listSummary($periods[1]['from'], $periods[1]['to'], $lang, 'smly_sum2', 'smly_cnt2'), 'question', 'department');
        $questions = ArrayHelper::merge($aux1, $aux2);

        $aux1 = ArrayHelper::index(
            Group::listSummary($periods[0]['from'], $periods[0]['to'], $lang, 'smly_sum1', 'smly_cnt1'), 'name');
        $aux2 = ArrayHelper::index(
            Group::listSummary($periods[1]['from'], $periods[1]['to'], $lang, 'smly_sum2', 'smly_cnt2'), 'name');
        $groups = ArrayHelper::merge($aux1, $aux2);
        $totals = ['smly_cnt1' => 0, 'smly_sum1' => 0, 'global1' => 0, 'smly_cnt2' => 0, 'smly_sum2' => 0, 'global2' =>0];
        foreach ($questions as $department => $question_grp)
          foreach ($question_grp as $question => $stats) {
              $totals['smly_cnt1'] += ArrayHelper::getValue($stats, 'smly_cnt1');
              $totals['smly_sum1'] += ArrayHelper::getValue($stats, 'smly_sum1');
              $totals['smly_cnt2'] += ArrayHelper::getValue($stats, 'smly_cnt2');
              $totals['smly_sum2'] += ArrayHelper::getValue($stats, 'smly_sum2');
          }
        $totals['global1'] = Survey::find()->select('sum(global_score) as sum, count(*) as count')
            ->where('global_score is not null and checkout_date between :from and :to', [
                ':from' => $periods[0]['from'],
                ':to' => $periods[0]['to']
            ])->asArray()->all()[0];
        $totals['global2'] = Survey::find()->select('sum(global_score) as sum, count(*) as count')
            ->where('global_score is not null and checkout_date between :from and :to', [
                ':from' => $periods[1]['from'],
                ':to' => $periods[1]['to']
            ])->asArray()->all()[0];
        $goal = (float) Configuration::find()->where([
            'category' => 'GOAL',
            'name' => date('Y')
        ])->one()->value;
        //\yii\helpers\VarDumper::dump($totals, 5, true); die;
        return $this->render('summary', [
            'questions' => $questions,
            'groups' => $groups,
            'periods' => $periods,
            'totals' => $totals,
            'goal' => $goal
        ]);
    }

    protected function getCreationModels($model) {
        $lang = Yii::$app->language;
        $sources = Source::listAll($lang);
        $countries = Country::listAll($lang);
        $met_expectations = MetExpectation::listAll($lang);
        $evolutions = Evolution::listAll($lang);
        $groups = Group::listAll($lang);
        return [
            'model' => $model,
            'sources' => $sources,
            'evolutions' => $evolutions,
            'countries' => $countries,
            'groups' => $groups,
            'met_expectations' => $met_expectations
        ];
    }
    /**
     * Creates a new Survey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SurveyForm();
        $model->survey = new Survey();

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return $this->redirect(['view', 'id' => $model->survey->id]);
        } else {
            return $this->render('create', $this->getCreationModels($model));
        }
    }

    public function actionGuestValidate() {
        $model = new SurveyForm();
        $model->survey = new Survey();
        $model->scenario = 'guest-survey';
        $model->load(Yii::$app->request->post(), '');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
    /**
     */
    public function actionGuestCreate($token_code = null) {
        $this->layout = 'holder';
        $model = new SurveyForm();
        $model->survey = new Survey();
        $model->scenario = 'guest-survey';

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            GuestToken::findOne($model->token_code)->delete();
            return true; 
        } else {
            if (!Yii::$app->user->isGuest) {
                Yii::$app->user->logout();
                if (!$model->token_code) {
                    $token = new GuestToken();
                    $token->save();
                    $model->token_code = $token->code;
                }
            } else $model->token_code = $token_code;
            return $this->render('guest_create',$this->getCreationModels($model));
        }
    }
    /**
     * Updates an existing Survey model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new SurveyForm();
        $model->survey = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return $this->redirect(['view', 'id' => $model->survey->id]);
        } else {
            return $this->render('update', $this->getCreationModels($model));
        }
    }

    /**
     * Deletes an existing Survey model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        foreach ($model->answers as $answer)
            $answer->delete();
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Survey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Survey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $with_translations = false)
    {
        $query = Survey::find();
        if ($with_translations) $query->with([
            'source.translations',
            'evolution.translations',
            'guestCountry.translations',
            'bestEmployeeGroup.translations',
            'metExpectation.translations'
        ]);
        if (($model = $query->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
