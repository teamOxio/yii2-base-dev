<?php


namespace app\modules\api\common;


use Yii;
use yii\filters\Cors;

class CorsCustom extends Cors
{
    public function beforeAction($action)
    {
        if (Yii::$app->getRequest()->getMethod() == 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'DELETE POST GET PUT');
            Yii::$app->getResponse()->setStatusCode(200);
            Yii::$app->end();
        }

        parent::beforeAction($action);

        return true;
    }
}
