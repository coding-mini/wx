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
            $responseMsg = '';
            switch ($message->MsgType) {
                case 'text':   // 文本消息
                    $responseMsg = '我是个不会聊天的人';
                    break;
                case 'image':
                    $responseMsg = '收到图片消息';
                    break;
                case 'video':
                    $responseMsg = '我非常喜欢做视频';
                    break;
                case 'voice':
                    $responseMsg = '我非常喜欢做音频';
                    break;
                default:
                    $responseMsg = '我是没有个性的默认恢复消息';
                    break;
            }
            return $responseMsg;
        });

        return $wechat->server->serve();
    }
}
