<?php
/**
 * Created by PhpStorm.
 * User: ngocd
 * Date: 12/25/18
 * Time: 10:51
 */
namespace app\modules\v2\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;



class ProductController extends ActiveController
{
    public $modelClass = 'app\modules\v2\models\Product';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $add_behaviors = ['corsFilter' => [
            'class' => \yii\filters\Cors::className(),
        ]];

        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_HTML;
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
            'except' => ['options']
        ];

        return ArrayHelper::merge($add_behaviors, $behaviors);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (
        (($action === 'view' || $action === 'index') && !Yii::$app->user->can('ViewProduct'))
        ||
        ($action === 'create' && !Yii::$app->user->can('CreateProduct'))
        ||
        ($action === 'update' && !Yii::$app->user->can('UpdateProduct'))
        ||
        ($action === 'delete' && !Yii::$app->user->can('DeleteProduct'))
        ) {
            throw new \yii\web\ForbiddenHttpException(sprintf('You have no permission'));
        }
    }
}