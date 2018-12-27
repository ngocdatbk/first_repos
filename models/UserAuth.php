<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_auth".
 *
 * @property string $user_id
 * @property string $auth_key
 * @property string $password_hash
 */
class UserAuth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'auth_key', 'password_hash'], 'required'],
            [['user_id'], 'integer'],
            [['auth_key'], 'string', 'max' => 32],
            [['access_token'], 'string', 'max' => 32],
            [['password_hash'], 'string', 'max' => 60],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
        ];
    }
}
