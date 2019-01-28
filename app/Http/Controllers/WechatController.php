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
        Log::info('I am wechat server');

        $wechat = app('wechat.official_account');

        $wechat->server->push(function($message) use($wechat){

            $user = $wechat->user->get($message->FromUserName);

            $responseMsg = '';

            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case 'subscribe':
                            return '欢迎您关注 Coding10 公众号';

                        case 'CLICK':
                            switch ($message->EventKey) {
                                case 'BUTTON_ABOUT_US':
                                    $user_auth = $wechat->oauth->user();
                                    return $user_auth->nickname.'你点击了关于我们';
                                case 'V1001_GOOD':
                                    return '你点击了赞一下我们';
                            }
                            return '';
                        default:
                            break;
                    }
                    return '';
                case 'text':   // 文本消息
                    $responseMsg = $user->nickname.'我是个不会聊天的人';
                    break;
                case 'image':
                    $responseMsg = $user->nickname.'收到图片消息';
                    break;
                case 'video':
                    $responseMsg = $user->nickname.'我非常喜欢做视频';
                    break;
                case 'voice':
                    $responseMsg = $user->nickname.'我非常喜欢做音频';
                    break;
                default:
                    $responseMsg = $user->nickname.'我是没有个性的默认恢复消息';
                    break;
            }
            return $responseMsg;
        });

        return $wechat->server->serve();
    }
}
