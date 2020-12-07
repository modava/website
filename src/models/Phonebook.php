<?php

namespace modava\website\models;

use common\models\User;
use modava\website\models\table\PhonebookTable;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "website_phonebook".
*
    * @property int $id
    * @property string $title
    * @property string $phone
    * @property int $status
    * @property string $description
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by
    * @property int $updated_by
    *
            * @property User $createdBy
            * @property User $updatedBy
    */
class Phonebook extends PhonebookTable
{
    public $toastr_key = 'phonebook';
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                [
                    'class' => BlameableBehavior::class,
                    'createdByAttribute' => 'created_by',
                    'updatedByAttribute' => 'updated_by',
                ],
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'preserveNonEmptyValues' => false,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                    ],
                ],
            ]
        );
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
			[['title', 'phone',], 'required'],
			[['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
			[['description'], 'string'],
			[['title'], 'string', 'max' => 255],
			[['phone'], 'string', 'max' => 20],
			[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
			[['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
		];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Tiêu đề'),
            'phone' => Yii::t('backend', 'Số điện thoại'),
            'status' => Yii::t('backend', 'Tình trạng'),
            'description' => Yii::t('backend', 'Mô tả'),
            'created_at' => Yii::t('backend', 'Ngày tạo'),
            'updated_at' => Yii::t('backend', 'Ngày sửa cuối'),
            'created_by' => Yii::t('backend', 'Tạo bởi'),
            'updated_by' => Yii::t('backend', 'Người cập nhật cuối'),
        ];
    }

    /**
    * Gets query for [[User]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getUserCreated()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
    * Gets query for [[User]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getUserUpdated()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
