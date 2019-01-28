<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $wechat = app('wechat.official_account');

        $wechat->server->push(function($message){
            return "欢迎关注 Coding10！";
        });

        return $wechat->server->serve();
    }
}
