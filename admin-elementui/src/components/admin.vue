<template>
  <div class="adminList">
    <div class="admin_header">
      <span class="title">管理员列表</span>
      <!-- <el-button type="success" @click="handleAdd()">添加</el-button> -->
      <el-button class="button_add" type="success" @click="handleAdd()">添加</el-button>
      <el-dialog :title="this.info_title" :visible.sync="centerDialogVisible" width="30%" center>
        <el-form ref="form" :model="form" label-width="80px">
          <input type="hidden" name="id" v-model="form.id" />
          <el-form-item label="用户名">
            <el-input v-model="form.username" :readonly="permission(form.id)"></el-input>
          </el-form-item>
          <el-form-item label="角色">
            <el-select v-model="form.gid" placeholder="请选择角色">
              <el-option
                v-for="item in admin_groups"
                :key="item.id"
                :label="item.title"
                :value="item.gid"
              ></el-option>
            </el-select>
            <!-- <el-select v-model="form.gid" placeholder="请选择角色">
              <el-option v-for="item in form" :key="item.id" :label="item.title" :value="item.gid"></el-option>
            </el-select>-->
          </el-form-item>
          <el-form-item label="密码">
            <el-input v-model="form.password"></el-input>
          </el-form-item>
          <el-form-item label="姓名">
            <el-input v-model="form.truename"></el-input>
          </el-form-item>
          <el-form-item label="状态">
            <el-switch
              active-color="#67C23A"
              active-text="正常"
              active-value="1"
              inactive-text="禁用"
              inactive-value="0"
              v-model="form.status"
            ></el-switch>
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button @click="centerDialogVisible = false">取 消</el-button>
          <!-- <el-button type="success" @click="centerDialogVisible = false,handleSubmit()">提 交</el-button> -->
          <el-button type="success" @click="handleSubmit()">提 交</el-button>
        </span>
      </el-dialog>
      <div class="line_bot"></div>
    </div>
    <el-table :data="admin_lists" style="width: 100%">
      <el-table-column label="ID" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="用户名" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.username }}</span>
        </template>
      </el-table-column>
      <el-table-column label="真实姓名" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.truename}}</span>
        </template>
      </el-table-column>
      <el-table-column label="角色" width="180">
        <template slot-scope="scope">
          <!-- <input type="hidden" :value="admin_title(scope.row.gid)"> -->
          <!-- <span style="margin-left: 10px" v-text=admin_gid[scope.row.gid]></span> -->
          <!-- <span style="margin-left: 10px">{{admin_title(scope.row.gid)}}</span> -->
          <div  v-for="item in roles_lists" :key="item.id" >
            <span v-if="item.gid == scope.row.gid" style="margin-left: 10px" >{{item.title}}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="状态" width="180">
        <template slot-scope="scope">
          <span v-if="scope.row.status == 1">正常</span>
          <span class="red" v-if="scope.row.status == 0">禁用</span>
        </template>
      </el-table-column>
      <el-table-column label="添加时间" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.add_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handleAdd(scope.row.id)">编辑</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
export default {
  name: "admin",
  data: function() {
    return {
      roles_lists: [],
      info_title: "",
      centerDialogVisible: false,
      admin_lists: [],
      admin_groups: [],
      admin_gid: {
        1: "系统管理员",
        2: "开发人员",
        3: "测试人员"
      },
      form: {}
    };
  },
  mounted() {
    this.admin_request();
    this.admin_title();
    // this.changearr();
  },
  // computed: {
  //   // 计算属性的 getter
  //   admin_title: function (gid) {
  //     let _self = this
  //        this.$http
  //       .post("/sell/admins.php/admins/Admin/add2?id="+gid, {
  //       })
  //       .then(function(response) {
  //           _self.admin_groups = response.data;
  //           _self.test =response.data[0].title;
  //           console.log(JSON.stringify(_self.admin_groups));
  //           // console.log(JSON.stringify(_self.admin_groups[1].title));

  //       });
  //           return _self.test;
  //   }
  // },
  methods: {
    //变换数组
    // changearr:function (){
    //   var _self = this
    //   for(var i =0;i<_self.roles_lists.length;i++)
    //   {
    //     _self.admin_gid[_self.roles_lists.gid] = _self.roles_lists.title;
    //   }
    //   alert(JSON.stringify(_self.admin_gid));
    // },
    //用户名读写权限
    permission: function(th) {
      if (th > 0) {
        return true;
      }
    },
    //请求管理员列表
    admin_request: function() {
      let _self = this;
      //数据请求代码  response为响应
      this.$http
        .post("/sell/admins.php/admins/Admin/index", {
          //   username: username,
          //   pwd: pwd
        })
        .then(function(response) {
          _self.admin_lists = response.data.lists;
          // alert(_self.admin_lists);
        });
    },
    //获取管理员角色
    admin_title: function() {
      let _self = this;
      this.$http
        .post("/sell/admins.php/admins/Admin/add2", {})
        .then(function(response) {
          // _self.admin_groups = response.data.groups;
          _self.roles_lists = response.data;
          console.log(JSON.stringify(_self.roles_lists[0].title));
        });
    },
    //添加和编辑
    handleAdd: function(id) {
      let _self = this;
      if (id > 0) {
        this.info_title = "编辑管理员";
        this.$http
          .post("/sell/admins.php/admins/Admin/add?id=" + id, {})
          .then(function(response) {
            _self.admin_groups = response.data.groups;
            _self.form = response.data.item;
          });
      } else {
        this.info_title = "添加管理员";
        this.$http
          .post("/sell/admins.php/admins/Admin/add", {})
          .then(function(response) {
            _self.admin_groups = response.data.groups;
            _self.form = {};
          });
      }
      this.centerDialogVisible = true;
    },
    //删除
    handleDelete: function(id) {
      let _self = this;
      this.$confirm("此操作将永久删除该数据, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.$http
            .post("/sell/admins.php/admins/Admin/delete", {
              id: id
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
    },
    handleSubmit() {
      let _self = this;
      var id = parseInt(_self.form.id);
      var username = _self.form.username;
      var password = _self.form.password;
      var gid = _self.form.gid;
      var truename = _self.form.truename;
      var status = _self.form.status;
      if (username == undefined) {
        this.$message.error("请输入用户名");
        return;
      }
      if (isNaN(id) && password == undefined) {
        this.$message.error("请输入密码");
        return;
      }
      if (gid == undefined) {
        this.$message.error("请选择角色");
        return;
      }
      if (truename == undefined) {
        this.$message.error("请输入姓名");
        return;
      }
      this.$http
        .post("/sell/admins.php/admins/Admin/save", {
          id: id,
          username: username,
          password: password,
          gid: gid,
          truename: truename,
          status: status
        })
        .then(function(response) {
          if (response.data.code > 0) {
            _self.$message.error(response.data.msg);
          } else {
            _self.$message({
              message: response.data.msg,
              type: "success"
            });
            (_self.centerDialogVisible = false), _self.admin_request();
          }
        });
    }
  }
};
</script>
<style>
.red {
  color: red;
}
.adminList {
  padding: 10px;
}
.admin_header .title {
  background: #67c23a;
  margin-left: 30px;
  padding: 10px;
  color: #ffffff;
}
.admin_header .button_add {
  float: right;
  margin-top: -5px;
}
.line_bot {
  border-bottom: solid 2px #67c23a;
  margin-top: 8px;
}
.el-switch .el-switch__label.is-active {
  color: #67c23a;
}
</style>
