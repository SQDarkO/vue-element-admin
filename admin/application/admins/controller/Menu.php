<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;


class Menu extends Controller
{
    public function index(){
        $this->db = new Sysdb;
        $pid = (int)input('get.pid');
        $data['lists'] = $this->db->table('admin_menus')->where(array('pid'=>$pid))->lists();

        //返回上级菜单
        $backid = 0;
        if($pid > 0){
            $parent = $this->db->table('admin_menus')->where(array('mid'=>$pid))->item();
            $backid = $parent['pid'];
            $data['backid'] = $backid;
            $data['pid'] = $pid;

        }
        // return(json_encode($pid));
        // return(json_encode($backid));
        return(json_encode($data));
    }
    //添加菜单
    public function save(){
        $this->db = new Sysdb;
        $ishidden = input('post.ishiddens');
        $status = input('post.status');
        $pid = input('post.pid');
        $data['ord'] = input('post.ords');
        $data['title'] = input('post.titles');
        $data['pid'] = isset($pid) ? $pid : 0;
        $data['controller'] = input('post.controllers');
        $data['method'] = input('post.methods');
        $data['ishidden'] =  isset($ishidden) ? 1 : 0;
        $data['status'] =  isset($status)? 1 : 0;

        $res = $this->db->table('admin_menus')->insert($data);
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
    //删除菜单
    public function delete(){
        $this->db = new Sysdb;
        $title = input('post.title');
        $res = $this->db->table('admin_menus')->where(array('title'=>$title))->delete();
        if(!$res){
            exit(json_encode(array('code'=>1,'msg'=>'删除失败')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
    }
}