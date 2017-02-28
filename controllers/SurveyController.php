<?php

namespace app\controllers;

use Yii;
use app\models\Department;
use app\models\Election;
use app\models\Answer;
use app\models\Group;
use app\models\Evolution;
use app\models\Survey;
use app\models\SurveyForm;
use app\models\Country;
use app\models\MetExpectation;
use app\models\SurveySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function getDefaultPeriods($from1 = null, $to1 = null, $from2 = null, $to2 = null)
    {
        $periods = [[
            'label' => Yii::t('app', 'Current month'),
            'default_from' => date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))),
            'default_to' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('t'), date('Y')))
        ], [
            'label' => Yii::t('app', 'Current year'),
            'default_from' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y'))),
            'default_to' => date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y')))
        ]];
        if (!$from1) $from1 = $periods[0]['default_from'];
        else if ($from1 != $periods[0]['default_from']) $periods[0]['label'] = "$from1 .. $to1";

        if (!$to1) $to1 = $periods[0]['default_to'];
        else if ($to1 != $periods[0]['default_to']) $periods[0]['label'] = "$from1 .. $to1";

        $periods[0]['from'] = $from1;
        $periods[0]['to'] = $to1;

        if (!$from2) $from2 = $periods[1]['default_from'];
        else if ($from2 != $periods[1]['default_from']) $periods[1]['label'] = "$from2 .. $to2";

        if (!$to2) $to2 = $periods[1]['default_to'];
        else if ($to2 != $periods[1]['default_to']) $periods[1]['label'] = "$from2 .. $to2";

        $periods[1]['from'] = $from2;
        $periods[1]['to'] = $to2;

        return $periods;
    }

    /**
     */
    public function actionSummary($from1 = null, $to1 = null, $from2 = null, $to2 = null)
    {
        $periods = $this->getDefaultPeriods($from1, $to1, $from2, $to2);

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
        $totals['global1'] = Survey::find()->select('sum(global_score), count(*)')
            ->where('checkout_date between :from and :to', [
                ':from' => $periods[0]['from'],
                ':to' => $periods[0]['to']
            ])->asArray()->all()[0];
        $totals['global2'] = Survey::find()->select('sum(global_score), count(*)')
            ->where('checkout_date between :from and :to', [
                ':from' => $periods[1]['from'],
                ':to' => $periods[1]['to']
            ])->asArray()->all()[0];
        //\yii\helpers\VarDumper::dump($totals, 5, true); die;
        return $this->render('summary', [
            'questions' => $questions,
            'groups' => $groups,
            'periods' => $periods,
            'totals' => $totals
        ]);
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
            $lang = Yii::$app->language;
            $elections = Election::listAll($lang);
            $countries = Country::listAll($lang);
            $met_expectations = MetExpectation::listAll($lang);
            $evolutions = Evolution::listAll($lang);
            $departments = Department::listAll($lang);
            return $this->render('create', [
                'model' => $model,
                'elections' => $elections,
                'evolutions' => $evolutions,
                'countries' => $countries,
                'departments' => $departments,
                'met_expectations' => $met_expectations
            ]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Survey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Survey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Survey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
