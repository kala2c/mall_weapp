<?php


namespace app\common\model;


use think\Model;

class Orders extends Model
{
    const STATUS_CANCEL = -1;
    const STATUS_WAIT = 0;
    const STATUS_SENT = 1;
    const STATUS_SUCCESS = 2;
    public static $STATUS = [
        self::STATUS_CANCEL => '已取消',
        self::STATUS_WAIT => '待处理',
        self::STATUS_SENT   => '待确认',
        self::STATUS_SUCCESS => '已完成'
    ];
    static public function createNo()
    {
        $no = date('Ymd').time().rand(100, 999);
        if (self::get(['orderno' => $no])) {
            self::createNo();
        } else {
            return $no;
        }
    }

    static public function updateStatus($id, $status)
    {
        return self::update([
            'status' => $status
        ], ['id' => $id]);
    }
    public function getStatusTextAttr($value, $data)
    {
        return self::$STATUS[$data['status']];
    }
    public function user()
    {
        return $this->belongsTo('User');
    }

    public function goods()
    {
        return $this->belongsTo('Goods');
    }
}