<?php
/**
 * Created by PhpStorm.
 * User: ngocd
 * Date: 12/25/18
 * Time: 10:51
 */
namespace app\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\modules\user\models\User';
}