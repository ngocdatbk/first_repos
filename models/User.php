<?php

namespace app\models;

use Yii;
use app\models\UserAuth;
use yii\base\NotSupportedException;

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
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface, \yii\filters\RateLimitInterface
{
    const  RATELIMIT = 5;
    const TIME_PERIOD = 5;
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
            [['is_active', 'is_admin', 'last_login', 'allowance', 'allowance_updated_at'], 'integer'],
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

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $loggedUserId = Yii::$app->session->get('loggedUserId');

        if ($loggedUserId && $viewAsData = Yii::$app->dataRegistry->get('viewAsUser')) {
            if (isset($viewAsData[$loggedUserId]['viewAsUserId']) && $id == $loggedUserId) {
                $id = $viewAsData[$loggedUserId]['viewAsUserId'];
            }
        }

        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $userAuth = UserAuth::find()->where(['access_token' => $token])->one();
        if ($userAuth) {
            return static::findIdentity($userAuth->user_id);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        $userAuth = $this->_getUserAuth();

        if ($userAuth === null) {
            throw new NotSupportedException('"getAuthKey" is not implemented.');
        }

        return $userAuth->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    private function _getUserAuth()
    {
        if ($this->_userAuth === false) {
            $this->_userAuth = UserAuth::findOne(['user_id' => $this->user_id]);
        }

        return $this->_userAuth;
    }


    public function getRateLimit($request, $action)
    {
        return [static::RATELIMIT, static::TIME_PERIOD]; // $rateLimit requests per second
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }
}
