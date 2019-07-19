<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;


class Roles extends Controller
{
    //角色列表
    public function index(){
        $this->db = new Sysdb;
        $data['roles'] = $this->db->table('admin_groups')->lists();
        return(json_encode($data));
    }
    //角色添加
    public function add(){
        $this->db = new Sysdb;
      
        $menus_list = $this->db->table('admin_menus')->where(array('status'=>0))->cates('mid');
        $menus = $this->gettreeitems($menus_list);
        $results = array();
        foreach($menus as $value){
            $value['children'] = isset($value['children'])?$this->formatMenus($value['children']):false;
            $results[] = $value;
        }
        return(json_encode($results));
    }
    //角色编辑
    public function edit(){
        $this->db = new Sysdb;

        $gid = (int)input('get.gid');
        $role =  $this->db->table('admin_groups')->where(array('gid'=>$gid))->item();
        $role && $role['rights'] && $role['rights'] = json_decode($role['rights']);
        return(json_encode($role));
    }

    private function gettreeitems($items){
        $tree = array();
        foreach ($items as $item){
            if(isset($items[$item['pid']])){
                $items[$item['pid']]['children'][] = &$items[$item['mid']];
            }else{
                $tree[] = &$items[$item['mid']];
            }
        }
        return $tree;
    }
    private function formatMenus($items, &$res = array()){
        foreach($items as $item){
            if(!isset($item['children'])){
                $res[] = $item;
            }else{
                $tem = $item['children'];
                unset($item['children']);
                $res[] = $item;
                $this->formatMenus($tem,$res);
            }
        }
        return $res;
    }
    //角色保存
    public function save(){
        $this->db = new Sysdb;
        $gid = (int)input('get.gid');

        $data['title'] = trim(input('post.title'));
        $menus = input('post.menu/a');
        if(!$data['title']){
            exit(json_encode(array('code'=>1,'msg'=>'角色名不能为空')));
        }
        $menus && $data['rights'] = json_encode($menus);
        if($gid){
            $this->db->table('admin_groups')->where(array('gid'=>$gid))->update($data);
        }else{
            $this->db->table('admin_groups')->insert($data);
        }
        return(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
    //删除角色
    public function delete(){
         $this->db = new Sysdb;
        $gid = (int)input('gid');
        $this->db->table('admin_groups')->where(array('gid'=>$gid))->delete();
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));

    }
}