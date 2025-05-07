<template>
    <div class="pa-5">
    <h1><center>User Information</center></h1>
        <v-divider />
        <br/>
        <v-row>
            
            <v-col cols="12" md="3" lg="3" xl="3">
                <v-card elevation="3" class="justify-center scroll-card" height="70vh" scrollable>
                    <v-card-title  class="card-title"><center>CONTROL PANEL</center></v-card-title>
                    <v-card-text >
                        <br>
                        <v-form ref="add" @submit.prevent="add">
                            <v-autocomplete 
                                label="Category"
                                :items="list.categoryList"
                                item-text="category_name"
                                item-value="category_code"
                                v-model="data.category"
                                dense
                                outlined
                                :rules="rules.required"
                                clearable
                                @change="()=>{unitFilter.getDepartment(),searchUser()}"
                            />
                            <v-autocomplete 
                                label="Unit"
                                dense
                                outlined
                                :items="list.departmentList"
                                item-text="name"
                                item-value="code"
                                v-model="data.department"
                                :rules="rules.required"
                                clearable
                                @change="()=>{unitFilter.getSection(),searchUser()}"
                            />
                            <v-autocomplete 
                                label="Sub-Unit"
                                dense
                                outlined
                                :items="list.sectionList"
                                item-text="name"
                                item-value="code"
                                v-model="data.section"
                                :rules="rules.required"
                                clearable
                                @change="()=>{unitFilter.getTeam(),searchUser()}"
                            />
                            <v-autocomplete 
                                label="Offices"
                                item-text="name"
                                item-value="code"
                                dense
                                outlined
                                :items="list.teamList"
                                v-model="data.team"
                                :rules="rules.required"
                                @change="()=>{unitFilter.unitInformation(),searchUser()}"
                                clearable
                                v-if="data.office"
                            />
                            <v-text-field
                                label="Name"
                                v-model="name"
                                outlined
                                dense
                                :rules="rules.required"
                                clearable
                                @click="dialogAddName"
                            />
                            
                            <v-autocomplete
                                label="Unit Approver"
                                outlined
                                dense
                                v-model="approver"
                                :items="approverItems"
                                item-text="text"
                                item-value="value"
                                :rules="rules.required"
                                clearable
                
                            />
                            <v-autocomplete
                                label="Office Admin"
                                v-model="restriction"
                                :items="restrictionItems"
                                item-text="text"
                                item-value="value"
                                outlined
                                dense
                                :rules="rules.required"
                                clearable
                            />
                            <v-text-field
                                label="Email Address"
                                v-model="email"
                                outlined
                                dense
                               
                            />
                            <v-text-field
                                label="Username"
                                v-model="username"
                                outlined
                                dense
                                :rules="rules.required"
                                @blur="generatePassword()"
                                maxlength="10"
                                hint="Maximum of 10 characters"
                            />
                            <v-text-field
                                label="Password"
                                v-model="password"
                                outlined
                                dense
                                :rules="rules.required"
                                maxlength="6"
                                hint="Maximum of 6 characters"
                            />
                            <v-btn block color="success" type="submit">ADD</v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" md="9" lg="9" xl="9" >
                <v-card>
                    <v-simple-table fixed-header height="60vh" class="elevation-1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Unit</th>
                                <th>Sub-Unit</th>
                                <th>Office</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>No</th>
                                <th>AccessRight</th>
                                <th>Logs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,i) in tableData" :key="i" v-if="item.username != 'ceo'">
                                <td>{{ i }}</td>
                                <td>{{ item.departmentabr }}</td>
                                <td>{{ item.sectionabr }}</td>
                                <td>{{ item.teamabr }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ designation(item) }}</td>
                                <td>{{ item.username }}</td>
                                <td>{{ item.repas }}</td>
                                <td>{{ item.office }}</td>
                                <td style="text-align: center;">
                                        <v-switch
                                            :value="item.id"
                                            inset
                                            v-model="rights"
                                            @change="addRights(item)"
                                        >
                                        </v-switch>
                                
                                </td>
                                <td><v-btn icon><v-icon color="success" @click="viewLogs(item)">mdi-eye</v-icon></v-btn></td>
                                <td><v-btn icon  @click="editLogs(item)"><v-icon color="warning">mdi-pencil-circle-outline</v-icon></v-btn></td>
                            </tr>
                        </tbody>
                        <v-overlay :value="$store.state.overlay" absolute>
                            <v-progress-circular
                                indeterminate
                                size="64"
                            ></v-progress-circular>
                        </v-overlay>
                    </v-simple-table>
                    <v-card-actions height="5vh">
                        <v-row >
                            <v-col cols="4" style="margin-top: 25px;margin-bottom:-20px;">
                                <v-text-field  
                                    dense
                                    outlined
                                    v-model="keyword"
                                    placeholder="Search name here.."
                                    @keypress.enter="searchUser"
                                    clearable
                                />
                            </v-col>
                            <v-col cols="8" style="margin-top: 25px;margin-bottom:-20px;">
                                <span class="float-right mr-7">Total Records: <b>{{ tableData.length }}</b></span>
                            </v-col>
                        </v-row>
                        
                    </v-card-actions>
                   
                </v-card>
                
            </v-col>
            
        </v-row>
        <DialogName  ref="dialogName" :rules="rules" @addName="addName"/>
        <DialogLog  ref="dialogLog"/>
        <DialogEditUser ref="edituser" 
            :rules="rules" 
            :approverItems="approverItems" 
            :restrictionItems="restrictionItems"
            :getuser="getUser"
        />
    </div>
</template>

<script>
import axiosHttp from '../../../axiosHttp'
import{ list } from '../../details/list.js'
import{ unitFilter } from '../../details/unitFilter.js'
import{ data } from '../../details/data.js'
import { mapState } from 'vuex'
import { decryptBase } from '../../details/crytp.js'
import DialogName from '../../dialog/dialogAddName.vue'
import DialogLog from '../../dialog/dialogLogged.vue'
import DialogEditUser from '../../dialog/dialogEditUser.vue'
    export default {
        components:{
            DialogName,
            DialogLog,
            DialogEditUser
        },
        data() {
            return {
                rules:{
                    required: [
                        v => !!v || 'Field is required'
                    ],
                },
                list : list,
                unitFilter : unitFilter,
                data : data,
                approver:null,
                restriction:null,
                restrictionItems:[
                    {text:'NO RESTRICTION',value:'-'},
                    {text:'1-PERSONNEL',value:1},
                    {text:'8-TRAINING',value:8},
                    {text:'4-EQUIPT&FAC',value:4},
                    {text:'6-COMMUNICATION',value:6},
                    {text:'3-ALL',value:3},
                ],
                name:'',
                username:'',
                password:'',
                email:'',
                keyword:'',
                rights:[],
                tableData:[]
            }
        },
        methods:{
            generatePassword() {
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                let password = "";
                    for (let i = 0; i < 7; i++) {
                        const randomIndex = Math.floor(Math.random() * charset.length);
                        password += charset[randomIndex];
                    }
                    if(this.username){
                        this.password = password
                    }else{
                        this.password = null
                    }
                    
                return this.password
            },
            designation(item){
                if(item.approver == 1){
                    return 'Encoder'
                }else if(item.approver == 2){
                    return 'OIC'
                }else if(item.approver == 3){
                    return item.hpn != '1'?'CHIEF OF STAFF':'EX-O'
                }else if(item.approver == 4){
                    return 'DEPUTY'
                }else{
                    return item.hpn != '1'?'COMMANDER':item.team
                }
            },

            add(){
                if(this.$refs.add.validate()){
                    axiosHttp.post('insertUser',{
                            unit : data.unitCode,
                            name : this.name.toUpperCase(),
                            approver : this.approver,
                            username : this.username,
                            password : this.password,
                            email : this.email,
                            restriction : this.restriction
                    }).then(res=>{
                   
                        // this.$refs.add.reset()
                        this.name = null
                        this.username = null
                        this.password = null
                        this.getUser()
                    }).catch(err=>{
                        console.log(err)
                    })
                }

            },
            
            getUser(){
                this.$store.state.overlay = true
                axiosHttp.post('viewUser',{
                    keyword : this.keyword
                }).then((res)=>{
                    this.tableData = decryptBase(res.data)
                    this.tableData.filter(r=>{
                        if(r.rights){
                            this.rights.push(r.id)
                        }
                    })
                }).catch(err=>{
                    console.log(err)
                }).finally(()=>{
                    this.$store.state.overlay = false
                })
            },
            
            searchUser(){
                this.$store.state.overlay = true
                this.tableData = []
                axiosHttp.post('searchUser',{
                    keyword : this.keyword,
                    category : data.category,
                    department : data.department,
                    section : data.section,
                    team : data.team,
                }).then((res)=>{
                    this.tableData = decryptBase(res.data)
                }).catch(err=>{
                    console.log(err)
                }).finally(()=>{
                    this.$store.state.overlay = false
                })
            },
        // dialog name function component
            dialogAddName(){
                unitFilter.getRank()
                this.$refs.dialogName.openDialog()
            },
            addName(val){
                this.name = `${val.rank} ${val.name} ${val.service}`
            },
            viewLogs(item){
     
                let data = JSON.parse(item.log).sort((a, b) => new Date(b.Login) - new Date(a.Login))
                let obj = {
                    name : item.name,
                    logs : data
                }
                this.$refs.dialogLog.openDialog(obj)
            },
            editLogs(item){
                this.$refs.edituser.openDialog(item)
            },
            addRights(item){
                let addRights = item.rights?null:1
                axiosHttp.post('updateRights',{
                    id : item.id,
                    rights : addRights
                }).then(res=>{
                    this.getUser()
                }).catch(err=>{
                    console.log(err)
                })
            },


        },
        computed:{
            approverItems(){
                let arr
                if(data.topOffice == '10'){
                    arr = [
                        {text:'Encoder/Drafter',value:1},
                        {text:'OIC',value:5},
                    
                    ]
                }else{
                    if(data.hpn == '1'){
                        arr = [
                            {text:'Encoder/Drafter',value:1},
                            {text:'OIC',value:2},
                            {text:'EX-O/COS',value:3},
                            {text:'DEPUTY',value:4},
                            {text:'COMMANDER',value:5},
                        ]
                    }else{
                        arr = [
                            {text:'Encoder/Drafter',value:1},
                            {text:'OIC',value:4},
                            {text:'COMMANDER',value:5},
                        ]
                    }
                    
                }
                return arr
     
            }
        },
        watch:{
            keyword(val){
                if(!val){
                    this.getUser()
                }
            }
        },
        mounted(){
            unitFilter.getCategory()
            this.getUser()
        }
    }
</script>
