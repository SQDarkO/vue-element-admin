<template>
  <div class="admin">
    <homeHeader></homeHeader>
    <homeMenu v-on:listenToChildrenEvent="showMsgChild"></homeMenu>
    <div class="main" :style="main_height">
      <welcome v-if="welcome_show"></welcome>
      <admin v-if="admin_show"></admin>
      <menuAdmin v-if="menuAdmin_show"></menuAdmin>
      <roleAdmin v-if="roleAdmin_show"></roleAdmin>
    </div>
  </div>
</template>
<script>
// @ is an alias to /src
import homeHeader from '@/components/homeHeader.vue'
import homeMenu from '@/components/homeMenu.vue'
import welcome from '@/components/welcome.vue'
import admin from '@/components/admin.vue'
import menuAdmin from '@/components/menuAdmin.vue'
import roleAdmin from '@/components/roleAdmin.vue'

export default {
  name: 'home',
  components: {
    homeHeader,
    homeMenu,
    welcome,
    admin,
    menuAdmin,
    roleAdmin
  },
  data: function () {
    return {
      main_height:'',
      welcome_show:true,
      admin_show:false,
      menuAdmin_show:false,
      roleAdmin_show:false
    }
  },

  methods: {
    showMsgChild:function(data){
      if(data == '管理员列表')
      {
      this.admin_show = true;
      this.welcome_show = false;
      this.menuAdmin_show = false;
      this.roleAdmin_show = false;
      }
       if(data == '菜单管理')
      {
      this.menuAdmin_show = true;
      this.welcome_show = false;
      this.admin_show = false;
      this.roleAdmin_show = false;
      }
      if(data == '角色管理')
      {
      this.roleAdmin_show = true;
      this.welcome_show = false;
      this.admin_show = false;
      this.menuAdmin_show = false;
      }
    }
  },
  mounted(){
     //计算主页面高度
    var count = document.documentElement.clientHeight -53;
    this.main_height = 'height:'+count+'px';
  }
}
</script>
<style scoped>
.main{
    position: absolute;
    left: 200px;
    right: 0;
    /* width: 100%;
    height: 100%; */
}
</style>


