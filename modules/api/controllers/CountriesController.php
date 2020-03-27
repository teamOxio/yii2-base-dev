<?php


namespace app\modules\api\controllers;


use app\modules\api\common\ApiController;

class CountriesController extends ApiController
{
    public $modelClass = 'app\models\activerecord\Countries';

    public $allowedActions = ['index','view'];
}
