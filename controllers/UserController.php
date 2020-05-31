<?php


namespace app\controllers;


use app\common\Constants;
use app\common\ProtectedController;
use Yii;

class UserController extends ProtectedController
{
    //Uncomment following and add action id to allow 2fa check on it
    /*
     * public $two_fa_actions = ['two-fa'];
     */

    public function actionIndex(){
        $identity = $this->identity;

        return $this->render("index",compact('identity'));
    }

    public function actionTwoFa(){

        $identity = $this->identity;

        if(Yii::$app->request->post('cmd')){

           $result = false;

           switch(Yii::$app->request->post('cmd')){

               case Constants::CMD_DISABLE_TWO_FA:
                    $result = $identity->disable2FA();
                   break;

               case Constants::CMD_ENABLE_TWO_FA:
                   $result = $identity->enable2FA();
                   break;
           }

           if($result === true)
               Yii::$app->session->setFlash('success','Request successful');
           else
               Yii::$app->session->setFlash('error','Request failed');

        }

        $two_fa = false;
        //generate a random Two FA Key if not enabled
        if($identity->is_two_fa == Constants::NO_FLAG){
            $two_fa = $identity->init2FA(true);
        }

        return $this->render('two_fa',compact('identity','two_fa'));
    }
}