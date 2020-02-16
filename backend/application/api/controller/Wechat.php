<?php
/**
 * Created by PhpStorm.
 * User: clw
 * Date: 19-1-31
 * Time: 下午3:48
 */

namespace app\api\controller;
//模型
use app\common\model\User as MemberModel;
//工具类
use \Requests;
use Firebase\JWT\JWT;
use think\Exception;
use think\facade\Cache;
use think\Validate;

class Wechat extends Base
{
    private $appid = 'wx57e9f4ddd8efc595';
    private $appsecret = 'a50dc1110c1730406ac5a46ad0fb1e25';

    public function postjscode2session()
    {
//      检验参数
        $jscode = $this->request->post('jscode') ?? null;
        if (!$jscode) {
            return json(err('操作禁止！！'))->code(400);
        }
//      获取openid和seession——key
        $wxUserInfo = $this->wxUserInfo($jscode);
        if (count($wxUserInfo) < 1) return json(err('服务器错误，稍后再试'))->code(500);
//        检查用户是否绑定了学籍信息
        $member = MemberModel::where('status', '0')->get(['openid' => $wxUserInfo['openid']]);
        if (!$member) {
            return json(err('尚未完善个人信息'))->code(403);
        }
//        登录 签发token
        $payload = [
            'uid'    => $member->id,
            'openid' => $member->openid,
        ];
        $token   = JWT::encode($payload, '2nd');
        Cache::set($token, $wxUserInfo['session_key']);
        return json(['token' => $token]);
    }

//   完善个人信息
    public function postbindwx()
    {
//        接受参数 验证
        $query    = $this->request->post();
        $validate = Validate::make([
            'jscode'     => 'require',
            'phone' => 'require|mobile',
            'realname'   => 'require',
        ]);
        if (!$validate->check($query)) {
            return json(err('参数错误，请检查参数'))->code(400);
        }
//        获取open——id
        $wxUserInfo = $this->wxUserInfo($query['jscode']);
        if (count($wxUserInfo) < 1) return json(err('服务器错误，稍后再试'))->code(500);
//        检测openid是否已存在
        if (!isset($wxUserInfo['openid'])) {
            return json(err('出错了 再试一次'))->code(500);
        }
        if (MemberModel::get(['openid' => $wxUserInfo['openid']])) {
            return json(err('不可重复绑定'))->code(403);
        }
//        写入用户
        $member = MemberModel::create([
            'phone' => $query['phone'],
            'realname' => $query['realname'],
            'openid' => $wxUserInfo['openid'],
            'points' => 100
//            'headpic' => $wxUserInfo['headImgUrl'],
//            'nickname' => $wxUserInfo['nickName']
        ]);
        if (!$member) {
            //记log
            return json(err('服务器错误'))->code(403);
        }
        //可能会有 操作2 写入新数据 再议
//        执行登录操作
        $payload = [
            'uid'    => $member->id,
            'openid' => $member->openid,
        ];
        $token   = JWT::encode($payload, '2nd');
        Cache::set($token, $wxUserInfo['session_key']);
        return json(['token' => $token]);
    }

//    通过jscode获取openid和session_key
    private function wxUserInfo($jscode = null)
    {
        if (!isset($jscode)) {
            return [];
        }
        $api = 'https://api.weixin.qq.com/sns/jscode2session?appid=' .
            $this->appid . '&secret=' .
            $this->appsecret . '&js_code=' .
            $jscode . '&grant_type=authorization_code';

        $response = Requests::get($api);
        if ($response->status_code == 200) {
            return (array)json_decode($response->body);
        } else {
//            $this->enlog('请求wx错误:' . $response->status_code, $response->body);
            return [];
        }
    }
}