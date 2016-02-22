<?php

namespace infoweb\partials\controllers;

use Yii;
use infoweb\partials\models\PagePartial;
use infoweb\partials\models\Lang;
use infoweb\partials\models\Search;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\base\Model;

/**
 * PagePartialController implements the CRUD actions for PagePartial model.
 */
class PagePartialController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PagePartial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new PagePartial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $languages = Yii::$app->params['languages'];
        
        // Load the model, default to 'user-defined' type
        $model = new PagePartial(['type' => 'user-defined']);
        
        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);

                // Get the translation models
                // Create an array of translation models
                $translationModels = [];

                foreach ($languages as $languageId => $languageName) {
                    $translationModels[$languageId] = new Lang(['language' => $languageId]);
                }

                // Populate the translation models
                Model::loadMultiple($translationModels, $post);

                // Validate the model and translation models
                $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));

                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();

                // Load the main model
                if (!$model->load($post)) {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Attach translations
                foreach (Yii::$app->request->post('Lang', []) as $language => $data) {
                    foreach ($data as $attribute => $translation) {
                        $model->translate($language)->$attribute = $translation;
                    }
                }

                // Save the model
                if (!$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }

                // Upload and attach images
                $model->uploadImage();
                
                $transaction->commit();
                
                // Set flash message
                Yii::$app->getSession()->setFlash('partial', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));
              
                // Take appropriate action based on the pushed button
                if (isset($post['close'])) {
                    return $this->redirect(['index']);
                } elseif (isset($post['new'])) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }    
            }    
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing PagePartial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);

                // Get the translation models
                $translationModels = ArrayHelper::index($model->getTranslations()->all(), 'language');

                // Populate the translation models
                Model::loadMultiple($translationModels, $post);

                // Validate the model and translation models
                $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));

                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();

                // Load the model
                if (!$model->load($post)) {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Attach translations
                foreach (Yii::$app->request->post('Lang', []) as $language => $data) {
                    foreach ($data as $attribute => $translation) {
                        $model->translate($language)->$attribute = $translation;
                    }
                }

                // Save the model
                if (!$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                // Upload and attach images
                $model->uploadImage();

                $transaction->commit();

                // Set flash message
                Yii::$app->getSession()->setFlash('partial', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));
              
                // Take appropriate action based on the pushed button
                if (isset($post['close'])) {
                    return $this->redirect(['index']);
                } elseif (isset($post['new'])) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }    
            }    
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing PagePartial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        try {                    
            // Only Superadmin can delete system partials
            if ($model->type == PagePartial::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin'))
                throw new \yii\base\Exception(Yii::t('app', 'You do not have the right permissions to delete this item'));
            
            $model->delete();
        } catch (\yii\base\Exception $e) {
            // Set flash message
            Yii::$app->getSession()->setFlash('partial-error', $e->getMessage());
    
            return $this->redirect(['index']);        
        } 
        
        // Set flash message
        $model->language = Yii::$app->language;
        Yii::$app->getSession()->setFlash('partial', Yii::t('app', '"{item}" has been deleted', ['item' => $model->name]));

        return $this->redirect(['index']);
    }

    /**
     * Finds the PagePartial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PagePartial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PagePartial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }
}
