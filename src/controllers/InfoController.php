<?php

namespace modava\website\controllers;

use modava\website\components\MyUpload;
use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use backend\components\MyController;
use modava\website\models\WebsiteInfo;

/**
 * InfoController implements the CRUD actions for WebsiteInfo model.
 */
class InfoController extends MyController
{
    /**
     * Updates an existing WebsiteInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $oldLogo = $model->getOldAttribute('logo');
                if ($model->save()) {
                    if ($model->getAttribute('logo') !== $oldLogo) {
                        if ($model->getAttribute('logo') != '') {
                            $pathImage = FRONTEND_HOST_INFO . $model->logo;
                            $path = $this->getUploadDir() . '/web/uploads/info/';
                            $logoName = null;
                            foreach (Yii::$app->params['info'] as $key => $value) {
                                $pathSave = $path . $key;
                                if (!file_exists($pathSave) && !is_dir($pathSave)) {
                                    mkdir($pathSave);
                                }
                                $resultName = MyUpload::uploadFromOnline($value['width'], $value['height'], $pathImage, $pathSave . '/', $logoName);
                                if ($logoName == null) {
                                    $logoName = $resultName;
                                }
                            }

                            $model->logo = $logoName;
                            if ($model->updateAttributes(['logo'])) {
                                foreach (Yii::$app->params['info'] as $key => $value) {
                                    $pathSave = $path . $key;
                                    if (file_exists($pathSave . '/' . $oldLogo) && !is_dir($pathSave . '/' . $oldLogo) && $oldLogo != null) {
                                        @unlink($pathSave . '/' . $oldLogo);
                                    }

                                }
                            }
                        }
                    }
                    Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-form', [
                        'title' => 'Thông báo',
                        'text' => 'Cập nhật thành công',
                        'type' => 'success'
                    ]);
                    return $this->refresh();
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

    protected function getUploadDir()
    {
        $uploadDir = \Yii::$app->getModule('website')->upload_dir;
        return \Yii::getAlias($uploadDir);
    }

    /**
     * Finds the WebsiteInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebsiteInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected function findModel()
    {
        if (($model = WebsiteInfo::find()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        // throw new NotFoundHttpException(BackendModule::t('backend','The requested page does not exist.'));
    }
}
