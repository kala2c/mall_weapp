<?php


namespace app\admin\controller;

class Upload extends ApiBase
{

    private $save_dir = '../public/uploads';
//    private $temp_dir = '';
    public function uploadImg()
    {
        $file = $this->request->file('file');
        $info = $file->move($this->save_dir);
        if ($info) {
            return json(['path' => $info->getSaveName()]);
        } else {
            return json(['err' => $file->getError()]);
        }
    }
}