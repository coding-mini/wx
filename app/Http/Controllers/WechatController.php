<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('I am in serve');
        $wechat = app('wechat.official_account');

        $wechat->server->push(function($message){
            return '';
        });

        return $wechat->server->serve();
    }
}
