<?php

namespace app\modules\api\common;

use app\common\Constants;
use app\modules\api\common\CorsCustom;
use app\common\Helper;
use app\models\activerecord\Users;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\Model;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class BaseActiveController extends ActiveController
{
    public $allowedActions = ['index','view','create','update','delete'];
    public $is_protected = true;

    public $is_serializable = false;

    /** @var Users $_identity */
    public $_identity = null;

    public $only = [];
    public $except = [];

    public $should_pagination = true;

    public $allowed_user_roles = [Constants::USER_ROLE_ADMIN];

    public $searchModel = null;

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

        if($this->searchModel){
            $actions['index']['prepareDataProvider'] = function () {
                $searchModel = new $this->searchModel;
                return $searchModel->search(\Yii::$app->request->queryParams);
            };

        }

        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
        ];

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        Helper::allowCorsPreflight();

        if($this->is_serializable){
            $this->serializer = [
                'class' => 'yii\rest\Serializer',
                'collectionEnvelope' => 'items',
            ];
        }

        parent::beforeAction($action);

        $this->_identity = Yii::$app->user->identity;

        Yii::$app->response->format = Response::FORMAT_JSON;

        return true;
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['corsFilter']);
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] =  [
            'class' => CorsCustom::class,
            'cors'  => [
                // restrict access to domains:
                'Origin' => Constants::CORS_ALLOWED_DOMAINS,
                'Access-Control-Request-Method'    => ['POST','GET','OPTIONS','PUT','DELETE'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age'           => 3600,
                'Access-Control-Allow-Headers' => Constants::CORS_ALLOWED_HEADERS,
            ],
        ];

        if($this->is_protected) {
            $behaviors['authenticator'] = [
                'class' => JwtHttpBearerAuth::class,
                'only'=>$this->only,
                'except'=>$this->except
            ];
            array_push($behaviors['authenticator']['except'],'options');
        }

        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if($this->is_protected) {
            if (!in_array($action, $this->except)){

                if (!$this->_identity
                    ||
                    !in_array($this->_identity->role_id, $this->allowed_user_roles))

                    throw new ForbiddenHttpException('You are not allowed to access this.');
            }
        }

        parent::checkAccess($action, $model, $params);
    }

    public function success($message = 'Request successful', $data = []){
        return $this->asJson(['status'=>'success',
            'message'=>$message,
            'data'=>$data]);
    }

    public function error($message = 'Invalid Request', $data = []){

        if($data instanceof Model){
            $data = $data->getErrors();
        }

        return $this->asJson(['status'=>'error',
            'message'=>$message,
            'data'=>$data]);
    }

    /**
     * @param $identifier
     * @return Users|null
     */
    public function findModelByIdentifier($identifier){
        return $this->modelClass::findByIdentifier($identifier);
    }

}
