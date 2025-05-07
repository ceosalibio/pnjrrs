<template>
    <div>
        <h1><center>Add/Update TO</center></h1>
        <v-divider/>
        <br>
        <v-container fluid>
            <v-row>
                <v-col cols="12" md="3" lg="3" xl="3">
                
                    <v-card elevation="3" class="justify-center" >
                        <v-card-title style="background-color: #0275d8;color:white;"><center>CONTROL PANEL</center></v-card-title>
                        <v-container>
                            <br>
                            <v-row class="d-flex justify-center" >
                                <v-radio-group v-model="radioTO" row>
                                <v-radio label="Exist TO" value="exist"></v-radio>
                                <v-radio label="New TO" value="new"></v-radio>
                                </v-radio-group>
                            </v-row>
                            <v-form ref="add" @submit.prevent="add">
                                <v-radio-group v-model="categoryRadio" row v-if="radioTO == 'new'">
                                    <h3>Category : </h3><v-spacer />
                                    <v-radio label="Existing" value="exist"></v-radio>
                                    <v-radio label="New" value="new"></v-radio>
                                </v-radio-group>
                                
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
                                    v-if="categoryRadio != 'new'"
                                />
                                <v-text-field 
                                    label="New Category"
                                    dense
                                    outlined
                                    v-model="categoryName"
                                    :rules="rules.required"
                                    v-else
                                />

                                <v-radio-group v-model="departmentRadio" row v-if="radioTO == 'new'" class="margin-top">
                                    <h3>Department : </h3><v-spacer />
                                    <v-radio label="Existing" value="exist"></v-radio>
                                    <v-radio label="New" value="new"   @click="unitFilter.getDepartment()"></v-radio>
                                </v-radio-group>
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
                                    v-if="departmentRadio != 'new'"
                            
                                />
                                <v-text-field 
                                    label="New Department"
                                    dense
                                    outlined
                                    v-model="newDepartmentName"
                                    :rules="rules.required"
                                    v-else
                                />

                                <v-radio-group v-model="sectionRadio" row v-if="radioTO == 'new'" class="margin-top">
                                    <h3>Section : </h3><v-spacer />
                                    <v-radio label="Existing" value="exist"></v-radio>
                                    <v-radio label="New" value="new" @click="unitFilter.getSection()"></v-radio>
                                </v-radio-group>
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
                                    v-if="sectionRadio != 'new'"
                                />
                                <v-text-field 
                                    label="New Section"
                                    dense
                                    outlined
                                    v-model="newSectionName"
                                    :rules="rules.required"
                                    @blur="unitFilter.getTeam()"
                                    v-else
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
                                    v-if="radioTO != 'new' && data.office == '1'"
        
                                />
                                <v-text-field 
                                    label="New Team"
                                    dense
                                    outlined
                                    v-model="newTeamName"
                                    :rules="rules.required"
                                    v-else
                                    v-show="data.office == '1'"
                                />
                                


                            
                                <!--  -->
                                <div v-if="data.office == '1' && data.team">
                                    <div v-show="this.tableData.length == 0 && user.restrict == '3'"> 
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv"  v-show="radioGroup == 'upload'  && radioTO != 'new'" class="file-input"/>
                                        <v-btn color="success" dark block medium @click="upload()" v-show="radioGroup == 'upload'  && radioTO != 'new'">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData.length > 0 && user.restrict == '3' && radioTO != 'new'">
                                        <v-btn color="error" dark block medium @click="deleteRow()">DELETE</v-btn>
                                    </div>
                                </div>
                                <div v-if="!data.office && data.section">
                                    <div v-show="this.tableData.length == 0 && user.restrict == '3'"> 
                                        <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv"  v-show="radioGroup == 'upload'  && radioTO != 'new'" class="file-input"/>
                                        <v-btn color="success" dark block medium @click="upload()" v-show="radioGroup == 'upload'  && radioTO != 'new'">UPLOAD</v-btn>
                                    </div>
                                    <div v-show="tableData.length > 0 && user.restrict == '3' && radioTO != 'new'">
                                        <v-btn color="error" dark block medium @click="deleteRow()">DELETE</v-btn>
                                    </div>
                                </div>
                                <!--  -->
                               

                                <v-btn color="success" dark block medium  v-if="radioTO == 'new'" @click="addUnitManual()">ADD</v-btn>
                            </v-form>
                        </v-container>
                    </v-card>
                    <br/>
                    <v-divider/>
                    <br/>
                    <afpos-reg v-if="user.rights == '1'"/>
                </v-col>
        
                <v-col cols="12" md="9" lg="9" xl="9" >
                    <v-card elevation="5">
                        <div class="d-flex flex-column justify-content-center text-center">
                            <h2>{{ departmentName }}</h2>
                            <h2>{{ sectionName }}</h2>
                            <h3>{{ teamName }}</h3>
                        </div>
                        <v-simple-table
                            fixed-header
                            class="elevation-1 custom-table"
                            height="80vh"
                        >
                            <thead>
                                <tr>
                                    <th>DESCRIPTION/POSITION</th>
                                    <th>GRADE</th>
                                    <th>AFPOS</th>
                                    <th>REQUIRED</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody v-for="(item, i) in tableData" :key="i">
                                <tr v-if="item.OfficeName">
                                    <td class="office-header" colspan="4">
                                        {{ item.OfficeName }}
                                    </td>
                                    <!-- <td>
                                        <v-btn icon @click="showEdit(item,i)"><v-icon color="warning">mdi-pencil</v-icon></v-btn>
                                    </td> -->
                                </tr>
                                <tr v-if="item.GroupBranch">
                                    <td class="branch-header" colspan="4">
                                        <!-- <template v-if="editColBranch && editIndex == i" >
                                            <v-text-field 
                                                outlined
                                                dense
                                                v-model="item.GroupBranch"
                                                class="mt-5"
                                            />
                                        </template>
                                        <template v-else> -->
                                            {{ item.GroupBranch }}
                                        <!-- </template> -->
                                    </td>
                                    <!-- <td>
                                        <v-btn icon @click="updateItem(item,i)" v-if="editColBranch && editIndex == i"><v-icon color="success">mdi-content-save-outline</v-icon></v-btn>
                                        <v-btn icon @click="showEdit(item,i)" v-else><v-icon color="warning">mdi-pencil</v-icon></v-btn>
                                    </td> -->
                                </tr>
                            
                                <tr v-if="item.GradeName">
                                    <td class="position-header" >
                                        <!-- <template v-if="editCol && editIndex == i" >
                                            <v-text-field 
                                                outlined
                                                dense
                                                v-model="item.PositionName"
                                                class="mt-5"
                                            />
                                        </template>
                                        <template v-else> -->
                                            {{ item.PositionName }}
                                        <!-- </template> -->
                                        
                                    </td>
                                    <td>
                                        <!-- <template v-if="editCol && editIndex == i" >
                                            <v-autocomplete 
                                                outlined
                                                dense
                                                v-model="item.GradeName"
                                                class="mt-5"
                                                :items="gradeList"
                                                item-text="GradeName"
                                                item-value="GradeName"
                                            />
                                        </template>
                                        <template v-else> -->
                                            {{ item.GradeName }}
                                        <!-- </template> -->
                                    
                                    </td>
                                    <td>{{ item.AfposName }}</td>
                                    <td>{{ item.RequiredMp }}</td>
                                    <td>
                                        <!-- <v-btn icon @click="updateItem(item,i)" v-if="editCol && editIndex == i"><v-icon color="success">mdi-content-save-outline</v-icon></v-btn> -->
                                        <!-- <v-btn icon @click="showEdit(item,i)" v-else><v-icon color="warning">mdi-pencil</v-icon></v-btn> -->
                                        <v-btn icon @click="deleteItem(item)"><v-icon color="error">mdi-trash-can-outline</v-icon></v-btn>
                                    </td>
                                </tr>
                                <template v-if="!item.GroupBranch && (!tableData[i + 1] || tableData[i + 1].GroupBranch)">
                                    <tr>
                                    <td style="font-weight:bold;font-size:15px;" colspan="2"></td>
                                    <td style="font-weight:bold;font-size:15px;">Total</td>
                                    <td style="font-weight:bold;font-size:15px;">{{ item.total }}</td>
                                    </tr>
                                </template>
                                <template v-if="i === tableData.length - 1">
                                    <tr>
                                    <td style="font-weight:bold;font-size:15px;" colspan="2"></td>
                                    <td style="font-weight:bold;font-size:15px;">Aggregate Total</td>
                                    <td style="font-weight:bold;font-size:15px;">{{ Manpower }}</td>
                                    </tr>
                                </template>

                            </tbody>
                        </v-simple-table>
                        
                    </v-card>
                    
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
import { encryptData,decryptData } from '../../details/crytp'
export default {
    components:{
        AfposReg
    },
    data:()=>({
        radioGroup:'upload',
        list : list,
        unitFilter : unitFilter,
        data : data,
        crud: crud,
        categoryName:null,
        newDepartmentName:null,
        newSectionName:null,
        newTeamName:null,
        tableData:[],
        tempFile:[],
        tempInsert:[],
        tempAfpos:[],
        forUpload:[],
        categoryRadio:'exist',
        departmentRadio:'exist',
        sectionRadio:'exist',
        radioTO : 'exist',

        rules:{
            required: [
                v => !!v || 'Field is required'
            ],
        },
    
        dataToExport:[],
        editColBranch:false,
        editCol:false,
        editIndex:null,
        gradeList:[]
        

    }),

    methods:{
        handleFileSelect(event) {
            // data.overlay = true
            this.tempFile = []
            this.forUpload = []
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
                        let obj = {
                            description : rowData.description.replace(/^\s+/, ''),
                            grade : rowData.grade?rowData.grade.replace(/\s+|-|\*/g, ''):'',
                            afpos  : rowData.afpos,
                            required : rowData.required,
                            office:rowData.office
                        }

                    this.tempFile.push(obj);
                }
                
                // data.overlay = false
            };
            reader.readAsText(file);

        },
        upload(){
            data.overlay = true
            let previosBranchName = ''
            for (let i = 0; i < this.tempFile.length - 1; i++) {
                const item = this.tempFile[i];
                let totalRow = item.description.toUpperCase();
                if (totalRow != 'TOTAL' && totalRow != 'SUBTOTAL') {
                    let obj = {
                        id : i,
                        BranchName:null,
                        PositionName : null,
                        AfposName : null,
                        GradeName : null,
                        RequiredMp : null,
                        OfficeName : null,
                    }
                    if(item.description.trim() !== '' &&  item.grade.trim() == '' && item.afpos.trim() == '' && item.required.trim() == ''){
                        obj.BranchName = item.description
                        previosBranchName = obj.BranchName 
                    }else{
                        obj.id = i,
                        obj.BranchName = previosBranchName
                        obj.PositionName = item.description
                        obj.AfposName = item.afpos
                        obj.GradeName = item.grade
                        obj.RequiredMp = item.required
                        obj.OfficeName = item.office == '1'?item.description:''
                        this.afpos(item)
                        
                    }
               
                     this.forUpload.push(obj)
              
                        
                }
            }
            this.$refs.fileInput.value = '';
            this.addUpload(this.forUpload);
        },
       async addUpload(val){
            try {
                // console.log(val)
                const response = await axiosHttp.post('uploadTO', {
                    unit: data.unitCode,
                    details: encryptData(val),
                    updated_by: this.user.name
                });
                if (response.status == 200) {
                    await this.insertAfpos();
                    this.forUpload = [];
                    data.overlay = false;
                    await this.getTable();
                }
            } catch (err) {
                console.error(err);
                data.overlay = false;
            }            
       },
       
//    end of function for uploading csv

       afpos(val){
            let obj= {}
            let array= []
            list.rankList.map(r=>{
                let grade = ''
                if (val.grade.includes('SG')) {
                    const sgMatch = val.grade.match(/^SG(\d+)/)
                    if (sgMatch) {
                        grade = `SG${parseInt(sgMatch[1], 10)}`
                    }
                } else if (val.grade.includes('E')) {
                    const eMatch = val.grade.match(/^E(\d+)/)
                    if (eMatch) {
                        grade = `E${parseInt(eMatch[1], 10)}`
                    }
                } else {
                    grade = val.grade
                }

                if(grade.includes(r.Grade)){
                    obj.DivisionCode = r.DivisionCode
                    obj.Afpos = val.afpos.toUpperCase()
                }
            })
            this.tempAfpos.push(obj)
       },

        async  insertAfpos(){
            let data = this.tempAfpos.reduce((total, item) => {
                let i = total.findIndex(res => item.Afpos && res.Afpos && res.Afpos.toUpperCase() === item.Afpos.toUpperCase());
                if (i === -1) {
                    total.push(item);
                }
                return total;
            }, []);
            try {
                const response = await axiosHttp.post('insertAfpos', { data });
                    if(response.status == 200){
                        this.tempAfpos = [];
                        
                    }
            } catch (error) {
                console.error(error);
            }
       },

        async  getTable(){
            this.tableData = []
            data.tempData = []
            data.overlay = true
        await  axiosHttp.post('getTable',{unit : this.unitCode}).then(res=>{
                if(res.data){
                    let details = decryptData(res.data.details)
                        data.tempData = details
                        this.tableData = details.reduce((total,item)=>{
                            let index = total.findIndex(res=> res.BranchName == item.BranchName)
                                if(index == -1){
                                    let obj = {...item}
                                    obj.GroupBranch = item.BranchName
                                    obj.TotalMP = isNaN(item.RequiredMp) ? 0 : Number(item.RequiredMp);
                                    total.push(obj)
                                }else{
                                    if (!isNaN(item.RequiredMp)) {
                                        total[index].TotalMP += Number(item.RequiredMp);
                                    }
                                    item.total = total[index].TotalMP
                                    total.push(item)
                                }
                                return total
                        },[])
                        console.log(this.tableData,'ceo')
                    }
                
                }).catch(err=>{
                    console.log(err)
                }).finally(()=>{
                    data.overlay = false
                })
        },
        // action button
       async deleteItem(item){
        let ask = confirm('Are you sure you want to delete this position?')
            if(ask){
                try {
                    data.overlay = true
                    const newDetails = data.tempData.filter(r=>r.id != item.id)
                    const res = await axiosHttp.post('deletePositon',{
                        unit : this.unitCode,
                        details : encryptData(newDetails)
                    });
                    if(res.status == 200){
                        this.getTable()
                    }
                } catch (error) {
                    console.log(error)
                }
                
            }
            
       },


       addUnitManual(){
        // console.log('test')
            // if(this.$refs.add.validate()){
                axiosHttp.post('insertManualUnit',
                    {
                            CategoryCode : this.categoryRadio == 'exist'?data.category:'CA'+this.generateCode(),
                            CategoryName : this.categoryName?this.categoryName.toUpperCase():null,
                            DepartmentCode : this.departmentRadio == 'exist'?data.department:'D'+this.generateCode(),
                            DepartmentName : this.newDepartmentName?this.newDepartmentName.toUpperCase():null,
                            SectionCode :  this.sectionRadio == 'exist'?data.section:'S'+this.generateCode(),
                            SectionName : this.newSectionName?this.newSectionName.toUpperCase():null,
                            SectionAbr : this.newSectionName?this.getAbbreviation(this.newSectionName):null,
                            TeamCode :  this.newTeamName?'T'+this.generateCode():null,
                            TeamName : this.newTeamName?this.newTeamName.toUpperCase():null,
                            TeamAbr : this.newTeamName?this.getAbbreviation(this.newTeamName):null,
                            UnitCode : 'RE'+this.generateCode()
                    }
                ).then(res=>{
                    if(res.status == 200){
                        this.radioTO = 'exist'
                        this.categoryRadio = 'exist'
                        this.departmentRadio = 'exist'
                        this.sectionRadio = 'exist'
                        alert('added')
                    }
                })
            // }
       },

       deleteRow(){
            const confirmed = window.confirm('Are you sure you want to delete this information?');
            if(confirmed){
                // data.overlay = true
                let obj = {
                    table : 'requiredpersonnels',
                    unit : data.unitCode,
                    name: this.user.name,
                    date: '2019', // subject to change (TO 2019)
                    time: moment().format('YYYY-MM-DD HH:mm:ss'),
                    rel : this.getTable.bind(this), //use bind to run function in external js
                    column:'org',
                    info : 'required'
                }
                return crud.deleteItem(obj)
            }
        },

        getAbbreviation(phrase) {
            const words = phrase.split(' ');
            let abbreviation = '';

            for (let i = 0; i < words.length; i++) {
                abbreviation += words[i][0].toUpperCase();
            }

            return abbreviation;
        },








// function for adding single details
        generateCode() {
            const charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            let code = "";
                for (let i = 0; i < 3; i++) {
                    const randomIndex = Math.floor(Math.random() * charset.length);
                    code += charset[randomIndex];
                }
              
                
            return code
        },
        newCodeTO(array,codeName){
            // console.log(array,codeName,'test33')
            let newCode
                let lastId = array[array.length - 1]
                // console.log(lastId)
                // //for new branchCode
                let lastRow = lastId[codeName].match(/\d+/);
                let getId = parseInt(lastRow[0], 10) + 1;
                newCode = lastId[codeName].replace(/\d+/, getId.toString().padStart(lastRow[0].length, '0'))
            return newCode
        },
        getCategory(){
            this.tableData = []
            this.department = null
            this.section = null
            this.team = null
            
            axios({
                method:'get',
                url:`api/getCategory`,
            }).then(r=>{
                this.categoryItems = r.data
            })
        },
        // getDepartment(){
        // this.tableData = []
        // this.section = null
        // this.team = null
        // axios({
        //     method:'post',
        //     url:`api/getDepartment`,
        //     data:{
        //         data : this.category,
        //         list : this.departmentRadio
        //     }
        // }).then(r=>{
        //     // console.log(r.data)
        //     this.departmentItems = r.data
        // })
        // },
        // getSection(){
        // this.tableData = []
        // this.team = null
        // axios({
        //     method:'post',
        //     url:`api/getSection`,
        //     data:{
        //         data : this.department,
        //         list : this.sectionRadio
        //     }
        // }).then(r=>{
        //     this.sectionItems = r.data
        // })
        // },
        // getTeam(){
        // this.tableData = []
        // axios({
        //     method:'post',
        //     url:`api/getTeam`,
        //     data:{
        //         data : this.section,
        //         list : this.radioTO
        //     }
        // }).then(r=>{
        //     this.teamItems = r.data
        //     if(this.category != 'CA01'){
        //         this.getTable()
        //     }
        // })
        // },
        // getGrade(){
        // axios({
        //     method:'post',
        //     url:`api/getGrade`,
        // }).then((response) => {
        //     const encryptedData = response.data;
        //     const decryptedData = decryptBase(encryptedData);
        //     this.gradeItems = JSON.parse(decryptedData);
        // })
        // .catch((error) => {
        //     console.error('Error fetching and decrypting grade data:', error);
        // });
        // },
        // getDivision(){
        // axios({
        //     method:'post',
        //     url:`api/getDivision`,
        //     data:{
        //         data : this.grade
        //     }
        // }).then(r=>{
        //     r.data.filter(  res=>{
        //         this.division = res.DivisionCode
        //     })
          
        // })
        // },
        // getEmpty(){
        //     this.tableData = []
        //     this.getTable()
        // },
        addCode(array,codeName){
        // console.log(array,codeName,'test33')
        let newCode
            let lastId = array.pop()
            // console.log(lastId)
            // //for new branchCode
            let lastRow = lastId[codeName].match(/\d+/);
            let getId = parseInt(lastRow[0], 10) + 1;
            newCode = lastId[codeName].replace(/\d+/, getId.toString().padStart(lastRow[0].length, '0'))
         return newCode
        },
      
       getNewCode(address,data,resName,resCode){
            return new Promise((resolve, reject) => {
                let newCode;
                axios({
                method: 'post',
                url: `api/${address}`,
                data:{
                    data : data
                }
                }).then(r => {
                    // console.log(r.data)
                if (r.data.length > 0) {
                    const index = r.data.find(res=> res[resName] == data)
                    // console.log(index)
                    if(index){
                        newCode = index[resCode];
                    }else{
                        newCode = this.addCode(r.data, resCode);
                    }
                } else {
                    if (address == 'getBranch') {
                        newCode = 'BR001';
                    } else if (address == 'getPosition') {
                        newCode = 'PS001';
                    } else {
                        newCode = 'AF001';
                    }
                }

                    resolve(newCode); // Resolving the Promise with the newCode value
                }).catch(error => {
                    reject(error); // Rejecting the Promise if an error occurs during the API call
                });
            });
        
       },

       showEdit(item,i){
        if(item.GroupBranch){
            this.editColBranch = true
        }else{
            this.editCol = true
        }
            
            this.editIndex = i
            this.getRequiredGrade()
       },
       updateItem(item,i){
            this.editColBranch = false
            this.editCol = false
            this.editIndex = null
            // console.log(item)
       },
       
       deleteUnit(){
        if (window.confirm(`Are you sure you want to delete this ${this.deleteNameDisplay.toLowerCase()}?`)) {
                axios({
                    method:'post',
                    url:`api/deleteUnit`,
                    data:{
                        data : this.deleteNameDisplay == 'DEPARTMENT'?this.department:this.deleteNameDisplay == 'SECTION'?
                                this.section:this.deleteNameDisplay == 'TEAM'?this.team:null,
                        table : this.deleteNameDisplay,
                        department : this.department,
                        section : this.section
                      
                    }
                }).then(res=>{
                    if(res.status == 200){
                        alert(`This ${this.deleteNameDisplay.toLowerCase()} was deleted`)
                        this.radioTO = 'exist'
                        this.categoryRadio = 'exist'
                        this.departmentRadio = 'exist'
                        this.sectionRadio = 'exist'
                        this.getCategory()
                        this.getDepartment()
                        this.getSection()
                        this.getTeam()
                    }
                    
                })
            }
       },
       
        


        getRequiredGrade(){
            axios.get('api/getRequiredGrade').then(res=>{
                this.gradeList = res.data
            })
        },
        // this all button bellow if for special case or test case
        testDelete(){
            axios.post('api/testDleteTO',{
                Team : this.department == 'D01' || this.department == 'D19'?this.team:this.section,
                Name: this.user.name,
                Time: moment().format('YYYY-MM-DD HH:mm:ss')
            })
            // .then((res)=>{
            //     console.log(res.data)
            // })
        },
        updateFos(){
            axios.post('api/updateFos')
            // .then((res)=>{
            //     // console.log(res.data)
            // })
        },
        uploadTest(){
            // console.log(this.tem)
            let ask = confirm('Are you sure you want to update personnel')
            if(ask){
                axios.post('api/updateUploadpersonnel',{
                    data : this.tempFile
                }).then((res)=>{
                    
                })
            }
        }
      


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
        Manpower(){
            if(this.tableData.length > 0){
                return this.tableData.reduce((total, item) => {
                    if (item.TotalMP) {
                        return total + item.TotalMP;
                    }
                    return total;
                    }, 0);
            }else{
                return 0
            }
        },
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