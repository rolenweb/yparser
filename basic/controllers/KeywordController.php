<?php

namespace app\controllers;

use Yii;
use app\models\Keyword;
use app\models\KeywordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use app\models\pizza\City;
/**
 * KeywordController implements the CRUD actions for Keyword model.
 */
class KeywordController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update','view','delete'],
                'rules' => [
                    [
                        'actions' => ['index','create','update','view','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->accessPage(Yii::$app->request->userIP);
                        }
                        
                    ],
                ],
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
     * Lists all Keyword models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KeywordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Keyword model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $keyword = $this->findModel($id);

        switch ($keyword->key) {
            case 'pizza':
                $provider = new ActiveDataProvider([
                    'query' => City::find()
                        ->select(['count(company.id) as countCompany','city.id','city.name','city.created_at','city.updated_at'])
                        ->joinWith(['companies'])
                        ->where(['is not','company.id',null])
                        ->groupBy('city.id'),
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                    'sort' => [
                        'attributes' => [
                            'id',
                            'name' => [
                                'label' => 'Name',
                            ],
                            'countCompany',
                            'created_at',
                            'updated_at'
                        ],
                        'defaultOrder' => [
                            'created_at' => SORT_DESC,
                            
                        ]
                    ],
                ]);
                break;
            
            default:
                # code...
                break;
        }

        return $this->render('view', [
            'model' => $keyword,
            'provider' => $provider,
        ]);
    }

    /**
     * Creates a new Keyword model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Keyword();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Keyword model.
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
     * Deletes an existing Keyword model.
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
     * Finds the Keyword model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Keyword the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Keyword::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
