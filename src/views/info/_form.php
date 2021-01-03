<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model modava\website\models\WebsiteInfo */
/* @var $form yii\widgets\ActiveForm */
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="website-info-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-8">
            <?= $form->field($model, 'site_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'language')
                ->dropDownList(Yii::$app->params['availableLocales'], ['prompt' => Yii::t('backend', 'Chọn ngôn ngữ...')])
                ->label(Yii::t('backend', 'Ngôn ngữ')) ?>

        </div>
    </div>

    <div class="row">
        <div class="col-6 col-sm-4 col-md-4">
            <?= $form->field($model, 'phone')->widget(MultipleInput::class, [
                'max' => 6,
                'allowEmptyList' => true,
                'columns' => [
                    [
                        'name' => 'phone',
                        'title' => Yii::t('backend', 'Điện thoại liên hệ'),
                    ]
                ]
            ])->label(false);
            ?>
        </div>
        <div class="col-6 col-sm-4 col-md-4">
            <?= $form->field($model, 'landline')->widget(MultipleInput::class, [
                'max' => 6,
                'allowEmptyList' => true,
                'columns' => [
                    [
                        'name' => 'landline',
                        'title' => Yii::t('backend', 'Điện thoại bàn'),
                    ]
                ]
            ])->label(false);
            ?>
        </div>
        <div class="col-6 col-sm-4 col-md-4">
            <?= $form->field($model, 'fax')->widget(MultipleInput::class, [
                'max' => 6,
                'allowEmptyList' => true,
                'columns' => [
                    [
                        'name' => 'fax',
                        'title' => Yii::t('backend', 'Fax'),
                    ]
                ]
            ])->label(false);
            ?>
        </div>
        <div class="col-6 col-sm-4 col-md-4">
            <?= $form->field($model, 'email')->widget(MultipleInput::class, [
                'max' => 6,
                'allowEmptyList' => true,
                'columns' => [
                    [
                        'name' => 'email',
                        'title' => Yii::t('backend', 'Email'),
                    ]
                ]
            ])->label(false);
            ?>
        </div>
        <div class="col-6 col-sm-4 col-md-4">
            <?= $form->field($model, 'address')->widget(MultipleInput::class, [
                'max' => 6,
                'allowEmptyList' => true,
                'columns' => [
                    [
                        'name' => 'address',
                        'title' => Yii::t('backend', 'Địa chỉ'),
                    ]
                ]
            ])->label(false);
            ?>
        </div>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'about')->widget(MultipleInput::class, [
            'max' => 6,
            'allowEmptyList' => true,
            'columns' => [
                [
                    'name' => 'about',
                    'type' => 'textArea',
                    'title' => Yii::t('backend', 'Giới thiệu'),
                ]
            ]
        ])->label(false);
        ?>
    </div>

    <?php if (Yii::$app->controller->action->id == 'create') $model->status = 1; ?>
    <?= $form->field($model, 'status')->checkbox() ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-sm btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
