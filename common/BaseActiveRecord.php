<?php


namespace app\common;


use app\common\exceptions\PersistException;
use Yii;
use yii\db\ActiveRecord;

abstract class BaseActiveRecord extends ActiveRecord
{
    public function beforeValidate()
    {
        if($this->isNewRecord)
        {
            if ($this->hasAttribute('identifier'))
            {
                $this->identifier = $this->generateIdentifier();
            }

            $country = null;

            if($this->hasAttribute('ip'))
            {
                if(Yii::$app->request->isConsoleRequest)
                {
                    $this->setAttribute('ip', "::1");
                }
                else
                {
                    $this->setAttribute('ip', Yii::$app->request->getUserIP());
                    $country = Helper::getCountryIDFromIP($this->getAttribute('ip'));
                }

            }

            if($this->hasAttribute('useragent'))
            {
                if(Yii::$app->request->isConsoleRequest)
                {
                    $this->setAttribute('useragent', 'console');
                }
                else
                {
                    $this->setAttribute('useragent', Yii::$app->request->getUserAgent());
                }
            }

            if($this->hasAttribute('ip_country_id'))
            {
                $this->setAttribute('ip_country_id', $country);
            }

            if($this->hasAttribute('time'))
            {
                if($this->getAttribute('time')=="" || $this->getAttribute('time')==null)
                {
                    $this->setAttribute('time', date(Constants::PHP_DATE_FORMAT));
                }
            }
        }
        if($this->hasAttribute('updated_on'))
        {
            $this->setAttribute('updated_on', date(Constants::PHP_DATE_FORMAT));
        }
        return parent::beforeValidate();
    }

    public function parseFunctions($functions,$value='')
    {
        if(is_array($functions) && count($functions)>0)
        {
            foreach ($functions as $func)
            {
                if(is_array($func)) {
                    foreach ($func as $key=>$val)
                    {
                        if($val=="{{val}}")
                        {
                            $func[$key]=$value;
                        }
                    }
                    $value = call_user_func($func[0],
                        ...array_slice($func, 1, count($func) - 1)
                    );
                }
                else
                    $value = call_user_func($func, $value);
            }
        }
        else
            $value = call_user_func($functions, $value);
        return $value;
    }
    public function getCurrentTable($functions=null)
    {
        $table = self::tableName();
        $table = str_replace('{{%','',$table);
        $table = str_replace('}}','',$table);
        if($functions!=null)
        {
            $table=$this->parseFunctions($functions,$table);
        }
        return $table;
    }

    public function getEncryptionSalt($attribute=null)
    {
        $salt=$this->getCurrentTable([
            ['strtolower','{{val}}'],
            ['str_replace','_','','{{val}}'],
        ]);
        if($attribute!=null)
        {
            if($this->hasAttribute($attribute))
            {
                $salt=$this->{$attribute};
            }
            else
            {
                $salt=null;
            }
        }
        if($salt=="" || $salt==null)
        {
            throw new \Exception('Salt value not found');
        }
        return $salt;
    }

    /**
     * @param $data
     * @param string $attribute
     * @return false|string
     */
    public function encryptModelData($data,$attribute=null)
    {
        $salt=$this->getEncryptionSalt($attribute);
        return utf8_encode(Yii::$app->security->encryptByKey($data,hash("sha256",$salt)));
    }

    /**
     * @param $data
     * @param string $attribute
     * @return bool|string
     */
    public function decryptModelData($data,$attribute=null)
    {
        $salt=$this->getEncryptionSalt($attribute);
        return Yii::$app->security->decryptByKey(utf8_decode($data),hash("sha256",$salt));
    }

    public function generateIdentifier(){
        $prefix = 'SYS'; //system prefix

        $table = self::tableName();
        $table = str_replace('{{%','',$table);
        $table = str_replace('}}','',$table);

        $exploded = explode('_',$table);

        if(is_array($exploded) && count($exploded)>0){
            $prefix = "";
            foreach ($exploded as $word){
                $prefix .= substr($word,0,1);
            }
            $prefix = strtoupper($prefix);
        }

        $identifier = Helper::generateRandomKey($prefix);
        $identifier = str_replace(array('&','?','#','@'),'',$identifier);

        //check if unique
        $user = self::find()->where(['identifier'=>$identifier])->one();
        if($user != null){
            return $this->generateIdentifier();
        }
        return $identifier;
    }

    public static function get($id){
        $instance = self::findOne($id);

        return $instance;
    }

    public static function create($config){
        $class = get_called_class();
        $instance = new $class($config);

        if($instance->save())
            return $instance;
        else {
            throw new PersistException($instance);

        }

    }


}
