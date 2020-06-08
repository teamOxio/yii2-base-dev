<?php


namespace app\modules\api\controllers;


use app\common\Constants;
use app\models\activerecord\Users;
use app\models\base\LoginForm;
use app\models\base\ResetPasswordForm;
use app\models\base\SignupForm;
use app\modules\api\common\BaseApiController;
use app\modules\api\common\EntityMapper;
use Yii;
use yii\web\UnauthorizedHttpException;

class AuthController extends BaseApiController
{
    public $is_protected = false;

    public function actionLogin(){

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(),'')) {
            if($model->login(true)) {
                /** @var Users $identity */
                $identity = Yii::$app->user->identity;

                return $this->asJson(
                    EntityMapper::map(
                        $identity,
                        [
                            'hash'=>$identity->getSessionHash(),
                            'manager'=> $identity->role_id == Constants::USER_ROLE_ADMIN
                        ]
                    )
                );
            }

        }


        throw new UnauthorizedHttpException();
    }

}
