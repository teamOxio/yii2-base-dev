<?php


namespace app\common;


use app\models\activerecord\Logs;
use Yii;

class SystemLog
{
    public static function log($user_id,
                $particulars,
               $type,
               $data=null,
                $parent_id = null
        ){
        $log = new Logs();
        if($parent_id){
            $log = Logs::findOne($parent_id);
            $log->particulars .= $particulars;
        }
        else{
            $log->particulars = $particulars;
        }

        $log->user_id = $user_id;
        $log->type = $type;
        $log->data = $data;

        if($log->save())
            return $log->id;
        else {

            return false;
        }
    }
}
