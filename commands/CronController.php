<?php


namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command can be used for running schedule tasks from command line using crontab
 *
 *
 * @author Prabhjyot Singh <prabhjyot@teamoxio.com>
 */
class CronController extends Controller
{

    public function actionIndex()
    {
        //accessed using ./yii cron/index
        return ExitCode::OK;
    }
}
