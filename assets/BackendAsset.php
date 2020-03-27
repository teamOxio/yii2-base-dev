<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
*/
class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot/backend/';
    public $baseUrl = '@web/backend/';
    public $css = [

    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
