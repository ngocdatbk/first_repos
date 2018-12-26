<?php

namespace app\models;

use Yii;
use app\models\UserAuth;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property string $username
 * @property string $gender
 * @property string $email
 * @property string $phone_number
 * @property string $fullname
 * @property int $is_active
 * @property int $is_admin
 * @property string $last_login
 */
class User extends \yii\db\ActiveRecord implements Linkable
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'fullname'], 'required'],
            [['gender'], 'string'],
            [['is_active', 'is_admin', 'last_login'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['email', 'fullname'], 'string', 'max' => 100],
            [['phone_number'], 'string', 'max' => 20],
            [['email'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'gender' => 'Gender',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'fullname' => 'Fullname',
            'is_active' => 'Is Active',
            'is_admin' => 'Is Admin',
            'last_login' => 'Last Login',
        ];
    }

    public function getAuth()
    {
        return $this->hasMany(UserAuth::className(), ['user_id' => 'user_id']);
    }

    public function fields()
    {
        return [
            'user_id',
            'username',
            'gender',
            'email',
            'fullname',
            'is_active',
            'is_admin',
        ];
    }

    public function extraFields()
    {
        return [
            'auth',
            'status' => function ($model) {
                return 1;
            },
        ];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->user_id], true),
            'edit' => Url::to(['user/view', 'id' => $this->user_id], true),
            'index' => Url::to(['users'], true),
        ];
    }
}
