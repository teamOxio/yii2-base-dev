<?php


namespace app\common;

use Yii;

class ProtectedController extends BaseController
{
    public $two_fa_actions = ['two-fa'];

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest)
            return $this->redirectTo(['site/login']);

        parent::beforeAction($action);

        if(
            Yii::$app->getRequest()->getIsPost()
            &&
            (
                Yii::$app->request->post('two_fa_code')
                || $this->identity->is_two_fa == Constants::YES_FLAG
            )
            &&
            in_array($action->id, $this->two_fa_actions)
        ){
            //check 2fa code

            $result = $this->identity->verify2FA(Yii::$app->request->post('two_fa_code'),
                (Yii::$app->session->has('two_fa_secret') ?
                    Yii::$app->session->get('two_fa_secret') : false
                ));



            if(!$result){
                Yii::$app->session->setFlash('error','Invalid 2FA code');
                return $this->goBack()->send();
            }
        }

        return true;
    }

}
