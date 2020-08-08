<?php

namespace modava\website\controllers;

use modava\website\components\MyUpload;
use yii\db\Exception;
use Yii;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use modava\website\WebsiteModule;
use modava\website\components\MyWebsiteController;
use modava\website\models\WebsitePartner;
use modava\website\models\search\PartnerSearch;

/**
 * PartnerController implements the CRUD actions for WebsitePartner model.
 */
class PartnerController extends MyWebsiteController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all WebsitePartner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single WebsitePartner model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WebsitePartner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebsitePartner();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    $imageName = null;
                    if ($model->image != "") {
                        $pathImage = FRONTEND_HOST_INFO . $model->image;
                        $path = Yii::getAlias('@frontend/web/uploads/partner/');
                        foreach (Yii::$app->params['partner'] as $key => $value) {
                            $pathSave = $path . $key;
                            if (!file_exists($pathSave) && !is_dir($pathSave)) {
                                mkdir($pathSave);
                            }
                            $imageName = MyUpload::uploadFromOnline($value['width'], $value['height'], $pathImage, $pathSave . '/', $imageName);
                        }

                    }
                    $model->image = $imageName;
                    $model->updateAttributes(['image']);
                    Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-view', [
                        'title' => 'Thông báo',
                        'text' => 'Tạo mới thành công',
                        'type' => 'success'
                    ]);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $errors = Html::tag('p', 'Tạo mới thất bại');
                foreach ($model->getErrors() as $error) {
                    $errors .= Html::tag('p', $error[0]);
                }
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-form', [
                    'title' => 'Thông báo',
                    'text' => $errors,
                    'type' => 'warning'
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WebsitePartner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $oldImage = $model->getOldAttribute('image');
                if ($model->save()) {
                    if ($model->getAttribute('image') !== $oldImage) {
                        if ($model->getAttribute('image') != '') {
                            $pathImage = FRONTEND_HOST_INFO . $model->image;
                            $path = Yii::getAlias('@frontend/web/uploads/partner/');
                            $imageName = null;
                            foreach (Yii::$app->params['partner'] as $key => $value) {
                                $pathSave = $path . $key;
                                if (!file_exists($pathSave) && !is_dir($pathSave)) {
                                    mkdir($pathSave);
                                }
                                $resultName = MyUpload::uploadFromOnline($value['width'], $value['height'], $pathImage, $pathSave . '/', $imageName);
                                if ($imageName == null) {
                                    $imageName = $resultName;
                                }
                            }

                            $model->image = $imageName;
                            if ($model->updateAttributes(['image'])) {
                                foreach (Yii::$app->params['partner'] as $key => $value) {
                                    $pathSave = $path . $key;
                                    if (file_exists($pathSave . '/' . $oldImage) && $oldImage != null) {
                                        unlink($pathSave . '/' . $oldImage);
                                    }

                                }
                            }
                        }
                    }
                    Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-view', [
                        'title' => 'Thông báo',
                        'text' => 'Cập nhật thành công',
                        'type' => 'success'
                    ]);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $errors = Html::tag('p', 'Cập nhật thất bại');
                foreach ($model->getErrors() as $error) {
                    $errors .= Html::tag('p', $error[0]);
                }
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-form', [
                    'title' => 'Thông báo',
                    'text' => $errors,
                    'type' => 'warning'
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WebsitePartner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            if ($model->delete()) {
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-index', [
                    'title' => 'Thông báo',
                    'text' => 'Xoá thành công',
                    'type' => 'success'
                ]);
            } else {
                $errors = Html::tag('p', 'Xoá thất bại');
                foreach ($model->getErrors() as $error) {
                    $errors .= Html::tag('p', $error[0]);
                }
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-index', [
                    'title' => 'Thông báo',
                    'text' => $errors,
                    'type' => 'warning'
                ]);
            }
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-index', [
                'title' => 'Thông báo',
                'text' => Html::tag('p', 'Xoá thất bại: ' . $ex->getMessage()),
                'type' => 'warning'
            ]);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the WebsitePartner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebsitePartner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected function findModel($id)
    {
        if (($model = WebsitePartner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('website', 'The requested page does not exist.'));
    }
}
