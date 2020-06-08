<?php

namespace app\modules\api\entities;

use yii\base\BaseObject;

class ApiUserModel extends BaseObject
{
    public $id;
    public $identifier;
    public $username;
    public $first_name;
    public $last_name;
    public $hash;
    public $email;
    public $country_id;
    public $manager;//isAdmin or not
}
