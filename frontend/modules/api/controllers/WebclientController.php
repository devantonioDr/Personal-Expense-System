<?php

namespace frontend\modules\api\controllers;
use yii\rest\ActiveController;


class WebclientController extends ActiveController
{
  public $modelClass = '';

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['corsFilter'] = [
      'class' => \yii\filters\Cors::class,
      'cors' => [
        'Origin' => ['*'],
        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS', 'QUESTION'],
        'Access-Control-Request-Headers' => ['*'],
      ],
    ];

    // $behaviors['authenticator']['only'] = ['index','client'];
    // $behaviors['authenticator']['authMethods']  = [
    //     HttpBearerAuth::class
    // ];

    return $behaviors;
  }
}
