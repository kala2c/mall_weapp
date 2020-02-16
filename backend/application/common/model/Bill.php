<?php
namespace app\common\model;
use think\Model;
use app\common\model\User;
class Bill extends Model
{
    const TYPE_INC = 1;
    const TYPE_DEC = 2;
    const TYPE_ADMIN_INC = 3;
    const TYPE_ADMIN_DEC = 4;

    public static $STATUS = [
        self::TYPE_INC => '增加',
        self::TYPE_DEC => '减少',
        self::TYPE_ADMIN_INC => '增加',
        self::TYPE_ADMIN_DEC => '减少'
    ];
    public function orders()
    {
        return $this->belongsTo('Orders', 'orderno', 'orderno');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }
//    生成账单
//    同时扣除或增加积分
    static public function add($uid, $type, $price, $info, $orderno = 0)
    {
        $user = User::get($uid);
        if ($type == self::TYPE_INC) {
            $user->points += $price;
        } else {
            if ($user->points < $price) {
                return -1;
            } else {
                $user->points -= $price;
            }
        }
        if ($user->save()) {
            return self::create([
                'user_id' => $uid,
                'orderno' => $orderno,
                'type' => $type,
                'price' =>$price,
                'info' => $info
            ]);
        } else {
            return 0;
        }
    }
}