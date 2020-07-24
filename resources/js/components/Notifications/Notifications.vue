<template>
<div class="notifications">
    <b-loading :is-full-page="isFullPage" :active.sync="isLoading" :can-cancel="true"></b-loading>

   
    <ul v-if="session">
        <div> 
            <b-button type="is-primary" outlined
            @click="openModalSendNotification"
            >Create Notification
            </b-button>
        </div>
        <b-field label="" style="float: right; margin-top: -35px; width: 355px" >
           <b-input v-model="search" placeholder="Search by Title..."
                type="search"
                @input="onKeyup">
            </b-input>
        </b-field>
        <h4 class="h4 border-bottom p-1" style="margin-top:15px">Notifications</h4> 
        
        <li  v-for="item in notifications">
            <div class="main">
                
                 <b-notification has-icon type='is-info' aria-close-label="Close notification">
                   Status : {{ item.status }}  <br>
                   Title : {{ item.title }} <br>
                   Body : {{ item.body }} <br>
                   Created at : {{ item.created_at }} <br>
                   Update at : {{ item.updated_at }}
                 </b-notification> 
                   
            </div>
        </li>
    </ul>

    <b-modal :active.sync="isComponentModalActiveCreateNotification"
            has-modal-card
            @close="closeCreateNotificationForm"
             :can-cancel="['escape', 'x']">
            <div class="modal-card" style="width: 70, height: auto">
                <header class="modal-card-head">
                    <p class="modal-card-title">Create New Notification</p>
                </header>
                <section class="modal-card-body">
                    <b-field label="Title">
                        <b-input
                            type="text"
                            v-model="title"
                            placeholder="Notification Title"
                            required>
                        </b-input>
                    </b-field>


                     <b-field label="Body">
                        <b-input
                           placeholder="Notification Body" 
                           maxlength="200"
                           type="textarea"
                           v-model="body"
                           required>
                        </b-input>
                    </b-field>

                    <div class="field">
                        <b-switch @input="changeSchedule" v-model="isSwitched">
                         Schedule Notification
                        </b-switch>
                    </div>

                    <b-field v-show = isSwitched label="Select Date and Time">
                        <b-datetimepicker
                            rounded
                            placeholder="Click to select..."
                            icon="calendar-today"
                            v-model="datetime"
                            :datepicker="{ showWeekNumber }"
                            :timepicker="{ enableSeconds, hourFormat: format }"
                            horizontal-time-picker>
                        </b-datetimepicker>
                    </b-field>

                </section>
                <footer class="modal-card-foot">
                    <b-button @click="closeCreateNotificationForm">Cancel</b-button>
                    <b-button @click="sendNotification">Send</b-button>
                </footer>
            </div>
        </b-modal>
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

        session: {
            get() {
               
                let result = this.$store.state.sessions.find(session => session.id === this.$store.state.currentSessionId )
               return result;

            }, set() {}
        },

        filterNotification: {
            get() {
                return (typeof this.$store.state.filterNotification !== null) ?  this.$store.state.filterNotification : {};
            }, set() {}
        },

        notifications: {
            get() {
                this.isLoading = true;
                if (this.session.notifications) {
                    let result = this.session.notifications.filter(notifications => notifications.title.includes(this.$store.state.filterNotification));  
                    this.isLoading = false;
                    return result;
                }
                else {
                     this.isLoading = false;

                }
            
            }, set() {}
        },

        format() {
            return this.formatAmPm ? '12' : '24'
        }

    },

    filters: {
       
    },

    created() {
      
         
    },

    data(){
        return {
            isComponentModalActiveCreateNotification:false,
            title:'',
            body:'',
            type:'',
            datetime: null,
            isLoading: false,
            isFullPage: true,
            showWeekNumber: false,
            formatAmPm: false,
            enableSeconds: false,
            isSwitched: false,
            search: ''
        }
        
    },

    methods: {

    scrollToEnd() {
        var container = document.querySelector(".notifications");
        var scrollHeight = container.scrollHeight;
        container.scrollTop = scrollHeight;
    },

    closeCreateNotificationForm() {
        this.isComponentModalActiveCreateNotification = false;
        this.cleanNotificationForm();
    },

    cleanNotificationForm() {
        this.title = '',
        this.body = '',
        this.type = '',
        this.datetime = null

    },

    changeSchedule() {
        this.isSwitched = this.isSwitched
        this.datetime = null;
    },

    onKeyup (e) {
        this.$store.dispatch('searchNotification', this.search);
    },

    sendNotification() {

        if(this.title  && this.body && !this.datetime && this.isSwitched == false) {
            axios.post('/api/notification' , {
           'user_id' : this.session.id,
           'title' : this.title,
           'body' : this.body,
           'schedule': -1
        
        }).then( resp => {
            this.isLoading = true;
            this.isComponentModalActiveCreateNotification = false;
            this.cleanNotificationForm();
            this.successSend();
            this.$store.dispatch('initData');
            this.isLoading = false;
            
        }).catch(error => {
            this.errorSendingNotification();
            console.log(error);
        });
        }  else {

            if (this.title &&  this.body && this.datetime) {
                 axios.post('/api/notification' , {
                    'user_id' : this.session.id,
                    'title' : this.title,
                    'body' : this.body,
                    'schedule': Math.round(((this.datetime.getTime() - (new Date()).getTime())/1000)/60) 

                    }).then( resp => {
                        this.isLoading = true;
                        this.isComponentModalActiveCreateNotification = false;
                        this.cleanNotificationForm();
                        this.successSchedule();
                        this.$store.dispatch('initData');
                        this.isLoading = false;
                        
                    }).catch(error => {
                        this.errorScheduleNotification();
                        console.log(error);
                    });
            } else {
               this.errorEmptyField();
            }
        }
    },
    
    openModalSendNotification() {
        this.isComponentModalActiveCreateNotification = true;
    },

    successSend() {
        this.$buefy.notification.open({
            message: 'Notification Sent Successfully',
            type: 'is-success'
        })
    },

    successSchedule() {
        this.$buefy.notification.open({
            message: 'Notification Scheduled Successfully',
            type: 'is-success'
        })
    },

    errorSendingNotification() {
        const notif = this.$buefy.notification.open({
            duration: 5000,
            message: `Upss! Notification not sent`,
            position: 'is-bottom-right',
            type: 'is-danger',
            hasIcon: true
        })
        notif.$on('close', () => {
            this.$buefy.notification.open('Custom notification closed!')
        })
    },

    errorScheduleNotification() {
    const notif = this.$buefy.notification.open({
        duration: 5000,
        message: `Upss! Notification not scheduled`,
        position: 'is-bottom-right',
        type: 'is-danger',
        hasIcon: true
    })
        notif.$on('close', () => {
            this.$buefy.notification.open('Custom notification closed!')
        })
    },

    errorEmptyField() {
    const notif = this.$buefy.notification.open({
        duration: 5000,
        message: `One or more fields are empty!`,
        position: 'is-top-right',
        type: 'is-danger',
        hasIcon: true
    })
        notif.$on('close', () => {
            this.$buefy.notification.open('Custom notification closed!')
        })
    }

    },
    
    mounted() {
        //this.scrollToEnd();

    },

    updated() {
       // this.scrollToEnd();
    },

    

};
</script>



<style lang="less" scoped>

.notifications {
    padding: 10px 15px;
    overflow-y: scroll;
   
    li {
        margin-bottom: 15px;
        background-color: rgb(170, 168, 165);
        border-radius: 15px;

    }
    .time {
        margin: 7px 0;
        text-align: center;

        > span {
            display: inline-block;
            padding: 0 18px;
            font-size: 12px;
            color: #fff;
            border-radius: 2px;
            background-color: #dcdcdc;
        }
    }
    .avatar {
        float: left;
        margin: 0 10px 0 0;
        border-radius: 3px;
    }
    .text {
        display: inline-block;
        position: relative;
        padding: 0 10px;
        max-width: ~"calc(100% - 40%)";
        min-height: 30px;
        line-height: 2.5;
        font-size: 12px;
        text-align: left;
        word-break: break-all;
        background-color: #fafafa;
        border-radius: 4px;

        &:before {
            content: " ";
            position: absolute;
            top: 9px;
            right: 100%;
            border: 6px solid transparent;
            border-right-color: #fafafa;
        }
    }

    .self {
        text-align: right;

        .avatar {
            float: right;
            margin: 0 0 0 10px;
        }
        .text {
            background-color: #b2e281;

            &:before {
                right: inherit;
                left: 100%;
                border-right-color: transparent;
                border-left-color: #b2e281;
            }
        }
    }
}
</style>