<?php

namespace infoweb\partials\controllers;

use Yii;
use infoweb\partials\models\PagePartial;
use infoweb\partials\models\PagePartialLang;
use infoweb\partials\models\PagePartialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

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
        $searchModel = new PagePartialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PagePartial model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PagePartial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PagePartial();
        
        // Load all the translations
        $model->loadTranslations(array_keys(Yii::$app->params['languages']));
        
        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);
                
                // Populate the translation model for the primary language
                $translationModel = new PagePartialLang([
                    'language'  => Yii::$app->language,              
                    'name'      => $post['PagePartialLang'][Yii::$app->language]['name'],
                    'content'   => $post['PagePartialLang'][Yii::$app->language]['content'],
                ]);
                
                // Validate the translation model
                $translationValidation = ActiveForm::validate($translationModel);
                $correctedTranslationValidation = [];
                
                // Correct the keys of the validation
                foreach($translationValidation as $k => $v) {
                    $correctedTranslationValidation[str_replace('pagepartiallang-', "pagepartiallang-{$translationModel->language}-", $k)] = $v;    
                }

                // Validate the model and primary translation model
                $response = array_merge(ActiveForm::validate($model), $correctedTranslationValidation);
                
                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();                
                
                // Save the main model
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('create', [
                        'model' => $model
                    ]);
                } 
                
                // Save the translation models
                foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
                    $model->language = $languageId;
                    $model->name = $post['PagePartialLang'][$languageId]['name'];
                    $model->content = $post['PagePartialLang'][$languageId]['content'];
                    
                    if (!$model->saveTranslation()) {
                        return $this->render('create', [
                            'model' => $model
                        ]);    
                    }                      
                }
                
                $transaction->commit();
                
                // Set flash message
                $model->language = Yii::$app->language;
                Yii::$app->getSession()->setFlash('partial', Yii::t('app', '{item} has been created', ['item' => $model->name]));
              
                return $this->redirect(['index']);    
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
        
        // Load all the translations
        $model->loadTranslations(array_keys(Yii::$app->params['languages']));
        
        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);
                
                // Populate the translation model for the primary language
                $translationModel = $model->getTranslation(Yii::$app->language);
                $translationModel->name = $post['PagePartialLang'][Yii::$app->language]['name'];
                $translationModel->content = $post['PagePartialLang'][Yii::$app->language]['content'];
                
                // Validate the translation model
                $translationValidation = ActiveForm::validate($translationModel);
                $correctedTranslationValidation = [];
                
                // Correct the keys of the validation
                foreach($translationValidation as $k => $v) {
                    $correctedTranslationValidation[str_replace('pagepartiallang-', "pagepartiallang-{$translationModel->language}-", $k)] = $v;    
                }

                // Validate the model and primary translation model
                $response = array_merge(ActiveForm::validate($model), $correctedTranslationValidation);
                
                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();                
                
                // Save the main model
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('update', [
                        'model' => $model
                    ]);
                } 
                
                // Save the translation models
                foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
                    $model->language = $languageId;
                    $model->name = $post['PagePartialLang'][$languageId]['name'];
                    $model->content = $post['PagePartialLang'][$languageId]['content'];
                    
                    if (!$model->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model
                        ]);    
                    }                      
                }
                
                $transaction->commit();
                
                // Set flash message
                $model->language = Yii::$app->language;
                Yii::$app->getSession()->setFlash('partial', Yii::t('app', '{item} has been updated', ['item' => $model->name]));
              
                return $this->redirect(['index']);    
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
        $model->delete();
        
        // Set flash message
        $model->language = Yii::$app->language;
        Yii::$app->getSession()->setFlash('partial', Yii::t('app', '{item} has been deleted', ['item' => $model->name]));

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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
