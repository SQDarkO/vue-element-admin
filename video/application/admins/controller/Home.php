<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;


class Home extends Controller
{
    public function index(){
        $this->db = new Sysdb;
        $gid = (int)input('gid');
        $role =  $this->db->table('admin_groups')->where(array('gid'=>$gid))->item();
        if($role){
            $role['rights'] = (isset($role['rights']) && $role['rights']) ? json_decode($role['rights'],true): [];
        }
        if($role['rights']){
            $where = 'mid in ('.implode(',',$role['rights']).')';
            $menus = $this->db->table('admin_menus')->where($where)->cates('mid');
            $menus && $menus = $this->gettreeitems($menus);
        }
        return(json_encode($menus));
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
}