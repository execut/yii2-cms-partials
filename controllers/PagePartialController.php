<?php

namespace infoweb\partials\controllers;

use Yii;
use infoweb\partials\models\PagePartial;
use infoweb\partials\models\Lang;
use infoweb\partials\models\Search;
use yii\base\Exception;
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
                'class'   => VerbFilter::className(),
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
            'searchModel'  => $searchModel,
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
        // Create the model with default values
        $model = new PagePartial(['type' => 'user-defined']);

        // The view params
        $params = $this->getDefaultViewParams($model);

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                return $this->validateModel($model, $post);

                // Normal request, save models
            } else {
                return $this->saveModel($model, $post);
            }
        }

        return $this->render('create', $params);
    }

    /**
     * Updates an existing PagePartial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Load the model
        $model = $this->findModel($id);

        // The view params
        $params = $this->getDefaultViewParams($model);

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                return $this->validateModel($model, $post);

                // Normal request, save models
            } else {
                return $this->saveModel($model, $post);
            }
        }

        return $this->render('update', $params);
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
                throw new Exception(Yii::t('app', 'You do not have the right permissions to delete this item'));

            $transaction = Yii::$app->db->beginTransaction();

            if (!$model->delete()) {
                throw new Exception(Yii::t('app', 'Error while deleting the node'));
            }

            $transaction->commit();

        } catch (Exception $e) {
            // Set flash message
            Yii::$app->getSession()->setFlash('partial-error', $e->getMessage());

            return $this->redirect(['index']);
        }

        // Set flash message
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

    /**
     * Returns an array of the default params that are passed to a view
     *
     * @param PagePartial $model The model that has to be passed to the view
     * @return array
     */
    protected function getDefaultViewParams($model = null)
    {
        return [
            'model'                   => $model,
            'allowContentDuplication' => $this->module->allowContentDuplication,
        ];
    }

    /**
     * Performs validation on the provided model and $_POST data
     *
     * @param \infoweb\pages\models\Page $model The page model
     * @param array $post The $_POST data
     * @return array
     */
    protected function validateModel($model, $post)
    {
        $languages = Yii::$app->params['languages'];

        // Populate the model with the POST data
        $model->load($post);

        // Create an array of translation models and populate them
        $translationModels = [];
        // Insert
        if ($model->isNewRecord) {
            foreach ($languages as $languageId => $languageName) {
                $translationModels[$languageId] = new Lang(['language' => $languageId]);
            }
            // Update
        } else {
            $translationModels = ArrayHelper::index($model->getTranslations()->all(), 'language');
        }
        Model::loadMultiple($translationModels, $post);

        // Validate the model and translation
        $response = array_merge(
            ActiveForm::validate($model),
            ActiveForm::validateMultiple($translationModels)
        );

        // Return validation in JSON format
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $response;
    }

    protected function saveModel($model, $post)
    {
        // Wrap everything in a database transaction
        $transaction = Yii::$app->db->beginTransaction();

        // Get the params
        $params = $this->getDefaultViewParams();

        // Validate the main model
        if (!$model->load($post)) {
            return $this->render($this->action, $params);
        }

        // Add the translations
        foreach (Yii::$app->request->post('Lang', []) as $language => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($language)->$attribute = $translation;
            }
        }

        // Save the main model
        if (!$model->save()) {
            return $this->render($this->action, $params);
        }

        $model->uploadImage();

        $transaction->commit();

        // Set flash message
        if ($this->action->id == 'create') {
            Yii::$app->getSession()->setFlash('partial', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));
        } else {
            Yii::$app->getSession()->setFlash('partial', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));
        }

        // Take appropriate action based on the pushed button
        if (isset($post['save-close'])) {
            return $this->redirect(['index']);
        } elseif (isset($post['save-add'])) {
            return $this->redirect(['create']);
        } else {
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }
}
