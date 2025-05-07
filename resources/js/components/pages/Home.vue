<template>
    <v-container fluid class="container" >
        <!-- <div>{{ user }}</div> -->
      <!-- <h1>SAMPLE IMAGE HERE</h1> -->
      <div class="d-none d-md-block d-lg-block d-xl-block" style="height: 80vh;width: 100%;">
        <v-img :src="image[0].val" style="width: 100%; height: 100%; object-fit: contain;"></v-img>
      </div>
      <div class="d-sm-flex d-md-none">
        <v-img :src="image[1].val"/>
      </div>
     
    
     
    </v-container>
  </template>
  
  <script>
    import axios from 'axios';
    import moment from 'moment'
    import image1 from '../../../../public/images/home.png';
    import image5 from '../../../../public/images/D01.png';
    import {removeItem} from '../details/crytp'
   import { mapState } from 'vuex';
    export default {
      name: 'HomeView',
      data() {
        return {
          image:[
            {val:image1},
            {val:image5},
          ],
          isAuthenticated: false,
          inactivityTimeout: null,
        }
      },
      created() {
      },
      methods: {
        login() {
          this.isAuthenticated = true;
          this.startInactivityTimer();
        },
        logout() {
          this.clearInactivityTimeout();
          this.isAuthenticated = false;
          this.getLogout()
          // Other logout actions
        },
        getLogout(){
              axios({
                 method:'post',
                 url:`api/logout`,
                 data :{
                    id  : user.id,
                    log : moment().format('YYYY-MM-DD HH:mm:ss')
                 }
              }).then(res=>{
                 if(res.status == 200){
                    removeItem('user')
                    // this.$store.dispatch('logout')
                    this.hideLogin = false
                    this.$router.push('/login')
                 }
              })
              
           },
        startInactivityTimer() {
          const inactivityTime = 600000; // 10 minutes (adjust as needed)
          this.inactivityTimeout = setTimeout(() => {
            this.logout(); // Automatically logout after inactivityTime
          }, inactivityTime);
  
          const resetTimer = () => {
            clearTimeout(this.inactivityTimeout);
            this.inactivityTimeout = setTimeout(() => {
              this.logout(); // Reset the timer and logout after inactivityTime
            }, inactivityTime);
          };
  
          const resetTimerOnActivity = () => {
            resetTimer();
            window.addEventListener('mousemove', resetTimer);
            window.addEventListener('keydown', resetTimer);
            // Add more event listeners for other user activities
          };
  
          resetTimerOnActivity();
  
          // Clear event listeners when the component is destroyed
          window.addEventListener('beforeunload', () => {
            window.removeEventListener('mousemove', resetTimer);
            window.removeEventListener('keydown', resetTimer);
            // Remove other event listeners if added
          });
        },
        clearInactivityTimeout() {
          clearTimeout(this.inactivityTimeout);
          this.inactivityTimeout = null;
        },
    },
    computed: {
        ...mapState([
            'user',
            'hideLogin'
        ])
    },
    mounted(){
        console.log(this.user)
    }
  
    }
  </script>
  <style scoped>
  .container {
    opacity: 0.9;
    position: relative;
    background-size: cover;
  }
  </style>
  