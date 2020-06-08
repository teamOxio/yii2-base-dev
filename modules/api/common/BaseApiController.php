<?php


namespace app\modules\api\common;

use app\common\Constants;
use app\modules\api\common\CorsCustom;
use app\common\Helper;
use app\models\activerecord\Users;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\Model;
use yii\rest\Controller;
use yii\web\Response;

class BaseApiController extends Controller
{
    public $is_protected = true;

    /** @var Users $_identity */
    public $_identity = null;

    public $only = null;
    public $except = [];

    public function beforeAction($action)
    {
        Helper::allowCorsPreflight();

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

        $behaviors['corsFilter'] =  [
            'class' => CorsCustom::class,
            'cors'  => [
                // restrict access to domains:
                'Origin' => Constants::CORS_ALLOWED_DOMAINS,
                'Access-Control-Request-Method'    => ['POST','GET','OPTIONS','PUT','DELETE'],
                'Access-Control-Allow-Headers' => Constants::CORS_ALLOWED_HEADERS,
//                'Access-Control-Allow-Credentials' => true,
//                'Access-Control-Max-Age'           => 3600,
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

}
