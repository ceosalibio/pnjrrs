<template>
    <div>
        <h1><center>UPDATE TABLE FACILITY</center></h1>
        <v-divider/>
        <br>
        <v-container fluid>
            <v-row class="pa-2">
                <v-col cols="12" md="3" lg="3" xl="3">
                    <v-card elevation="3" class="justify-center" >
                        <v-card-title class="vcard-title"><center>CONTROL PANEL</center></v-card-title>
                        <v-container>
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
                                    @change="unitFilter.getDepartment()"
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
                                    @change="unitFilter.getSection()"
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
                                    @change="unitFilter.getTeam()"
                                />

                                <v-autocomplete 
                                    label="Office"
                                    item-text="name"
                                    item-value="code"
                                    dense
                                    outlined
                                    :items="list.teamList"
                                    v-model="data.team"
                                    :rules="rules.required"
                                    @change="unitFilter.unitInformation()"
                                    v-if="data.office == '1'"
        
                                />

                                <!-- <v-autocomplete 
                                    label="Year"
                                    dense
                                    outlined
                                    :items="year"
                                    v-model="data.year"
                                    :rules="rules.required"
                                    @change="getTable()"
            
        
                                /> -->
                               
                            
                                <!--  -->
                                <div v-if="data.office == '1' && data.team">
                                    <div v-show="tableData.length == 0 && (user.restrict == '4' || user.rights == '1')"> 
                                        <label for="fileInput">Upload Facility csv</label>
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv" class="file-input">
                                        <v-btn color="success" dark block medium @click="upload()">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData.length > 0 && (user.restrict == '4' || user.rights == '1')">
                                        <v-btn color="error" dark block medium @click="deleteRow()">DELETE</v-btn>
                                    </div>
                                </div>
                                <div v-if="!data.office && data.section">
                                    <div v-show="tableData.length == 0 && (user.restrict == '4' || user.rights == '1')"> 
                                        <label for="fileInput">Upload Facility csv</label>
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv" class="file-input">
                                        <v-btn color="success" dark block medium @click="upload()">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData.length > 0 && (user.restrict == '4' || user.rights == '1')">
                                        <v-btn color="error" dark block medium @click="deleteRow()">DELETE</v-btn>
                                    </div>
                                </div>
                                <!--  -->
                            </v-form>
                        </v-container>
                    </v-card>
                    <br/>
                    <v-divider/>
                </v-col>
                <v-col cols="12" md="9" lg="9" xl="9" >
                    <Table  
                        :data="data"
                        :tableData="tableData"
                        :getTable="getTable"
                        :obj="obj"
                    />
                </v-col>
            </v-row>
            <v-overlay :value="data.overlay">
                <v-progress-circular
                    indeterminate
                    size="64"
                ></v-progress-circular>
            </v-overlay>
        </v-container>

       
    </div>
</template>

<script>

import {mapState} from 'vuex'
import Papa from 'papaparse';
import moment from 'moment';
import{ list } from '../../details/list.js'
import{ unitFilter } from '../../details/unitFilter.js'
import{ data } from '../../details/data.js'
import { crud } from '../../details/crud.js'
import axiosHttp from '../../../axiosHttp'
import { encryptData,decryptData, decryptBase } from '../../details/crytp'
import Table from './table.vue'
export default {
    components:{
        Table,
    },
    data:()=>({
      
        list : list,
        unitFilter : unitFilter,
        data : data,
        crud: crud,
        tableData:[],
        tempData:[],
        forUpload:[],
        rules:{
            required: [
                v => !!v || 'Field is required'
            ],
        },
        required1:0,
        required2:0,
        obj:{}
        
    }),

    methods:{

        handleFileSelect(event) {
                this.tempData = []
                this.metFileSelected = event.target.files.length > 0;
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = (e) => {
                    const contents = e.target.result;

                    const parsedData = Papa.parse(contents, {
                    header: true,
                    encoding: 'UTF-8',
                    });

                    const rows = parsedData.data;

                    // Process each row
                    const headers = Object.keys(rows[0]);
                    let prevInfo = ''
                    for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rowData = {};
                   

                        for (let j = 0; j < headers.length; j++) {
                            let value = row[headers[j]];
                            rowData[headers[j]] = value;
                        }
                        let obj = {
                            id: i + 1,
                            description : rowData.description.replace(/^\s+/, ''),
                            title : '',
                            info : '',
                            required : rowData.required?parseInt(rowData.required.replace(/,/g, ''), 10):'',
                            actual : '',
                            rating : '',
                            pa : '',
                            sa : '',
                            hs : '',
                            bp : '',
                            total : '',
                            facility : this.facility_rating
                        }
                        if(obj.description){
                            let match = this.master.find((r) => r.facility.toUpperCase().includes(obj.description.toUpperCase()));
                            if(match && match.info == '1'){
                                prevInfo = match.id
                            }
                            obj.title = match ? match.info : '';
                            obj.info = match && match.info == '1'?match.id:prevInfo
                            this.tempData.push(obj);
                        }
                        
                    }
                   
                };
     
                reader.readAsText(file);
        },
       async upload(){
            try {
                data.overlay = true
                const response = await axiosHttp.post('facilityDetails', {
                    unit: data.unitCode,
                    details: encryptData(this.tempData),
                    year : '2019',
                    updated_by: this.user.name
                });
               
                if (response.status == 200) {
                    this.$refs.fileInput.value = '';
                    this.teampData = [];
                    data.overlay = false;
                    await this.getTable();
                }
            } catch (err) {
                console.error(err);
                data.overlay = false;
            }            
       },
     async  getTable(){
        this.obj = {}
        data.overlay = true
            axiosHttp.post('requiredFacilities',{
                unit: data.unitCode
            }).then(res=>{
                let val = decryptBase(res.data)
                if(val){
                    this.obj = {...val}
                    data.tempData = val.details
                    this.tableData = val.details
                }else{
                    data.tempData = []
                    this.tableData = []
                }
            }).catch(err=>{
                console.log(err)
                // alert('Error Try again')
                // data.overlay = false
            }).finally(()=>{
                data.overlay = false
            })       
       },
       
        // action button
      
       deleteRow(){
            const confirmed = window.confirm('Are you sure you want to delete this information?');
            if(confirmed){
                // data.overlay = true
                let obj = {
                    table : 'requiredfacilities',
                    unit : data.unitCode,
                    name: this.user.name,
                    date: '2019', // subject to change (TO 2019)
                    time: moment().format('YYYY-MM-DD HH:mm:ss'),
                    rel : this.getTable.bind(this), //use bind to run function in external js
                    column:'year',
                    info : 'required'
                }
                return crud.deleteItem(obj)
            }
        },
    },
    computed:{
        ...mapState([
            'user'
        ]),
        departmentName(){
            if(data.department != ''){
               return list.departmentList.find(item => item.code === data.department)?.name;
            }
        },
        sectionName(){
            if(data.section != ''){
               return list.sectionList.find(item => item.code === data.section)?.name;
            }
        },
        teamName(){
            if(data.team != ''){
            
               return list.teamList.find(item => item.code === data.team)?.name;
            }
        },
        unitCode(){
            if(data.unitCode){
                return data.unitCode;
            }
        },
        year(){
            let data = []
            for (let i = 2024; i < 2028; i++) {
                data.push(i)
            }
            return data
        },
        master(){
            let data = [
                {id:1,info:'1',facility:'Operational Facilities'},
                {id:2,info:'1',facility:'Operational Support Facilities'},
                {id:3,info:'1',facility:'Base Utilities and Facilities'},
                {id:4,info:'1',facility:'Community Facilities'},
                {id:5,info:'2',facility:'Administrative Building (sq. m.)'},
                {id:6,info:'2',facility:'Communication Facility (sqm)'},
                {id:7,info:'2',facility:'Base Security & Defense Facilties'},
                {id:8,info:'2',facility:'Other Operational Facilties'},
                {id:9,info:'2',facility:'Billeting facilities(sq.m.)'},
                {id:10,info:'2',facility:'Mess facilties (sqm)'},
                {id:11,info:'2',facility:'Health Facilities'},
                {id:12,info:'2',facility:'Storage Facilities'},
                {id:13,info:'2',facility:'Maintenance facilities'},
                {id:14,info:'2',facility:'Training Facilities '},
                {id:15,info:'2',facility:'Chapel Facility (sq.m.)'},
                {id:16,info:'2',facility:'Recreational Facility (sq.m.)'},
                {id:17,info:'2',facility:'Common Living Space (sq..m.)'},
                {id:18,info:'2',facility:'Family Housing/Dwelling (sq.m.)'},
            ]
            return data
        },
        facility_rating(){
            let data = [
                {id:1,code:'PA',detail:'A. Physical Appearance (10%)',percent:0,parameter:'',rating:''},
                {id:2,code:'',detail:'1. Building Interior and Exterior',percent:0,parameter:'',rating:''},
                {id:3,code:'PA1',detail:'a.Condition of Roofing',percent:5,parameter:'',rating:''},
                {id:4,code:'PA2',detail:'b.Condition of Wall Painting',percent:5,parameter:'',rating:''},
                {id:5,code:'SA',detail:'B.Safety (30%)',percent:0,parameter:'',rating:''},
                {id:6,code:'',detail:'1. Structural Safety (10%)',percent:0,parameter:'',rating:''},
                {id:7,code:'SA1',detail:'a. Building mainframes',percent:5,parameter:'',rating:''},
                {id:8,code:'SA2',detail:'b. Building secondary members',percent:5,parameter:'',rating:''},
                {id:9,code:'',detail:'2.Fire Safety (10%)',percent:0,parameter:'',rating:''},
                {id:10,code:'SA3',detail:'a. Fire/emergency exit',percent:5,parameter:'',rating:''},
                {id:11,code:'SA4',detail:'b. Fire fighting Equipment',percent:5,parameter:'',rating:''},
                {id:12,code:'',detail:'3. Building Openings and Locks (10%)',percent:0,parameter:'',rating:''},
                {id:13,code:'SA5',detail:'a. Doors and Door Locks',percent:5,parameter:'',rating:''},
                {id:14,code:'SA6',detail:'b. Windows and Windows Locks',percent:5,parameter:'',rating:''},
                {id:15,code:'HS',detail:'C. Habitability and Sanitation (30%)',percent:0,parameter:'',rating:''},
                {id:16,code:'',detail:'1. Water and Plumbing (10%)',percent:0,parameter:'',rating:''},
                {id:17,code:'HS1',detail:'a. Water Source',percent:2.5,parameter:'',rating:''},
                {id:18,code:'HS2',detail:'b. Water Line',percent:2.5,parameter:'',rating:''},
                {id:19,code:'HS3',detail:'c. Septic Tank',percent:2.5,parameter:'',rating:''},
                {id:20,code:'HS4',detail:'d. Sewer Lines',percent:2.5,parameter:'',rating:''},
                {id:21,code:'',detail:'2. Electrical Integrity (10%)',percent:0,parameter:'',rating:''},
                {id:22,code:'HS5',detail:'a. Circuit breakers or fuse box',percent:3,parameter:'',rating:''},
                {id:23,code:'HS6',detail:'b. Electrical wirings',percent:2,parameter:'',rating:''},
                {id:24,code:'HS7',detail:'c. Electrical lightings and fictures',percent:5,parameter:'',rating:''},
                {id:25,code:'',detail:'3. Other Sanitation and Habitality Requirement (10%)',percent:0,parameter:'',rating:''},
                {id:26,code:'HS8',detail:'a. Toilet and bathroom',percent:5,parameter:'',rating:''},
                {id:27,code:'HS9',detail:'b. Proper lighting and ventilation',percent:5,parameter:'',rating:''},
                {id:28,code:'BP',detail:'D. Building Plans and Other Documents (30%)',percent:0,parameter:'',rating:''},
                {id:29,code:'BP1',detail:'1. Site Development and Building Plan',percent:5,parameter:'',rating:''},
                {id:30,code:'BP2',detail:'2. Technical Inspection and Acceptance or properly entered and recorded in the Unit Property Book',percent:5,parameter:'',rating:''},
                {id:31,code:'BP3',detail:'3. Electrical/Water/Plumbing System Plan',percent:5,parameter:'',rating:''},
                {id:32,code:'BP4',detail:'4. Building Insurance',percent:5,parameter:'',rating:''},
                {id:33,code:'BP5',detail:'5. Maintenance Records',percent:10,parameter:'',rating:''},
            ]
            return data
        }
    },
    mounted(){
        unitFilter.getCategory()
        data.section = ''
        data.team = ''
       
    },
    watch:{
        unitCode(val, oldVal) {
            // console.log(val, oldVal)
            if (val != oldVal) {
                this.tableData = []
                this.tempData = []
                if(data.office == '1' && data.team){
                    this.getTable()
                }else if(data.office != '1' && data.section){
                    this.getTable()
                }
            }
        }
    }
}
</script>
<style >
.v-slide-group__content {
    justify-content:center;
}
.v-data-table > .v-data-table__wrapper > table {
    text-align:center; /* text naka center*/
}
.custom-table th,
.custom-table td {
    border: 1px solid #ccc;
    padding: 10px;
}
.text-center .v-text-field__slot input {
    text-align: center;
}
.custom-container {
    margin-bottom: -10;
}
  
.custom-container .v-input__control {
    padding-top: -10px; /* Adjust top padding */
    padding-bottom: -10px; /* Adjust bottom padding */
}
  
.custom-container .v-label {
    margin-bottom: 1px; /* Adjust bottom margin */
}
.file-input {
 /* Hide the default file input */
  /* Add your desired styles */
  /* For example: */
  padding: 10px;
  background-color: #f0f0f0;
  color: #333;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
}
.margin-top{
    margin-top:-20px;
}
</style>