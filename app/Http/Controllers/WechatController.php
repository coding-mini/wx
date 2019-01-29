<?php

namespace App\Http\Controllers;

use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
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
                    switch ($message->Event) {
                        case 'subscribe':

                            break;

                        case 'unsubscribe':
                            break;

                        case 'CLICK':
                            switch ($message->EventKey) {
                                case 'KEY_GIVE_ME_OK':
                                    return '我给你点赞';

                                case 'KEY_ABOUT_US':
                                    return '你点击了关于我们';

                                default:
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    return $user->nickname.'收到事件消息';

                case 'text':
                    $new = new NewsItem([
                        'title'       => 'Coding10 欢迎你',
                        'description' => 'XXXXX',
                        'url'         => 'http://www.coding10.com',
                        'image'       => public_path('coding10.png')
                    ]);

                    return new News([$new]);
                    //return $user->nickname.'收到文字消息';

                case 'image':
                    $image = new Image('wWzPhXyhPpOxBB-jDIPmwk6FkS_i1qcg74VwcjVjEVo');
                    $wechat->customer_service->message($image)->to($message->FromUserName)->send();
                    return '';
                    //return $user->nickname.'收到图片消息';

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
        return $this->wechat->user_tag->usersOfTag($tag_id)->data->openid;
    }

    public function uploadImage()
    {
        $result = $this->wechat->material->uploadImage(public_path('/coding10.png'));

        dd($result);
    }

    public function getMaterials()
    {
        $media_ids = $this->wechat->material->list('news');
        dd($media_ids);
    }

    public function getMaterial($material_id)
    {
        $stream = $this->wechat->material->get($material_id);

        if ($stream instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            // 以内容 md5 为文件名
            $stream->save('materials');
        }
    }

    public function broadcastTagUsers()
    {
//        $image = new Image('wWzPhXyhPpOxBB-jDIPmwk6FkS_i1qcg74VwcjVjEVo');
        $open_ids = $this->getUsersBelongsToTag(102);
        $this->wechat->broadcasting->sendImage('wWzPhXyhPpOxBB-jDIPmwk6FkS_i1qcg74VwcjVjEVo',$open_ids);
        return 'Done';
    }

    public function addMenu()
    {
        $buttons = [
            [
                "type" => "click",
                "name" => "About Us",
                "key"  => "KEY_ABOUT_US"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url"  => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url"  => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "KEY_GIVE_ME_OK"
                    ],
                ],
            ],
        ];
        $this->wechat->menu->create($buttons);
    }
}
