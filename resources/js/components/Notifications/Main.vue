<template>
  <div class="app">
    <div class="sidebar">
      <CardComponent></CardComponent>
      <ListComponent></ListComponent>
    </div>
    <div class="main">
     <NotificationsComponent></NotificationsComponent>
     
    </div>
  </div>
</template>

<script>


// @ is an alias to /src
import CardComponent from "./Header.vue";
import ListComponent from "./Users.vue";
import NotificationsComponent from "./Notifications.vue";



export default {
  name: "Home",
  components: {
     CardComponent,
     ListComponent,
     NotificationsComponent
  },
 
  created() {   

     this.$store.dispatch('initData', this.user ).then(response => {  
			}).catch(error => {
				console.log(error.response.data.message);
			}); 
  }
};
</script>

<style lang="less" scoped>
.app {
  margin: 0px auto;
  width: 100%;
  height: 800px;

  overflow: hidden;
  //border-radius: 3px;

  .sidebar, .main {
        height: 100%;
    }
    .sidebar {
        float: left;
        width: 30%;
        color: #f4f4f4;
        background-color: #e6e4e4b8;
    }
  .main {
    position: relative;
    overflow: hidden;
    background-color: #eee;
  }
  .text {
    position: relative;
    width: 100%;
    bottom: 0;
    left: 0;
  }
  .notifications {
    height: 810px;
  }
  .list {
    height: 810px;
  }
}
</style>
