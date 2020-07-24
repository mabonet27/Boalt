<template>
<div class="list">
    <ul>
        <li v-for="item in sessions" :class="{ active: item.id == currentId }" @click="selectSession(item.id)">
            <img class="avatar"  width="30" height="30"  :src="item.img">
            <p class="name">{{item.name}}</p>
        </li>
    </ul>
</div>
</template>

<script>

export default {
 
    computed: {

        sessions: {
            get() {
                
                let result = this.$store.state.sessions.filter(session => session.name.includes(this.$store.state.filterKey));  
                return result;

            }, set() {}
        },

        currentId: {
            get() {
        
              if(typeof this.sessions !== 'undefined' && this.sessions > 0) {
                this.$store.dispatch('selectSession' , this.sessions[0].id )
             
              }
              return this.$store.state.currentSessionId;
                
            }, set() {}
        },

    },
    methods: {
        
        selectSession(sessionId) { 
            this.$store.dispatch('selectSession' , sessionId )
        }
    },
};
</script>



<style scoped lang="less">
.list {
    
    overflow-y: scroll;
    li {
        padding: 12px 15px;
        border-bottom: 1px solid #e6e4e4b8;
        background-color: #e6e4e4b8;
        cursor: pointer;
        transition: background-color .1s;

        
        &.active {
            background-color:#c0c2c3;
        }
    }
    .avatar, .name {
        vertical-align: middle;
    }
    .avatar {
        border-radius: 2px;
    }
    .name {
        display: inline-block;
        margin: 0 0 0 15px;
        color: rgb(104, 103, 103);
    }
}
</style>