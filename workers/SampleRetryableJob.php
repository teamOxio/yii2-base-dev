<?php


namespace app\workers;


use app\common\RetryableWorker;

class SampleRetryableJob extends RetryableWorker
{
    //you can change the following parameters

    //public $retry_delay; // in seconds
//    public $retry_count; // max tries, default 5
    public $task;


    protected function run()
    {
        //check SiteController -> index on how to use
        //to use

        //$this->task is available here
        //$this->queue is available here

        //set $this->exit_response to any value before returning
        //with a EXIT_STATUS. This will be saved to response column of the $task object.

        //if you want to retry
        //return self::EXIT_STATUS_RETRY

        //if you do not want to retry
        //return self::EXIT_STATUS_RETRY

        //if success
        //return self::EXIT_STATUS_OK


    }
}
