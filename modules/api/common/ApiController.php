<?php

namespace app\modules\api\common;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiController extends ActiveController
{
    public function beforeAction($action)
    {
         parent::beforeAction($action);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return true;
    }

    public $allowedActions = ['index','view','create','update','delete'];
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],

        ];

        foreach($actions as $action=>$data){
            if(!in_array($action,$this->allowedActions))
                unset($actions[$action]);
        }


        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
        ];

        return $actions;
    }
}
