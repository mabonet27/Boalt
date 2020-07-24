<template>
<div class="card">
    <header>
        <!-- <img class="avatar" width="40" height="40" :alt="user.name" :src="user.img"> -->
        <h4 class="h4 p-1 border-bottom">Users</h4>
    </header>
    <footer>
        
         <b-field label="">
           <b-input v-model="search" placeholder="Search User..."
                type="search"
                @input="onKeyup">
            </b-input>
        </b-field>
      
    </footer>
</div>
</template>


<script>

export default {
    computed: {
       
        user: {
            get() {
                return (typeof this.$store.state.user !== null) ?  this.$store.state.user : {};
            }, set() {}
        },

        filterKey: {
            get() {
                return (typeof this.$store.state.filterKey !== null) ?  this.$store.state.filterKey : {};
            }, set() {}
        },

    },
    methods: {
        onKeyup (e) {
            this.$store.dispatch('search', this.search);
        }
    },
    data () {
      return {
          search: '',
          
      }
    }
};
</script>



<style scoped lang="less">
.card {
    padding: 5px;
    border-bottom: solid 1px #e6e4e4b8;
    background-color: #e6e4e4b8;

    footer {
        margin-top: 10px;
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
        font-size: 16px;
        color: rgb(104, 103, 103);
    }
    .search {
        padding: 0 10px;
        width: 100%;
        font-size: 12px;
        color: rgb(104, 103, 103);
        height: 30px;
        line-height: 30px;
        border: solid 1px #3a3a3a;
        border-radius: 4px;
        outline: none;
        background-color: #dcdcdc;
    }
}
</style>