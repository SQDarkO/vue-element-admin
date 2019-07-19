<template>
    <div class="header">
        <span class="title">后台管理系统
          <el-form :inline="true" style="margin-top:-45px;margin-left:300px;">
            <el-form-item>
              <el-select v-model="admin_gid" placeholder="角色选择">
                <el-option v-for="item in admin_lists" :key="item.id" :label="item.title" :value="item.gid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="confirm(admin_gid)">确认</el-button>
            </el-form-item>
          </el-form>
        </span>
    </div>
</template>
<script>
export default {
  name: 'homeHeader',
  data: function() {
      return {
        admin_lists:[],
        admin_gid:''
      }
    },
    mounted() {
    this.admin_lists_request();
    },
    methods:{
      //请求角色列表
    admin_lists_request: function() {
      let _self = this;
      //数据请求代码  response为响应
      this.$http
        .post("/sell/admins.php/admins/Roles/index", {
          //   username: username,
          //   pwd: pwd
        })
        .then(function(response) {
         _self.admin_lists = response.data.roles;
         console.log(JSON.stringify( _self.admin_lists));
        });
    },
    confirm:function(gid){
      // let _self = this;
      // //数据请求代码  response为响应
      // this.$http
      //   .post("/sell/admins.php/admins/Home/index", {
      //       gid: gid,
      //     //   pwd: pwd
      //   })
      //   .then(function(response) {
      //     _self.admin_lists = response.data.roles;
      //    console.log(JSON.stringify( _self.admin_lists));
      //   });
      
      //提交状态
      this.$store.commit('changeGid',gid);
      // alert(gid);
        }
    },
}
</script>
<style>
.header{
  width: 100%;
  height: 50px;
  line-height: 50px;
  background-color: #2e6da4;
  color: #ffffff;
}
.title{
  margin-left: 20px;
  font-size: 20px;
}
</style>
