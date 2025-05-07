<template>
    <div >
      <div v-if="hideLogin">
        <br>
        <!-- wide view -->
        <v-card class="scroll-card d-none  d-md-block d-lg-block d-xl-block" height="100vh">
            <div>
                <v-btn icon><v-icon color="primary" x-large @click="startLog(false)">mdi-arrow-left</v-icon></v-btn>
                <h1 style="font-size: 70px;font-weight: bold;"><center>PHILIPPINE NAVY</center></h1>
        
                <div v-for="(rowItems, rowIndex) in groupedDepartment" :key="rowIndex" class="row-container">
                  <div class="title">
                    <span>{{ rowItems[0].title.toUpperCase() }}</span>
                    
                  </div>
                  <div class="images">
                    <v-row class="d-flex justify-center">
                      <v-col v-for="(item, i) in rowItems" :key="i" cols="12" sm="1" md="1" lg="1" xl="1">
                        <center>
                          <v-img :src="item.path"  
                            max-width="100%" max-height="100%" 
                            @click="openDialog(item)" 
                            transition="slide-x-reverse-transition"
                            style="cursor:pointer;"
                            class="image-container"
                          />
                          <span style="font-size: 10px;font-weight: bold;" class="text-container">{{ item.name.toUpperCase() }}</span>
                        </center>
                      </v-col>
                    </v-row>
                  </div>
                </div>
              </div>
        </v-card>
        
        <!-- small view -->
        <div class="d-sm-none">
          <v-row>
            <v-col cols="12">
              <v-btn icon><v-icon color="primary" x-large @click="startLog(false)">mdi-arrow-left</v-icon></v-btn>
        
            <div class="text-center" >
              <h1 style="font-size: 40px;font-weight: bold;" >PHILIPPINE NAVY</h1>
            </div>
          <br>
            <div >
              <v-autocomplete  
                outlined
                label="Select Unit"
                :items="this.department"
                item-text="type"
                item-value="row"
                v-model="departmentMobile"
                @change="selectDepartment()"
                class="text-center"
                clearable
                block
              />
            </div>
            </v-col>
          </v-row>
          <br>
          <!-- <v-row> -->
            <div v-for="(item, i) in department" :key="i">
              <v-row  v-if="item.row == departmentMobile">
                <v-col  cols="12">
                
                    <center>
                      <v-img :src="item.path"  
                        @click="openDialog(item)" 
                        transition="slide-x-reverse-transition"
                        style="cursor:pointer;"
                        max-height="100%"
                        max-width="100%"
                      />
                      <span style="font-size: 20px;font-weight: bold;" class="text-container">{{ item.name }}</span>
                    </center>
              
                  
                </v-col>
              </v-row>
            </div>
          <!-- </v-row> -->
          
            
       
  
        </div>
      </div> 
      
      <v-dialog width="500px" v-model="dialog" >
        <div v-if="!captcha">
          <v-card>
            <br>
            <center>
              <v-img :src="logo" max-width="70%" max-height="70%"/>
            
            <h3>{{ logoName }}</h3>
            </center>
            <v-form ref="Login" @submit.prevent="Login" lazy-validation>
              <v-card-text class="d-flex flex-column">
                    <v-text-field 
                    dense
                    outlined
                    v-model="username"
                    :rules="[rules.required]"
                    label="Username"
                    class="text-center"
                    maxlength="10"
                  />
                  <v-text-field 
                    dense
                    outlined
                    v-model="password"
                    label="Password"
                    :append-icon="show?'mdi-eye':'mdi-eye-off'"
                    :type="show?'text':'password'"
                    :rules="[rules.required]"
                    @click:append ="show = !show"
                    name="input-10-1"
                    class="text-center"
                    maxlength="12"
                    @input="passCheck()"
                  />
              </v-card-text>
             
              <v-card-actions class="d-flex flex-column">
                <v-btn block  color="primary" type="submit" :disabled="logBtnRules?false:true" :loading="loading">Login</v-btn>
             
           </v-card-actions>
            </v-form>
          </v-card> 
        </div>
        <div class="captchaContainer" v-else>
          <v-card >
            <v-card-text>
              <h3>Prove you are human.</h3>
              <div class="captcha">
                <template v-for="(char, index) in captchaChars">
                  <span class="captcha-char" :key="index"
                      :style="{ fontSize: char.fontSize + 'px', transform: 'rotate(' + char.rotation + 'deg)' }">
                      {{ char.char }}
                  </span>
                </template>
              </div>
              <div>
                <br>
                <v-text-field 
                  dense
                  outlined
                  label="Text"
                  placeholder="Type the text"
                  v-model="captchaCode"
                  class="text-center"
                  maxlength="6"
                />
              </div>
            </v-card-text>
            <v-card-actions>
              <v-row>
                <v-col cols="12">
                  <v-btn @click="generateCaptcha" block>Generate Text</v-btn>
                </v-col>
                <v-col cols="12">
                  <v-btn  block color="success"  @click="validateCaptcha()">Submit</v-btn>
                </v-col>
              </v-row>
            </v-card-actions>

            <v-overlay :value="data.overlay" absolute>
              <v-progress-circular
                 size="65"
              >
              </v-progress-circular>
           </v-overlay>

          </v-card>
        </div>
  
        
      </v-dialog>
  
      <div v-if="!hideLogin">
        <center>
          
            <!-- wide view -->
            <div class="d-none d-md-block d-lg-block d-xl-block">
              <v-row >
                <v-col cols="12"  >
                  <!-- <v-img :src="navyLogo"/> -->
                  <v-img src="storage/img/welcome.png" style="width: 100%; height: 100vh; object-fit: cover; position: absolute; top: 0; left: 0;" />
                  <div style="position: absolute; top: 60%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white;">
                    <span style="color: white;font-size:45px;margin-top:-30px;">
                      <span class="typed-text">{{ typeValue }}</span>
                      <span class="blinking-cursor">|</span>
                      <span class="cursor" :class="{ typing: typeStatus }">&nbsp;</span>
                    </span><br>
                    <v-hover>
                      <template v-slot="{ hover }">
                        <v-btn
                          width="100%"
                          height="50%"
                          x-large
                          color="primary"
                          style="font-size: 24px;"
                          :class="{ 'elevate': hover }"
                          @click="startLog(true)"
                        >
                          GET STARTED
                          <v-icon v-if="hover">mdi-arrow-right</v-icon>
                        </v-btn>
                      </template>
                    </v-hover>
                  </div>
                </v-col>
              </v-row>
            </div>
  
            <!-- small view -->
          <v-card width="50%" style="top: 100px" height="100%" rounded flat>
            <div class="d-sm-flex d-md-none" style="height:50vh;">
              <v-row >
                <v-col cols="12">
                  <v-img src="storage/img/navyLogo.png"/>
                  <!-- <v-img  src="img/welcome.png"/> -->
                  
                </v-col>
                <v-col cols="12" style="text-align:center;">
                  <h2 style="font-size:25px;margin: 0;">Philippine Navy</h2>
                  <h2 style="font-size:25px;margin: 0;">Joint Readiness </h2>
                  <h2 style="font-size:20px;margin: 0;">Reporting System</h2>
                  <span style="color: #466ded;font-size:15px;margin-top:-30px;">
                    <span class="typed-text">{{ typeValue }}</span>
                    <span class="blinking-cursor">|</span>
                    <span class="cursor" :class="{ typing: typeStatus }">&nbsp;</span>
                </span><br>
                <v-hover>
                  <template v-slot="{ hover }">
                    <v-btn
                     
                      medium
                      color="primary"
                      style="font-size: 15px;"
                      :class="{ 'elevate': hover }"
                      @click="startLog(true)"
                    >
                      GET STARTED
                      <v-icon v-if="hover">mdi-arrow-right</v-icon>
                    </v-btn>
                  </template>
                </v-hover>
                </v-col>
              </v-row>
            </div>
          </v-card>
        <!-- <Register ref="register" /> -->
          </center>
      </div>
      
    </div>
    </template>
  <script>
  import axios from 'axios';
  import { mapState } from 'vuex';
  import { unitFilter } from '../details/unitFilter';
  import { list } from '../details/list';
  import { data } from '../details/data';
  import { decryptBase , setItem, getItem } from '../details/crytp'
  import axiosHttp from '../../axiosHttp'
  import moment from 'moment';
    export default {
      name:'LoginComponent',
        data:()=>({
            dialog:false,
            images:[],
            logo:null,
            logoName:null,
            logoCode:null,
            show:false,
            rules:{
              required: value => !!value || 'Required.',
              userMatch:()=>(`The email and password you entered don't match`)
            },
            password:null,
            username:null,
            logBtnRules:false,
            loading:false,
            log:false,
  
            typeValue: "",
            typeStatus: false,
            displayTextArray: [ "PHILIPPINE NAVY","Protecting the Seas", "Securing our Future"],
            typingSpeed: 100,
            erasingSpeed: 100,
            newTextDelay: 2000,
            displayTextArrayIndex: 0,
            charIndex: 0,
            captchaChars: [],
            characters: '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            captcha:false,
            combinedWord:null,
            logObj:{},
            token : '',
            captchaCode:null,
            inLog:false,
  
            // mobile model
            departmentMobile:null,
            data:data
            
           
    
        }),
        methods:{
          generateCaptcha() {
            const chars = this.characters.split('');
            let captchaChars = [];
            for (let i = 0; i < 6; i++) {
                let char = chars[Math.floor(Math.random() * chars.length)];
                let fontSize = Math.floor(Math.random() * 10) + 30; // random font size between 20 and 30
                let rotation = Math.floor(Math.random() * 30) - 10; // random rotation between -10 and 10 degrees
                captchaChars.push({ char, fontSize, rotation });
              }
            this.captchaChars = captchaChars;
            this.combinedWord = this.captchaChars.map(item => item.char).join('');
     
          },
     
          typeText() {
              if (
                  this.charIndex <
                  this.displayTextArray[this.displayTextArrayIndex].length
              ) {
                  if (!this.typeStatus) this.typeStatus = true;
                  this.typeValue += this.displayTextArray[
                      this.displayTextArrayIndex
                  ].charAt(this.charIndex);
                  this.charIndex += 1;
                  setTimeout(this.typeText, this.typingSpeed);
              } else {
                  this.typeStatus = false;
                  setTimeout(this.eraseText, this.newTextDelay);
              }
          },
          eraseText() {
              if (this.charIndex > 0) {
                  if (!this.typeStatus) this.typeStatus = true;
                  this.typeValue = this.displayTextArray[
                      this.displayTextArrayIndex
                  ].substring(0, this.charIndex - 1);
                  this.charIndex -= 1;
                  setTimeout(this.eraseText, this.erasingSpeed);
              } else {
                  this.typeStatus = false;
                  this.displayTextArrayIndex += 1;
                  if (this.displayTextArrayIndex >= this.displayTextArray.length)
                      this.displayTextArrayIndex = 0;
                  setTimeout(this.typeText, this.typingSpeed + 1000);
              }
          },
          startLog(val){
            this.$store.state.hideLogin = val
            if(!val){
              this.captcha = false
            }
          },
          loadImage() {
            this.images = []
            for (let i = 1; i <= 24; i++) {
              let obj = {
                name: i < 10 ? `D0${i}` : `D${i}`,
                path: i < 10 ? `storage/img/D0${i}.png` : `storage/img/D${i}.png`
              };
              this.images.push(obj);
            }
            // console.log(this.department);
          },
          getImageDataUrl(imageSrc, callback) {
            const img = new Image();
            img.crossOrigin = "Anonymous";
            img.onload = () => {
              const canvas = document.createElement("canvas");
              canvas.width = img.width;
              canvas.height = img.height;
              const ctx = canvas.getContext("2d");
              ctx.drawImage(img, 0, 0);
              const dataUrl = canvas.toDataURL("image/png");
              callback(dataUrl);
            };
            img.src = imageSrc;
          },
           openDialog(item){
            this.logo = item.path
            this.logoName = item.name
            this.logoCode = item.code
            this.dialog = true
            // console.log(item)
            // console.log(this.department)
           },
           passCheck(){
            if(this.username&& this.password){
                this.logBtnRules = true
              }else{
                this.logBtnRules = false
              }
           },

           Login(){
              if(this.$refs.Login.validate()){
                // this.loading = true
                axios({
                  method:'post',
                  url:`api/getLogin`,
                  data:{
                    username : this.username,
                    password : this.password,
                    logoCode : this.logoCode
                  }
                }).then(res=>{
                  if(res.data == 1){
                    alert('You cannot log in to this unit')
                  }else if(res.data == 2){
                    alert('Username and Password are not match')
                  }else{
                    let data = decryptBase(res.data.user)
                    // console.log(data,'user',res.data.token,'token')
                    this.logObj = data
                    this.token = res.data.token
                    this.captcha = true
                  }
                })     
              }
              
           },
           validateCaptcha(){
            data.overlay = true
            axios({
              method:'post',
              url:`api/validateCaptcha`,
  
              data:{
                captchaData : this.combinedWord,
                validateData : this.captchaCode
              }
            }).then(res=>{
                let item = res.data
                if(item.success == true){
                  axiosHttp.post('insertLog',{
                    id : this.logObj.id,
                    log : moment().format('YYYY-MM-DD HH:mm:ss')
                  }).then(()=>{
                    setItem('user',this.logObj)
                    setItem('token',this.token)
                    
                    setTimeout(() => {
                      location.reload()
                    }, 1000);
                    this.$router.push("/home");
                  }).catch(err=>{
                    console.error(err)
                  })
                }else{
                  alert('You enter wrong text')
                  data.overlay = false
                }
            })
           },
  
          //  mobile methods
          selectDepartment(){
            console.log(this.departmentMobile)
          }
  
        },
        computed:{
          ...mapState([
            'hideLogin'
          ]),
          department(){
            let data = []
            
           
            this.images.forEach((image,i) => {
              for (const item of list.departmentList) {
                  if(item.code == image.name){
                    let obj = {
                      row:null,
                      type: null,
                      path : null,
                      code : null,
                      name : null
                    }
  
                      if(i == 0){
                        obj.type = 'HPN & HSG'
                        obj.row = 1
                      }else if(i >= 1 && i <= 2){
                        obj.type = 'TYPE COMMANDS'
                        obj.row = 2
                      }else if(i >= 3 && i <= 9){
                        obj.type = 'COMBAT FORCES'
                        obj.row = 3
                      }else if(i >= 10 && i <= 16){
                        obj.type = 'COMBAT SUPPORT FORCES'
                        obj.row = 4
                      }else if(i >= 17 && i <= 22){
                        obj.type = 'GENERAL SUPPORT AND SUSTAINMENT FORCES'
                        obj.row = 5
                      }else if(i == 23){
                        obj.type = 'RESERVE FORCES'
                        obj.row = 6
                      }
                      obj.path = image.path
                      obj.code = image.name
                      obj.name = item.name
                      data.push(obj)
                  }
                  
              }
            });
            return data.reduce((total, item)=>{
              let i = total.findIndex(res=>res.type == item.type)
              if(i == -1){
                item.title = item.type
                total.push(item)
              }else{
                total[i].title = item.type
                total.push(item)
              }
              return total
            },[])
            
          },
          groupedDepartment() {
            const grouped = {};
            this.department.forEach((item) => {
              if (!(item.row in grouped)) {
                grouped[item.row] = [];
              }
              grouped[item.row].push(item);
            });
            return Object.values(grouped);
          }
    
         
        },
        watch:{
          dialog(val){
            if(!val){
              if(this.$refs.Login){
                this.$refs.Login.resetValidation();
                this.$refs.Login.reset();
              }
              this.captcha = false
            }
          },
          // inLog(val){
  
          //   if(val){
          //       this.$store.dispatch('getLogin',this.logObj)
          //       this.$router.push("/home");
          //   }
          // }
         
        },
        components:{
         
        },
        created(){
        unitFilter.getDepartment()
          this.loadImage();
          setTimeout(this.typeText, this.newTextDelay + 200);
        },
        mounted(){
          
          this.generateCaptcha();
          this.loadImage();
  
            const self = this;
  
            this.images.forEach(image => {
              this.getImageDataUrl(image.path, (dataUrl) => {
                self.images = self.images.map(img => img.path === image.path ? { ...img, dataUrl } : img);
                // Update the images array with the converted dataUrl
              });
          });
          // console.log(this.images)
        },
   
    }
  </script>
    
    <style scoped>
    .text-center .v-text-field__slot input {
      text-align: center;
    }
    .image-container {
  
    transition: transform 0.3s ease;
  }
  
  .image-container:hover {
    transform: scale(1.1); /* Adjust the scaling factor as needed */
  }
  .text-container {
  
    transition: transform 0.3s ease;
  }
  
  .text-container:hover {
    font-size: 1.2em; /* Adjust the font size as needed */
  }
  
  .elevate {
    box-shadow: 0px 3px 5px -1px rgba(0,0,0,0.2), 0px 6px 10px 0px rgba(0,0,0,0.14), 0px 1px 18px 0px rgba(0,0,0,0.12);
  }
  .row-container {
    text-align: center;
    margin-bottom: 20px; /* Adjust as needed */
  }
  
  .title {
    font-size: 10px;
    font-weight: bold;
    margin-bottom: 5px; /* Adjust as needed */
  }
  .blinking-cursor {
    font-size: 3rem;
    color: #2c3e50;
    -webkit-animation: 1s blink step-end infinite;
    -moz-animation: 1s blink step-end infinite;
    -ms-animation: 1s blink step-end infinite;
    -o-animation: 1s blink step-end infinite;
    animation: 1s blink step-end infinite;
  }
  
  
  @keyframes blink {
    from,
    to {
        color: transparent;
    }
    50% {
        color: #2c3e50;
    }
  }
  @-moz-keyframes blink {
    from,
    to {
        color: transparent;
    }
    50% {
        color: #2c3e50;
    }
  }
  @-webkit-keyframes blink {
    from,
    to {
        color: transparent;
    }
    50% {
        color: #2c3e50;
    }
  }
  @-ms-keyframes blink {
    from,
    to {
        color: transparent;
    }
    50% {
        color: #2c3e50;
    }
  }
  @-o-keyframes blink {
    from,
    to {
        color: transparent;
    }
    50% {
        color: #2c3e50;
    }
  }
  
  
  .captcha {
    text-align: center;
    background-color: #f0f0f0; /* Background color */
    padding: 30px; /* Add padding for better spacing */
    border-radius: 8px; /* Optional: add rounded corners */
  }
  
  .captcha-char {
    display: inline-block;
    font-size:40px;
    margin: 0 5px; /* Optional: adjust margin for spacing between characters */
    font-family: 'Comic Sans MS', cursive; /* Use a playful font */
    color: #f02828; /* Set a cartoonish color */
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.3)); /* Add shadow for depth */
    background: radial-gradient(circle, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.8) 100%); /* Add gradient for shine effect */
  }
  
  /* Add wavy effect to the background */
  .captcha::before {
    content: "";
    position: absolute;
    background-image: linear-gradient(to right, #f0f0f0 50%, #dcdcdc 50%);
    z-index: -1;
    transform: skewY(2deg);
  }
  .captchaContainer{
    overflow: hidden;
  }
    </style>