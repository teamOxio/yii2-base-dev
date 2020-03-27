<?php


namespace app\common;


use app\models\activerecord\Countries;
use app\models\activerecord\Settings;
use app\models\activerecord\Users;
use IP2Location\Database;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

class Helper
{
    const RECAPTCHA_VERIFY_URL = "https://www.google.com/recaptcha/api/siteverify";

    public static function getCountryFromCode($code,$return_model = false){
        $country = Countries::find()->where(['iso'=>$code])->one();
        if($country) {
            if($return_model)
                return $country;
            else
                return $country->id;
        }
        return null;
    }

    public static function generateRandomKey($prefix = "K",$length = 16){
        return $prefix.'.'. Yii::$app->security->generateRandomString($length);
    }

    public static function generateRandomString($length = 32){
        return Yii::$app->security->generateRandomString($length);
    }

    /**
     * @param $prefix
     * @param int $length
     * @param BaseActiveRecord $class
     * @param string $property
     * @return string
     */
    public static function generateUniqueKey($prefix = 'S',  $class, $property = 'identifier',$length = 16){
        $identifier = self::generateRandomKey($prefix,$length);

        $duplicate = $class::find()->where([$property=>$identifier])->one();

        if($duplicate != null)
            return self::generateUniqueKey($prefix,$class,$property,$length);

        return $identifier;
    }

    public static function validateBTCAddress($address){
        $decoded = Helper::decodeBase58($address);
        if($decoded===false)
            return false;

        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true);

        if(substr_compare($decoded, $d2, 21, 4)){
            return false;
        }
        return true;
    }

    public static function decodeBase58($input) {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

        $out = array_fill(0, 25, 0);
        for($i=0;$i<strlen($input);$i++){
            if(($p=strpos($alphabet, $input[$i]))===false){
                return false;
            }
            $c = $p;
            for ($j = 25; $j--; ) {
                $c += (int)(58 * $out[$j]);
                $out[$j] = (int)($c % 256);
                $c /= 256;
                $c = (int)$c;
            }
            if($c != 0){
                return false;
            }
        }

        $result = "";
        foreach($out as $val){
            $result .= chr($val);
        }

        return $result;
    }
    public static function getSetting($id){
        /**
         * @var Settings $setting
         */
        $setting =  Settings::findOne($id);
        return $setting;
    }


    public static function getCountryIDFromIP($ip,$ipv6 = false){
        $country = self::getCountryFromIP($ip,$ipv6);
        if($country == null)
            $country = self::getCountryFromIP($ip,!$ipv6);

        if(array_key_exists('countryCode',$country)){
            return self::getCountryFromCode($country['countryCode']);
        }

        return false;
    }

    public static function getCountryFromIP($ip,$ipv6 = false){
        $db = null;
        if($ipv6)
            try {
                $db = new Database(Yii::getAlias("@app") . '/data/ip2location_ipv6.bin',
                    Database::FILE_IO);
            } catch (\Exception $e) {
            }
        else
            try {
                $db = new Database(Yii::getAlias("@app") . '/data/ip2location.bin',
                    Database::FILE_IO);
            } catch (\Exception $e) {
            }

        if($db)
            return $db->lookup($ip, Database::ALL);
        else
            return false;

    }

    public static function verifyCaptcha($response){
        $captcha_secret = Settings::findOne(Constants::SETTINGS_RECAPTCHA_SECRET);

        $data = [
            'secret'=>$captcha_secret->value,
            'response'=>$response
        ];

        $ch = curl_init(self::RECAPTCHA_VERIFY_URL);
        curl_setopt_array($ch,[
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_POST=>true,
            CURLOPT_POSTFIELDS=>http_build_query($data),
            CURLOPT_TIMEOUT=>5,
        ]);
        $response = curl_exec($ch);

        if($response) {
            $response = json_decode($response);
            if($response){
                if($response->success){
                    return true;
                }
            }
        }

        return false;
    }
    public static function withBaseUrl($path)
    {
        return Url::base(true)."/".$path;
    }
    public static function getWebsiteTitle()
    {
        $pageTitle = Yii::$app->controller->view->title;
        if($pageTitle!=null){
            $pageTitle = $pageTitle." | ".Yii::$app->name;
        }else{
            $pageTitle = Yii::$app->name;
        }
        return Html::encode($pageTitle);
    }

    public static function generateRandomInteger(){
        return rand(1000,9999).rand(5000,9999);
    }

    public static function getCryptoPrice($crypto,$buffer_enabled = true){
        $curl = curl_init("https://api.coincap.io/v2/assets/".$crypto);
        curl_setopt_array($curl,[
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_TIMEOUT=>5
        ]);
        $response = curl_exec($curl);
        if($response){
            $data = json_decode($response);
            $data = $data->data;
            if(isset($data->priceUsd)){
                $buffer = 0;
                if($buffer_enabled === -1) {
                    $buffer = 0;
                }
                else if($buffer_enabled === true) { //dashboard stats
                    //add 2%
                    $buffer = round(($data->priceUsd * (Constants::CRYPTO_BUFFER / 100)), Constants::BTC_PRECISION);
                }
                else{
                    //this is withdrawal request
                    //add 1% or whatever is defined in settings
                    $withdrawal_buffer_percent = Constants::CRYPTO_WITHDRAWAL_BUFFER;

                    //multiplying by -1 to ultimately add it in the crypto price.
                    $buffer = -1 * (round(($data->priceUsd *
                            ($withdrawal_buffer_percent / 100))
                            , Constants::CURRENCY_PRECISION));
                }
                return (round($data->priceUsd,Constants::CURRENCY_PRECISION) - $buffer);
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    /**
     * @param $code
     * @return ActiveRecord
     */
    public static function getSponsor($code){

        $user = Users::find()->where(["referral_code"=>$code])
            ->andWhere(" status_id = :active",
                [':active'=>Constants::USER_STATUS_ACTIVE])
            ->one();
        return $user;
    }




}
