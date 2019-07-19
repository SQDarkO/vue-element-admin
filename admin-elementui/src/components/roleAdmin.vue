<template>
  <div class="meunList">
    <div class="admin_header">
      <span class="title">角色管理</span>
      <el-button type="success" class="btn_add" @click="handleAdd()">添加</el-button>
      <el-dialog :title="this.info_title" :visible.sync="centerDialogVisible" width="30%" center>
        <input type="hidden" name="gid" v-model="roleRights_lists.gid">
        <el-form ref="roleRights_lists" label-width="80px">
          <el-form-item label="角色名称">
            <el-input v-model="form.title"></el-input>
          </el-form-item>
          <div v-for="item in roleRights_lists" :key="item.mid">
            <el-checkbox :label="item.mid" v-model="select_rights">{{item.title}}</el-checkbox>
            <hr>
            <el-checkbox v-model="select_rights" v-for="itemchild in item.children" :key="itemchild.mid" :label="itemchild.mid" style="margin-left:30px">{{itemchild.title}}</el-checkbox>
          </div>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button @click="centerDialogVisible = false">取 消</el-button>
          <!-- <el-button type="success" @click="centerDialogVisible = false,handleSubmit()">提 交</el-button> -->
          <el-button type="success" @click="handleSubmit(add_gid)">提 交</el-button>
        </span>
      </el-dialog>
      <div class="line_bot"></div>
    </div>
    <el-table class="mainLists" :data="roleAdmin_lists" style="width: 100%">
      <el-table-column label="ID" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.gid }}</span>
        </template>
      </el-table-column>
      <el-table-column label="角色名称" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" type="success" @click="handleAdd(scope.row.gid)">编辑</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.row.gid)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
import { stringify } from 'querystring';
export default {
  name: "menuAdmin",
  data: function() {
    return {
      add_gid:'',
      info_title:'',
      centerDialogVisible: false,
      roleAdmin_lists: [],
      roleRights_lists:{},
      form:{},
      select_rights:[],
    };
  },
  mounted() {
    let _self = this;
    this.admin_request();
    
  },

  methods: {
    //请求菜单列表
    admin_request: function() {
      let _self = this;
      //数据请求代码  response为响应
      this.$http
        .post("/sell/admins.php/admins/Roles/index", {
          //   username: username,
          //   pwd: pwd
        })
        .then(function(response) {
          _self.roleAdmin_lists = response.data.roles;
        //  console.log(JSON.stringify( _self.roleAdmin_lists));
        });
    },
    // add: function() {
    //     this.centerDialogVisible = true;
    //     let _self = this;
    //     //数据请求代码  response为响应
    //     this.$http
    //         .post("/sell/admins.php/admins/Roles/add", {
    //         })
    //         .then(function(response) {
    //         _self.roleRights_lists = response.data;
    //         _self.form ={};
    //     });
    // },
     //添加和编辑
    handleAdd: function(gid){
      let _self = this
       if(gid>=0){
        this.info_title = '编辑管理员'
        this.$http
        .post("/sell/admins.php/admins/Roles/edit?gid="+gid, {
        })
        .then(function(response) {
          // _self.roleRights_lists = response.data;
          _self.form = response.data;
          _self.select_rights = response.data.rights;
          console.log(JSON.stringify(_self.select_rights));
            _self.$http
            .post("/sell/admins.php/admins/Roles/add",{
            })
            .then(function(response){
             _self.roleRights_lists = response.data;
             console.log(JSON.stringify( _self.roleRights_lists));
            });
        });
        _self.add_gid = gid;
      }else{
        this.info_title = '添加管理员'
       //数据请求代码  response为响应
        this.$http
            .post("/sell/admins.php/admins/Roles/add", {
            })
            .then(function(response) {
            _self.roleRights_lists = response.data;
             _self.select_rights = [];
            // _self.roleRights_lists = {};
            // alert(JSON.stringify(_self.roleRights_lists));
            _self.form ={};
        });
      }
      this.centerDialogVisible = true
    },
    handleSubmit:function(gid){
      let _self = this;
      var title = _self.form.title;
      var menu = _self.select_rights;
      if(title == undefined){
        this.$message.error('请输入角色名称');
        return;
      }
      this.$http
      .post("/sell/admins.php/admins/Roles/save?gid="+gid, {
        title:title,
        menu: menu
      })
      .then(function(response) {
        if(response.data.code>0){
          _self.$message.error(response.data.msg);
        }else{
          _self.$message({
            message: response.data.msg,
            type: 'success',
          });
            _self.centerDialogVisible = false,
            _self.admin_request();
        }
      });
    },
    //删除
    handleDelete:function(gid){
      let _self = this;
      this.$confirm("此操作将永久删除该数据, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.$http
            .post("/sell/admins.php/admins/Roles/delete", {
              gid: gid
            })
            .then(function(response) {
              if (response.data.code > 0) {
                _self.$message.error(response.data.msg);
              } else {
                _self.$message({
                  message: response.data.msg,
                  type: "success"
                });
                _self.admin_request();
              }
            });
          // this.$message({
          //   type: 'success',
          //   message: '删除成功!'
          // });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    }
  }
};
</script>
<style>
.red {
  color: red;
}
.meunList {
  padding: 10px;
}
.admin_header .title {
  background: #67c23a;
  margin-left: 30px;
  padding: 10px;
  color: #ffffff;
}
.admin_header .btn_add {
  float: right;
  margin-top: -5px;
}
.line_bot {
  border-bottom: solid 2px #67c23a;
  margin-top: 8px;
}
</style>
