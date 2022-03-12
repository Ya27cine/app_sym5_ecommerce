<?php  

namespace App\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SecoundTestSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'test3',
            'kernel.response' => 'test4',
            'kernel.finish_request' => 'test4',
        ];
    }


    public function test3(){
        dump("listener  Test 3");
     }

     public function test4(){
        dump("listener  Test 4");
     }
}


?>