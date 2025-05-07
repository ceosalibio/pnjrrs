<template>
    <div>
        <h1><center>UPDATE TABLE EQUIPMENT</center></h1>
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
                                        <label for="fileInput">Upload Equipment csv</label>
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv" class="file-input">
                                        <v-btn color="success" dark block medium @click="upload()">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData.length > 0 && (user.restrict == '4' || user.rights == '1')">
                                        <v-btn color="error" dark block medium @click="deleteRow()">DELETE</v-btn>
                                    </div>
                                </div>
                                <div v-if="!data.office && data.section">
                                    <div v-show="tableData.length == 0 && (user.restrict == '4' || user.rights == '1')"> 
                                        <label for="fileInput">Upload Equipment csv</label>
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
                    <Tab1  
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
import Tab1 from './tab1.vue'
export default {
    components:{
        Tab1,
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
                this.forUpload = []
                this.metFileSelected = event.target.files.length > 0;
                const file = event.target.files[0];
                const reader = new FileReader();
                let previousCategory = ''
                let previousSub = ''

                reader.onload = (e) => {
                    const contents = e.target.result;

                    const parsedData = Papa.parse(contents, {
                    header: true,
                    encoding: 'UTF-8',
                    });

                    const rows = parsedData.data;

                    // Process each row
                    const headers = Object.keys(rows[0]);

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
                            required : rowData.required?parseInt(rowData.required.replace(/,/g, ''), 10):'',
                            category  : null,
                            sub : null,
                            na:rowData.na
                        }
                       
                    
                        if(rowData.category){
                            obj.category = rowData.category
                            previousCategory = rowData.category
                            previousSub = ''
                        }else{
                            obj.category = previousCategory
                        }

                        if(rowData.sub && obj.required){
                            obj.sub = rowData.sub
                            previousSub = rowData.sub
                        }
                        else{
                            if(obj.required){
                                obj.sub = previousSub
                            }
                        }
                   
                        if(obj.description.trim() !== ''){
                            obj.Title = !obj.required ? '1' : obj.na == 'YES'?'2':''
                            obj.Info = obj.category == 'FIREPOWER' || obj.category == 'MOBILITY' || obj.category == 'COMMUNICATIONS' ? 'primary' : 'secondary'
                            if(obj.required && !obj.na){
                                obj.Info == 'primary'?this.required1 += obj.required : this.required2 += obj.required
                            }
                            this.tempData.push(obj);
                        }
                    }
                    // console.log(this.tempData.sort(this.customSort),this.required1,this.required2 )
                };
     
                reader.readAsText(file);
        },
        customSort(a, b) {
            const categoryOrder = {
                'MOBILITY': 1,
                'FIREPOWER': 2,
                'COMMUNICATIONS': 3,
                'MEDICAL': 4,
                'DENTAL': 5,
                'QUARTERMASTER': 6
            }
            return categoryOrder[a.category] - categoryOrder[b.category]
        },
       async upload(){
            try {
                // console.log(val)
                data.overlay = true
                const response = await axiosHttp.post('equipmentDetails', {
                    unit: data.unitCode,
                    details: encryptData(this.tempData.sort(this.customSort)),
                    required1: this.required1,
                    required2: this.required2,
                    // master:encryptData(this.master),
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
        this.tableData = []
        data.tempData = []
        this.obj = {}
        data.overlay = true
            axiosHttp.post('requiredEquipment',{
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
                alert('Error Try again')
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
                    table : 'requiredequipments',
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
                {title:'1',category:'MOBILITY',sub:'',info:'primary',required:'',actual:'',percent:''},
                {title:'',category:'MOBILITY',sub:'Ground',info:'primary',required:0,actual:0,percent:0},
                {title:'',category:'MOBILITY',sub:'Sea',info:'primary',required:0,actual:0,percent:0},
                {title:'',category:'MOBILITY',sub:'Air',info:'primary',required:0,actual:0,percent:0},
                {title:'1',category:'FIREPOWER',sub:'',info:'primary',required:'',actual:'',percent:''},
                {title:'',category:'FIREPOWER',sub:'Individual',info:'primary',required:0,actual:0,percent:0},
                {title:'',category:'FIREPOWER',sub:'Crew Served Weapon',info:'primary',required:0,actual:0,percent:0},
                {title:'1',category:'COMMUNICATIONS',sub:'COMMUNICATIONS',info:'primary',required:0,actual:0,percent:0},
                {title:'',category:'',sub:'Total',info:'primary',required:0,actual:0,percent:0},
                {title:'2',category:'',sub:'MISSION ESSENTIAL EQUIPMENT (80%)',info:'primary',required:0,actual:0,percent:0},
                {title:'1',category:'MEDICAL',sub:'MEDICAL',info:'secondary',required:0,actual:0,percent:0},
                {title:'1',category:'DENTAL',sub:'DENTAL',info:'secondary',required:0,actual:0,percent:0},
                {title:'1',category:'QUARTERMASTER',sub:'QUARTERMASTER',info:'secondary',required:0,actual:0,percent:0},
                {title:'',category:'',sub:'Total',info:'secondary',required:0,actual:0,percent:0},
                {title:'2',category:'',sub:'SECONDARY EQUIPMENT(20%)',info:'secondary',required:0,actual:0,percent:0},
                {title:'3',category:'',sub:'MEE + SEE',info:'result',required:0,actual:0,percent:0},
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