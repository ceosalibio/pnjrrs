<template>
    <div>
        <h1><center>UPDATE TRAINING</center></h1>
        <v-divider/>
        <v-col cols="12" md="12" lg="12" xl="12">
            <v-tabs justify-center>
                <v-tab  @click="goTab('1')" >MET</v-tab>
                <v-tab  @click="goTab('2')" >ACTIVITY</v-tab>
            </v-tabs>
        </v-col>
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

                                <v-autocomplete 
                                    label="Year"
                                    dense
                                    outlined
                                    :items="year"
                                    v-model="data.year"
                                    :rules="rules.required"
                                    @change="getTable()"
            
        
                                />
                               
                            
                                <!--  -->
                                <div v-if="data.office == '1' && data.team && data.year">
                                    <div v-show="!tableData && (user.restrict == '8' || user.rights == '1')"> 
                                        <label for="fileInput">Upload MET CSV file</label>
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv" class="file-input">
                                        <br />
                                        <label for="fileInput2">Upload Activity CSV file</label>
                                        <input type="file" ref="fileInput2" @change="handleFileSelect2" accept=".csv" class="file-input"/>
                                        <v-btn color="success" dark block medium @click="upload()" :disabled="!metFileSelected ||!activityFileSelected">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData && (user.restrict == '8' || user.rights == '1')">
                                        <v-btn color="error" dark block medium @click="deleteRow()">DELETE</v-btn>
                                    </div>
                                </div>
                                <div v-if="!data.office && data.section && data.year">
                                    <div v-show="!tableData && (user.restrict == '8' || user.rights == '1')"> 
                                        <label for="fileInput">Upload MET CSV file</label>
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv" class="file-input">
                                        <br />
                                        <label for="fileInput2">Upload Activity CSV file</label>
                                        <input type="file" ref="fileInput2" @change="handleFileSelect2" accept=".csv"  class="file-input"/>
                                        <v-btn color="success" dark block medium @click="upload()" :disabled="!metFileSelected ||!activityFileSelected">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData && (user.restrict == '8' || user.rights == '1')">
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
                    <v-tabs-items v-model="tab">
                        <!-- tab 1 -->
                        <v-tab-item :value="'1'">
                            
                                <Tab1  
                                    :data="data"
                                    :year="year"
                                    :tableData="tableData"
                                />
                            
                        </v-tab-item>
                        <!-- tab 2 -->
                        <v-tab-item :value="'2'">
          
                                <Tab2  
                                    :data="data"
                                    :year="year"
                                    :tableData="tableData"
                                />
                        
                        </v-tab-item>
                    </v-tabs-items>
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
import axios from 'axios';
import moment from 'moment';
import AfposReg from '../../dialog/dialogAddAfpos.vue';
import{ list } from '../../details/list.js'
import{ unitFilter } from '../../details/unitFilter.js'
import{ data } from '../../details/data.js'
import { crud } from '../../details/crud.js'
import axiosHttp from '../../../axiosHttp'
import { encryptData,decryptData, decryptBase } from '../../details/crytp'
import Tab1 from './tab1.vue'
import Tab2 from './tab2.vue'
export default {
    components:{
        Tab1,
        Tab2,
    },
    data:()=>({
        tab:'1',
        list : list,
        unitFilter : unitFilter,
        data : data,
        crud: crud,
        metFileSelected: false,
        activityFileSelected: false,
        tableData:[],
        tempMet:[],
        tempActivity:[],
        forUpload:[],
        rules:{
            required: [
                v => !!v || 'Field is required'
            ],
        },
        required:0
        
    }),

    methods:{
        goTab(tab){
            this.tab = tab
        },
        handleFileSelect(event) {
                this.tempMet = []
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

                    for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rowData = {};

                    for (let j = 0; j < headers.length; j++) {
                        let value = row[headers[j]];
                        rowData[headers[j]] = value;
                    }
                    this.tempMet.push(rowData);
                    }
                };
     
                reader.readAsText(file);
        },
        handleFileSelect2(event){
            this.tempActivity = []
            this.activityFileSelected = event.target.files.length > 0;
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

                for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const rowData = {};

                for (let j = 0; j < headers.length; j++) {
                    let value = row[headers[j]];
                    rowData[headers[j]] = value;
                }
                    this.tempActivity.push(rowData);
                }
            };
    
            reader.readAsText(file);
        },
        upload(){
            data.overlay = true
        
            for (let i = 0; i < this.tempMet.length - 1; i++) {
                    const item = this.tempMet[i];
                    let obj = {}
                    obj.id = i + 1
                    obj.met = item.met
                    obj.required = 0
                    obj.actual = 0
                    obj.percent = 0
                    for (let r = 1; r <= 4; r++) {
                        obj[`q${r}`] = item[`quarter${r}`],
                        obj[`q${r}activity`] = '',
                        obj[`q${r}date`] = '',
                        obj[`q${r}incharge`] = '',
                        obj[`q${r}month`] = '',
                        obj[`q${r}stats`] = '-'
                        if(item[`quarter${r}`]){
                            this.required += 1
                            obj.required += 1
                        }
                    }
                    this.forUpload.push(obj)
                }

                // console.log(this.forUpload,'this.uploadFile',this.required)
                this.$refs.fileInput.value = '';
            this.addUpload(this.forUpload);
        },
       async addUpload(val){
            try {
                // console.log(val)
      
                const response = await axiosHttp.post('trainingDetails', {
                    unit: data.unitCode,
                    details: encryptData(val),
                    activity : encryptData(this.tempActivity),
                    required: this.required,
                    year : data.year,
                    updated_by: this.user.name
                });
               
                if (response.status == 200) {
                    this.forUpload = [];
                    data.overlay = false;
                    await this.getTable();
                }
            } catch (err) {
                console.error(err);
                data.overlay = false;
            }            
       },
     async  getTable(){
        data.overlay = true
            axiosHttp.post('requiredTraining',{
                unit: data.unitCode,
                year : data.year,
            }).then(res=>{
                this.tableData = decryptBase(res.data)
                // console.log(this.tableData)
            }).catch(err=>{
                console.log(err)
                alert('Error Try again')
                data.overlay = false
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
                    table : 'requiredtrainings',
                    unit : data.unitCode,
                    name: this.user.name,
                    date: data.year, // subject to change (TO 2019)
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
        }
    },
    mounted(){
        unitFilter.getCategory()
        unitFilter.getRank()
        unitFilter.getAfpos()
        data.section = ''
        data.team = ''
       
    },
    watch:{
        unitCode(val, oldVal) {
            // console.log(val, oldVal)
            if (val != oldVal) {
                this.tableData = []
                this.tempData = []
                if(data.office == '1' && data.team && data.year){
                    this.getTable()
                }else if(data.office != '1' && data.section && data.year){
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