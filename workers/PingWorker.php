<?php


namespace app\workers;


use app\common\BaseWorker;
use yii\queue\Queue;

class PingWorker extends BaseWorker
{

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        //check SiteController -> index on how to use
        //to use

        //$this->task is available here

        //add code here

    }
}
