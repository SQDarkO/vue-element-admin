<template>
  <div class="meunList">
    <div class="admin_header">
      <span class="title">菜单管理</span>
      <el-button v-if="pid>0" type="success" class="btn_back" @click="back(backid)">返回上级</el-button>
      <div class="line_bot"></div>
    </div>
    <el-table class="mainLists" :data="menuAdmin_lists" style="width: 100%">
      <el-table-column label="ID" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.mid }}</span>
        </template>
      </el-table-column>
      <el-table-column label="排序" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.ord }}</span>
        </template>
      </el-table-column>
      <el-table-column label="菜单名称" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.title}}</span>
        </template>
      </el-table-column>
      <el-table-column label="controller" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.controller}}</span>
        </template>
      </el-table-column>
      <el-table-column label="controller" width="180">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.method}}</span>
        </template>
      </el-table-column>
      <el-table-column label="是否隐藏" width="180">
        <template slot-scope="scope">
          <el-checkbox
            :checked="con_method(scope.row.ishidden)"
            style="margin-left: 10px"
            v-model="scope.row.ishidden"
          >隐藏</el-checkbox>
        </template>
      </el-table-column>
      <el-table-column label="是否禁用" width="180">
        <template slot-scope="scope">
          <el-checkbox
            style="margin-left: 10px"
            :checked="con_method(scope.row.status)"
            v-model="scope.row.status"
          >禁用</el-checkbox>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" type="success" @click="child(scope.row.mid)">子菜单</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.row.title)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-button
      @click="addMenu()"
      style="margin-left:50%;margin-top:20px;width:100px;"
      type="success"
    >添加</el-button>
    <el-dialog :visible.sync="centerDialogVisible" width="30%" center class="add_dialog">
      <!-- <el-input v-model="this.pid"></el-input> -->
      <el-form ref="form" label-width="80px">
        <el-form-item label="排序">
          <el-input v-model="form.ords"></el-input>
        </el-form-item>
        <el-form-item label="菜单名称">
          <el-input v-model="form.titles"></el-input>
        </el-form-item>
        <!-- <el-form-item label="pid">
            <el-input v-model="form.pid"></el-input>
        </el-form-item>-->
        <el-form-item label="controller">
          <el-input v-model="form.controllers"></el-input>
        </el-form-item>
        <el-form-item label="method">
          <el-input v-model="form.methods"></el-input>
        </el-form-item>
        <el-checkbox v-model="form.status" style="margin-left:5%;">禁用</el-checkbox>
        <el-checkbox v-model="form.ishiddens">隐藏</el-checkbox>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="centerDialogVisible = false">取 消</el-button>
        <!-- <el-button type="success" @click="centerDialogVisible = false,handleSubmit()">提 交</el-button> -->
        <el-button type="success" @click="handleSubmit()">提 交</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: "menuAdmin",
  data: function() {
    return {
      centerDialogVisible: false,
      pid: 0,
      backid: "",
      menuAdmin_lists: [],
      form: {}
    };
  },
  mounted() {
    let _self = this;
    this.admin_request(_self.pid);
  },

  methods: {
    //将数字1转换成true
    con_method: function(th) {
      if (th == 1) {
        return true;
      }
    },
    //请求菜单列表
    admin_request: function(pid) {
      let _self = this;
      //数据请求代码  response为响应
      this.$http
        .post("/sell/admins.php/admins/Menu/index?pid=" + pid, {
          //   username: username,
          //   pwd: pwd
        })
        .then(function(response) {
          _self.menuAdmin_lists = response.data.lists;
          _self.backid = response.data.backid;
          _self.pid = response.data.pid;
        });
    },
    //子菜单
    child: function(pid) {
      // var url ="/sell/admins.php/admins/Menu/index?pid"+pid;
      // this.pid = pid;
      this.admin_request(pid);
    },
    //返回上级菜单
    back: function(pid) {
      this.admin_request(pid);
    },
    //添加菜单
    addMenu: function() {
      this.centerDialogVisible = true;
    },
    handleSubmit() {
      let _self = this;
      var ords = _self.form.ords;
      var pid = _self.pid;
      var titles = _self.form.titles;
      var controllers = _self.form.controllers;
      var methods = _self.form.methods;
      var ishiddens = _self.form.ishiddens;
      var status = _self.form.status;
      this.$http
        .post("/sell/admins.php/admins/Menu/save", {
          ords: ords,
          pid: pid,
          titles: titles,
          controllers: controllers,
          methods: methods,
          methods: methods,
          ishiddens: ishiddens,
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
    },
    //删除
    handleDelete: function(title) {
      let _self = this;
      this.$confirm("此操作将永久删除该数据, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.$http
            .post("/sell/admins.php/admins/Menu/delete", {
              title: title
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
.admin_header .btn_back {
  float: right;
  margin-top: -5px;
}
.line_bot {
  border-bottom: solid 2px #67c23a;
  margin-top: 8px;
}
.mainLists .el-checkbox__input.is-checked + .el-checkbox__label {
  color: #67c23a;
}
.mainLists .el-checkbox__input.is-checked .el-checkbox__inner,
.el-checkbox__input.is-indeterminate .el-checkbox__inner {
  background-color: #67c23a;
  border-color: #67c23a;
}
.add_dialog .el-checkbox__input.is-checked + .el-checkbox__label {
  color: #67c23a;
}
.add_dialog .el-checkbox__input.is-checked .el-checkbox__inner,
.el-checkbox__input.is-indeterminate .el-checkbox__inner {
  background-color: #67c23a;
  border-color: #67c23a;
}
</style>
