<?php
/**
 * Created by PhpStorm.
 * User: ngocd
 * Date: 10/4/18
 * Time: 16:05
 */
namespace app\modules\test\models;

use Yii;
use yii\base\Model;

class TestEvent extends Model
{
    const   EVENT_START = "Test_start";
    const   EVENT_STOP = "Test_stop";

    public function start(){
        $this->trigger($this::EVENT_START);
    }

    public function stop(){
        $this->trigger($this::EVENT_STOP);
    }

    public function processEvent($event){
        echo $event->name;
    }
}