<?php

namespace app\controllers;

use app\models\Attachment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;

/**
 * AttachmentController implements the CRUD actions for Attachment model.
 */
class AttachmentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Attachment models.
     *
     * @return string
     */

    public function actionIndex()
    {
        $files = Attachment::find()->all();
        return $this->render('index', [
            'files' => $files,
        ]);
    }

    /**
     * Displays a single Attachment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Attachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Attachment();

        if ($model->load(Yii::$app->request->post())) {

            $model->filename = UploadedFile::getInstance($model, 'filename');
            if ($model->filename) {
                $file = $model->filename->name;
                if ($model->filename->saveAs(Yii::getAlias('@app') . '/web/files/' . $file)) {
                    $model->filename = $file;
                }
            }
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionFilesUpload()
    {
        $model = new Attachment();

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $files = UploadedFile::getInstances($model, 'filename');
            $response = true;

            if (!empty($files) && !empty($postData['entity_id']) && !empty($postData['entity_type'])) {
                switch ($postData['entity_type']) {
                    case Attachment::INVOICE:
                        $root = Yii::getAlias('@app') . '/web/files/' . $postData['entity_id'];
                        break;
                    case Attachment::PAYMENT:
                        $root = Yii::getAlias('@app') . '/web/payments/' . $postData['entity_id'];
                        break;
                    case Attachment::PRODUCT:
                        $root = Yii::getAlias('@app') . '/web/products/' . $postData['entity_id'];
                        break;
                    case Attachment::CUSTOMER:
                        $root = Yii::getAlias('@app') . '/web/customers/' . $postData['entity_id'];
                        break;
                }

                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }

                foreach ($files as $oneFile) {
                    $fileName = $oneFile->baseName . '.' . $oneFile->extension;
                    $oneFile->saveAs($root . '/' . $fileName);

                    $model->entity_id = $postData['entity_id'];
                    $model->entity_type = $postData['entity_type'];
                    $model->filename = $fileName;
                    $model->save();
                }
            } else {
                return json_encode(false);
            }


            return json_encode($response);
        }
    }

    public function actionDeleteUpload()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $keys = Yii::$app->request->post('key');
        $key = explode(' ', $keys);

        $model = Attachment::find()->where([
            'id' => $key[1],
        ])->one();

        if ($key[0] == 'filename') {
            @unlink(Yii::getAlias('@app') . '/web/files/' . $model->filename);
            $model->filename = NULL;
            $model->delete();
        }

        return [];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_file = $model->filename;

        if ($model->load(Yii::$app->request->post())) {

            echo '<pre>';
            print_r(Yii::$app->request->post());
            die;

            $model->filename = UploadedFile::getInstance($model, 'filename');
            if ($model->filename) {
                $file = $model->filename->name;
                if ($model->filename->saveAs(Yii::getAlias('@app') . '/web/files/' . $file)) {
                    $model->filename = $file;
                }
            }
            if (empty($model->filename)) {
                $model->filename = $old_file;
            }

            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        @unlink(Yii::getAlias('@app') . '/web/files/' . $model->filename);
        $model->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Attachment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
