<?php


namespace app\modules\api\controllers;


use app\modules\api\common\BaseActiveController;

class CountriesController extends BaseActiveController
{
    public $modelClass = 'app\models\activerecord\Countries';

    public $allowedActions = ['index','view'];
}
