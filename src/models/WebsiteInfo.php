<?php

namespace modava\website\models;

use common\models\User;
use modava\website\models\table\WebsiteInfoTable;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "website_info".
*
    * @property int $id
    * @property string $site_name
    * @property array $about
    * @property array $phone
    * @property array $landline
    * @property array $fax
    * @property array $email
    * @property array $address
    * @property string $language
    * @property int $status
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by
    * @property int $updated_by
    *
            * @property User $createdBy
            * @property User $updatedBy
    */
class WebsiteInfo extends WebsiteInfoTable
{
    public $toastr_key = 'website-info';
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
			[['site_name'], 'required'],
			[['phone', 'landline', 'fax', 'email', 'address', 'about'], 'safe'],
			[['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
			[['site_name'], 'string', 'max' => 255],
			[['language'], 'string', 'max' => 25],
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
            'site_name' => Yii::t('backend', 'Site Name'),
            'about' => Yii::t('backend', 'Giới thiệu'),
            'phone' => Yii::t('backend', 'Phone'),
            'landline' => Yii::t('backend', 'Điện thoại bàn'),
            'fax' => Yii::t('backend', 'Fax'),
            'email' => Yii::t('backend', 'Email'),
            'address' => Yii::t('backend', 'Address'),
            'language' => Yii::t('backend', 'Language'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
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
