<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;


class Admin extends Controller
{
    //管理员列表
    public function index(){
        $this->db = new Sysdb;
        $data['lists'] = $this->db->table('admins')->lists();
        // return($data);
        return(json_encode($data));
        // $this->assign('data',$data);
    }
    //加载角色
    public function add(){
        $this->db = new Sysdb;
        $id = (int)input('get.id');
        //加载管理员
        $data['item'] = $this->db->table('admins')->where(array('id'=>$id))->item();
        //加载角色
        $data['groups'] = $this->db->table('admin_groups')->cates('gid');
        return(json_encode($data));
    }
     //加载角色2
     public function add2(){
        $this->db = new Sysdb;
        
        $data = $this->db->table('admin_groups')->lists();
        return(json_encode($data));
    }
    //保存管理员
    public function save(){
        $this->db = new Sysdb;
        $id = (int)input('post.id');
        $data['username'] = trim(input('post.username'));
        $data['gid'] = (int)input('post.gid');
        $data['password'] = trim(input('post.password'));
        $data['truename'] = trim(input('post.truename'));
        $data['status'] = (int)input('post.status');

        if(!$data['username']){
            exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空')));
        }
        if(!$data['gid']){
            exit(json_encode(array('code'=>1,'msg'=>'角色不能为空')));
        }
        if($id == 0 && !$data['password']){
            exit(json_encode(array('code'=>1,'msg'=>'密码不能为空')));
        }
        if(!$data['truename']){
            exit(json_encode(array('code'=>1,'msg'=>'姓名不能为空')));
        }
        $res = true;
        if($id == 0){
             //检查用户是否存在
            $item = $this->db->table('admins')->where(array('username'=>$data['username']))->item();
            if($item){
                exit(json_encode(array('code'=>1,'msg'=>'用户名已存在')));
            }
            $data['add_time'] = time();
             //保存用户
            $res = $this->db->table('admins')->insert($data);
        }else{
            $this->db->table('admins')->where(array('id'=>$id))->update($data);
        }
       
        if(!$res){
            exit(json_encode(array('code'=>1,'msg'=>'保存失败')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
    //删除管理员
    public function delete(){
        $this->db = new Sysdb;
        $id = (int)input('post.id');
        $res = $this->db->table('admins')->where(array('id'=>$id))->delete();
        if(!$res){
            exit(json_encode(array('code'=>1,'msg'=>'删除失败')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
    }
}