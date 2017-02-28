<?php

namespace app\controllers;

use Yii;
use app\models\Group;
use app\models\GroupQuestion;
use app\models\Question;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\helpers\ArrayHelper;

/**
 * SurveyController implements the CRUD actions for Survey model.
 */
class GroupController extends Controller
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

    /*
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $selectable = Question::listWithGroup($id, Yii::$app->language);
        $selectable = ArrayHelper::map($selectable, 'id', 'title', 'department_name');
        return $this->render('view', [
            'model' => $model,
            'selectable' => $selectable
        ]);
    }

    /*
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
