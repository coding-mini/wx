<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
    private $wechat;
    private $wechat_user;

    /**
     * WechatController constructor.
     * @param $wechat
     */
    public function __construct()
    {
        $this->wechat = app('wechat.official_account');
        $this->wechat_user = $this->wechat->user;
    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $wechat = app('wechat.official_account');

        $wechat->server->push(function($message) use ($wechat){
            $user = $wechat->user->get($message->FromUserName);
            switch ($message->MsgType) {
                case 'event':
                    return $user->nickname.'收到事件消息';

                case 'text':
                    return $user->nickname.'收到文字消息';

                case 'image':
                    return $user->nickname.'收到图片消息';

                case 'voice':
                    return $user->nickname.'收到语音消息';

                default:
                    return $user->nickname.'收到其它消息';
            }
        });

        return $wechat->server->serve();
    }

    public function getUsers()
    {
        $openids = $this->wechat_user->list()->data->openid;
        $users = $this->wechat_user->select($openids);
        dd($users);
    }

    public function getTags()
    {
        dd($this->wechat->user_tag->list());
    }

    public function getUsersBelongsToTag($tag_id)
    {
        dd($this->wechat->user_tag->usersOfTag($tag_id));
    }

    public function uploadImage()
    {
        $result = $this->wechat->material->uploadImage(public_path('/coding10.png'));

        dd($result);
    }

    public function getMaterial($material_id)
    {
        dd($this->wechat->material->get($material_id));
    }
}
