<?php
/** @var \app\models\activerecord\Users $identity */
/** @var array $two_fa */
/** @var \yii\web\View $this */

use app\common\Constants;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = "Two FA";
?>
<div class="row">
    <div class="col-lg-5">
<?php
$form = ActiveForm::begin([
    'id' => 'two-fa-form',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]);
if($identity->is_two_fa === Constants::YES_FLAG){

    echo Html::hiddenInput('cmd',Constants::CMD_DISABLE_TWO_FA);

    echo $this->render('//layouts/_two_fa_form');

    echo Html::submitButton('Disable 2FA',['class'=>'btn btn-warning']);



}
else{

    echo Html::hiddenInput('cmd',Constants::CMD_ENABLE_TWO_FA);

    echo Html::img($two_fa['uri'],['style'=>'width:250px;']);

    echo '<p class="text-info">Secret: '.$two_fa['secret'].'</p>';

    echo $this->render('//layouts/_two_fa_form');
    ?>
    <div class="form-group">
        <?=  Html::submitButton('Enable 2FA',['class'=>'btn btn-success']);?>
    </div>
<?php
}

ActiveForm::end();

?>
    </div>
</div>

