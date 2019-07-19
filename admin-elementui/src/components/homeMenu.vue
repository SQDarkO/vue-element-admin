<template>
  <el-row class="tac" style="position: absolute;">
    <input type="hidden" :name="changeMenus" />
    <el-col :span="12">
      <!-- <h5>自定义颜色</h5> -->
      <el-menu
        :style="menu_height"
        default-active="2"
        class="el-menu-vertical-demo"
        background-color="#333744"
        text-color="#fff"
        active-text-color="#ffd04b"
      >
         <el-submenu :index="item.title" v-for="item in admin_lists" :key="item.mid">
          <template slot="title">
            <i class="el-icon-location"></i>
            <span>{{item.title}}</span>
          </template>
          <el-menu-item v-for="childitem in item.children" :key="childitem.mid" :index="childitem.title" @click="con_admin(childitem.title)">{{childitem.title}}</el-menu-item>
          <!-- <el-submenu index="1-4">
            <template slot="title">选项</template>
            <el-menu-item index="1-4-1">选项1</el-menu-item>
          </el-submenu> -->
        </el-submenu>

        <!-- <el-submenu index="1">
          <template slot="title">
            <i class="el-icon-location"></i>
            <span>管理员管理{{test}}</span>
          </template>
          <el-menu-item index="1-1" @click="con_admin('admin')">管理员列表</el-menu-item>
          <el-submenu index="1-4">
            <template slot="title">选项4</template>
            <el-menu-item index="1-4-1">选项1</el-menu-item>
          </el-submenu>
        </el-submenu>
        <el-submenu index="2">
          <template slot="title">
            <i class="el-icon-location"></i>
            <span>权限管理</span>
          </template>
          <el-menu-item index="1-1" @click="con_admin('menuAdmin')">菜单管理</el-menu-item>
          <el-menu-item index="1-1" @click="con_admin('roleAdmin')">角色管理</el-menu-item>
        </el-submenu>
        <el-menu-item index="3" disabled>
          <i class="el-icon-document"></i>
          <span slot="title">系统设置</span>
        </el-menu-item>
        <el-menu-item index="4">
          <i class="el-icon-setting"></i>
          <span slot="title">其他</span>
        </el-menu-item> -->
      </el-menu>
    </el-col>
  </el-row>
</template>
<script>
export default {
  name: "homeMenu",
  props: {},
  data: function() {
    return {
      menu_height: "",
      test: "",
      admin_lists: []
    };
  },
  computed: {
    changeMenus() {
      // return this.$store.state.admin_gid;
      this.getMenus(this.$store.state.admin_gid);
    }
  },
  mounted() {
    this.getMenus(this.$store.state.admin_gid);
    //计算左侧菜单高度
    var count = document.documentElement.clientHeight - 50;
    this.menu_height = "height:" + count + "px";
  },
  methods: {
    con_admin: function(res) {
      this.$emit("listenToChildrenEvent", res);
    },
    getMenus: function(gid) {
      let _self = this;
      //数据请求代码  response为响应
      this.$http
        .post("/sell/admins.php/admins/Home/index", {
          gid: gid
        })
        .then(function(response) {
          _self.test = gid;
          _self.admin_lists = response.data;
          console.log(JSON.stringify(_self.admin_lists));
        });
    }
  }
};
</script>
<style>
.el-menu-vertical-demo {
  width: 200px;
}
</style>
