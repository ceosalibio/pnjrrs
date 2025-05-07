<template>
    <v-app id="inspire">
        <div class="main-page">
            <v-navigation-drawer
                v-model="drawer"
                app
                v-if="showAppBar"
            >
            <v-list-item-group v-model="selectedItem">
                <h2 class="s-header text-center" style="margin-bottom: 15px;">NAVIGATION</h2>
                <v-card  light v-for="(item, index) in navItems" :key="index" 
                dense style="border-radius: 0; border: .5x solid black; border: .5px #0080ff solid; background: linear-gradient(#ffffff, #eeeeee); font-size: 10px; cursor: default;">
                    <v-list-item 
                    v-if="item.subMenu == 0" 
                        link 
                        :to="item.to"
                        active-class="active"
                        >
                        <v-list-item-icon>
                            <v-icon >{{ item.icon }}</v-icon>
                        </v-list-item-icon>
    
                        <v-list-item-content>
                            <v-list-item-title>{{ item.text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>  
                    <!-- active-class="active" -->
                    <v-list-group v-else>
                        <template v-slot:activator>
                            <v-list-item-icon>
                                <v-icon light>{{ item.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>{{ item.text }}</v-list-item-title>
                        </template>
                        <v-list-item v-for="(item1, index) in item.subMenu" :key="index" link :to="item1.to" active-class="active">
                            <v-list-item-title v-text="item1.text"></v-list-item-title>
                        </v-list-item>
                    </v-list-group>
                </v-card>
            </v-list-item-group>
        </v-navigation-drawer>
  
        <v-app-bar app color="primary" dark v-if="showAppBar">
           <v-tooltip bottom >
              <template v-slot:activator="{on , attrs}">
                 <v-app-bar-nav-icon @click="drawer = !drawer" color="white" v-bind="attrs" v-on="on"></v-app-bar-nav-icon> 
              </template>
                 <span v-if="!drawer">Click to show navigation drawer</span>
                 <span v-else>Click to hide navigation drawer</span>
           </v-tooltip>
           
           <v-toolbar-title style="color:white;cursor:pointer;" @click="goHome()" >
              <span class="d-none d-md-block d-lg-block d-xl-block">PHILIPPINE NAVY</span>
              
           </v-toolbar-title>
           <v-spacer/>
           <v-img :src="`storage/img/${user.departmentcode}.png`" max-width="3%"  class="d-none d-md-block d-lg-block d-xl-block"/>
           <v-img :src="`storage/img/${user.departmentcode}.png`" max-width="5%"  class="d-sm-flex d-md-none"/>
           <span>{{user.name }}<br/>{{ user.offices == '1'?user.team:user.section }}</span>
           <v-tooltip bottom>
              <template v-slot:activator="{on , attrs}">
                 <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon @click="getLogout()">mdi-logout</v-icon>
                 </v-btn>  
              </template>
                 <span>Logout</span>
           </v-tooltip>
        
        </v-app-bar>
        
        <v-main class="main-content">
           <router-view></router-view>
        </v-main>
            <!-- <footer class="footer" v-if="$route.name != 'login'"> -->
               <v-footer :class="$route.name == 'login'?'footer-display':''">
                 <v-col class="text-center">
                     <v-icon small>mdi-copyright</v-icon>2023 - <strong>OFFICE OF AC OF NS FOR OPERATIONS,N3</strong><br /><span>All Rights Reserved</span>
                 </v-col>
               </v-footer>
             <!-- </footer> -->
        </div>
        
  
        <v-overlay :value="data.overlay">
           <v-progress-circular
              size="65"
           >
           </v-progress-circular>
        </v-overlay>
        
    </v-app>
  </template>
  
  <script>
      import moment from 'moment'
      import axios from 'axios'
      import { data } from '../details/data'
      import { mapState } from 'vuex'
      import {removeItem} from '../details/crytp'
      import axiosHttp from '../../axiosHttp'
     export default {
        data: () => ({
            users:'',
            selectedItem:1,
            drawer: true,
            items:[
               {subMenu:[],text:'Personnel', icon:'mdi-account-tie', to:'personnel'},
               {subMenu:[],text:'Training', icon:'mdi-book-open', to:'training'},
               {subMenu:[],text: 'Equipment', icon: 'mdi-tools', to : 'equipment'},
               {subMenu:[],text: 'Maintenance', icon: 'mdi-wrench-outline', to : 'maintenance'},
               {subMenu:[],text: 'Facilities', icon: 'mdi-office-building', to : 'facility'},
               {subMenu:[],
               text: 'Report', icon: 'mdi-note-outline',to : 'report'},
               {subMenu:[
                  {text: 'Check Report', icon: 'mdi-file-document-check', to : 'checkReport'},
                  // {text: 'Branch/Rank', icon: 'mdi-home', to : 'admin'},
                  {text: 'Update TO', icon: 'mdi-note-plus', to : 'personnelAdmin'},
                  {text: 'Update Training', icon: 'mdi-note-plus', to : 'trainingAdmin'},
                  {text: 'Update TE', icon: 'mdi-note-plus', to : 'equipmentAdmin'},
                  {text: 'Update Facility', icon: 'mdi-note-plus', to : 'facilityAdmin'},
                  {text: 'Update User', icon: 'mdi-note-plus', to : 'addUser'},
                  {text: 'Inventory', icon: 'mdi-list', to : 'inventory'},
                  // {text: 'Master', icon: 'mdi-list', to : 'master'},
               ],text: 'Admin', icon: 'mdi-shield-crown'},
               
            ],
            data: data,
            showFooter:true
        }),
        methods:{
           goHome(){
              if(this.$route.name != 'home'){
                 this.$router.push('/home')
              }
           },
           getLogout(){
              const confirmed = window.confirm('Are you sure you want to logout?');
              if(confirmed){
                 this.overlay = true
                 axiosHttp.post(`logout`,{
                     log : moment().format('YYYY-MM-DD HH:mm:ss')
                 }).then(res=>{
                    if(res.status == 200){
                        removeItem('user')
                        removeItem('token')
                        this.overlay = false
                        this.$router.push('/login')
                    }
                 })
              }
              
              
           },
           handleScroll() {
              // Calculate the distance between the bottom of the page and the bottom of the viewport
              const distanceToBottom = document.documentElement.scrollHeight - window.innerHeight - window.pageYOffset;
              // console.log(distanceToBottom)
              // If the distance to the bottom is less than or equal to 0, hide the footer, otherwise show it
              this.showFooter = distanceToBottom > 1;
           }
        },
        computed:{
          ...mapState([
            'user'
          ]),
           getImageUrl() {
              const baseUrl = process.env.BASE_URL || '';
              const imagePath = `/images/${this.$store.state.user.main}.png`;
              return `${baseUrl}${imagePath}`;
           },
           navItems(){
              let item 
              if(this.user.rights != '1' && this.user.hpn != '1'){
                  item = this.items.slice(0, -1);
              }else{
                 if( this.user.hpn && this.user.restrict == '8'){
                    item = this.items.map(res=>{
                       res.subMenu = res.subMenu.filter(r => r.text == 'Update Training' || r.text == 'Check Report');
                       return res; // Return the modified subMenu
                    });
                 }else if( this.user.hpn && this.user.restrict == '4'){
                    item = this.items.map(res=>{
                       res.subMenu = res.subMenu.filter(r => r.text == 'Update TE' || r.text == 'Update Facility' || r.text == 'Inventory' || r.text == 'Check Report');
                       return res; // Return the modified subMenu
                    });
                 
                 }else if(this.user.hpn && this.user.restrict == '1'){
                       item = this.items.map(res=>{
                          res.subMenu = res.subMenu.filter(r => r.text == 'Update TO' || r.text == 'Inventory' || r.text == 'Check Report');
                          return res; // Return the modified subMenu
                       });
                 
                 }else if(this.user.hpn && this.user.restrict && this.user.restrict != '3'){
                          item = this.items.map(res=>{
                             res.subMenu = res.subMenu.filter(r => r.text == 'Inventory');
                             return res; // Return the modified subMenu
                          });
                 }
                 else{
                    if(this.user.username == 'ceo'){
                       item = this.items
                    }else if(this.user.hpn && this.user.restrict == '3'){
                       item = this.items.map(res=>{
                             res.subMenu = res.subMenu.filter(r => r.text != 'Master');
                             return res; // Return the modified subMenu
                          });
                    }else{
                     item = this.items.slice(0, -1);
                    }
                    
                 }
                  
              }
              return item
           },
           showAppBar(){
              return this.$route.name != 'login'
           },
  
        },
        created(){
            // this.users = this.user
         //   setTimeout(() => {

         //   }, timeout);
        }
     }
  </script>
  <style>
  .footer-display {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 1;
 }

   </style>
  