<?php


namespace app\modules\api\common;


use app\common\BaseActiveRecord;
use app\models\activerecord\AffiliateManagers;
use app\models\activerecord\Categories;
use app\models\activerecord\Offers;
use app\models\activerecord\Users;
use app\models\base\PostbackMacroModel;
use app\modules\api\entities\ApiAffiliateManagerModel;
use app\modules\api\entities\ApiCategoryModel;
use app\modules\api\entities\ApiOfferModel;
use app\modules\api\entities\ApiPostbackMacroModel;
use app\modules\api\entities\ApiUserModel;

class EntityMapper
{
    public static $entityMap = [
         Users::class => ApiUserModel::class,
    ];

    public static function map($object, $extraParams = []){

        $mapped_object = null;
        /**
         * @var BaseActiveRecord $from
         */
        foreach(self::$entityMap as $from=>$to){

            if($object instanceof $from) {
                $mapped_object = new $to();

                $properties = array_merge($object->getAttributes(),$extraParams);

                foreach ($properties as $property => $value) {
                    if (property_exists($mapped_object, $property))
                        $mapped_object->{$property} = $value;
                }
            }
        }


        return $mapped_object;
    }
}
