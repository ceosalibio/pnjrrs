<template>
    <div class="pa-2">
        <v-card elevation="3">
            <v-card-title class="d-flex justify-center vcard-title">
              CONTROL  
            </v-card-title>
            <v-card-text>
      
                <v-col cols="12">
                    <v-autocomplete 
                        label="View Report"
                        dense
                        outlined
                        :items="unitItems"
                        item-text="text"
                        item-value="value"
                        v-model="report"
                        class="input-unit"
                    />
                </v-col>
                <v-col>
                    <v-dialog ref="dialog" v-model="generateMonthDialog" :return-value.sync="generateDate" persistent width="290px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field 
                                v-model="formattedDate" 
                                label="Select Month" 
                                prepend-inner-icon="mdi-calendar" 
                                readonly 
                                v-bind="attrs" 
                                v-on="on" 
                                dense 
                                outlined
                                class="text-center"
                            />
                        </template>
                        <v-date-picker v-model="generateDate" type="month" scrollable :max="month">
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="generateMonthDialog = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="[$refs.dialog.save(generateDate)]">OK</v-btn>
                        </v-date-picker>
                    </v-dialog>
                </v-col>

                <v-col>
                    <v-btn 
                        color="warning" 
                        medium 
                        block 
                        @click="generateData()"  
                        class="mb-2" 
                        :disabled="data.unitCode && generateDate ?false:true"
                    >
                        GENERATE
                    </v-btn>
                    <div v-if="['2','3'].includes(report) && user.office != '-'">
                        <v-btn 
                            color="primary" 
                            medium 
                            block 
                            class="mb-2" 
                            :disabled="Object.keys(val).length > 0 && val.status != '0' ?false:true" 
                            @click="openPrint()"
                            v-if="tab == '6'"
                        >
                            PRINT
                        </v-btn>
                        <v-btn 
                            v-show="user.approver == '1' && Object.keys(val).length > 0 && user.unitcode == data.unitCode"
                            color="success"
                            medium
                            block
                            @click="submitData()" 
                            :disabled="val.status != '0' || tab != '6' && user.office == '3'?true:false"
                        >
                            SUBMIT
                        </v-btn>
                    
                            <div v-for="(item, i) in approvers" :key="i">
                                <template v-if="user.approver == item.approver">
                                    <v-btn color="error" medium block class="mb-2" :disabled="val.status!= item.status? true : false" @click="openDeclined()">DECLINED</v-btn>
                                    <v-btn color="success" medium block :disabled="val.status!= item.status? true : false" @click="getApproved(user.approver)">{{user.approver != '5'?'CONCURRENCE':'APPROVED'}}</v-btn>
                                </template>
                            </div>
                    </div>
                    
                </v-col>
            </v-card-text>
        </v-card>
        <div v-if="user.office != '-'">
        <!-- declined table -->
            <div v-if="val.declined && tab == val.reportTab">
                <v-card rounded="2"  color="red" class="mt-3" v-if="val.declined.length > 0" @click="declinedDetails()">
                    <v-card-text style="text-align:center;">
                        <p style="font-size:25px;font-weight:bold;">DECLINED HISTORY</p>
                        <p style="font-size:30px;font-weight:bold;">({{val.declined.length}})</p>
                    </v-card-text>
                </v-card>
            </div>
            <DeclinedTable ref="historyDeclined"  />
            <dialog-declined ref="declined" :generate="generate"/> 
            <!-- status timeline -->
            <StatusTimeline  :obj="val" v-if="val.status !='0' && tab == val.reportTab"/>
            <!-- assesement -->
            <dialog-assessment ref="assessmentDialog" :generate="generate" :tab="tab"/> 
            <!-- print -->
            <dialogPrint 
                ref="PrintDialog" 
                :print="print" 
                :printPdf="printPdf"
                :tab="tab"
            />

        </div>
        
    </div>
</template>

<script>
import axiosHttp from '../../axiosHttp'
import{ unitFilter } from '../details/unitFilter.js'
import{ data } from '../details/data.js'
import moment from 'moment'
import { mapState } from 'vuex';
import dialogAssessment from '../dialog/dialogAssessment.vue'
import dialogPrint from '../dialog/dialogPrint.vue'
import StatusTimeline from './statusTimeline.vue';
import DialogDeclined from '../dialog/dialogDeclined.vue'
import DeclinedTable from '../dialog/dialogDeclinedTable.vue'
import {getItem} from '../details/crytp'
    export default {
        components: { 
            dialogAssessment ,
            StatusTimeline,
            dialogPrint,
            DialogDeclined,
            DeclinedTable
        },
        props:[
            'generate',
            'val',
            'print',
            'printPdf',
            'tab'
        ],
      data() {
        return {
            data: data,
            unitFilter : unitFilter,
            generateMonthDialog: false,
            generateDate: null,
            month : moment().format('YYYY-MM'),
            obj : {},
            approvers: [
                { approver: 2, status: 1 },
                { approver: 3, status: 2 },
                { approver: 4, status: 3 },
                { approver: 5, status: 4 },
            ],
            report: '1',
        }
      },
      methods:{
        generateData(){
            this.navData()
            this.generate()
        },
        navData(){
            let obj = {
                formattedDate : this.formattedDate,
                month : this.generateDate,
                report : this.report
            }
            this.$emit('navData',obj)
        },
        submitData(){
            this.$refs.assessmentDialog.openDialog(this.val)
        },
        openPrint(){
            this.$refs.PrintDialog.openDialog()
        },
        openDeclined(){
            this.$refs.declined.openDialog(this.val)
        },
        declinedDetails(){
            let data = this.val.declined
            this.$refs.historyDeclined.openDialog(data)
        },
        getApproved(stats){
            let ask = confirm('Are you sure you want to approved this report?')
            if(ask){
                const noted = this.val.noted
                if(stats != '5'){
                    noted[0][`approved_by${stats}`] = this.user.name
                    noted[0][`approved_date${stats}`] = moment().format('YYYY-MM-DD HH:mm:ss')
                }else{
                    noted[0].noted_by = this.user.name
                    noted[0].noted_date = moment().format('YYYY-MM-DD HH:mm:ss')
                }
                
                axiosHttp.post('approvedReport',{
                    id : this.val.id,
                    status : stats,
                    table : this.val.table,
                    noted : JSON.stringify(noted)
                }).then(()=>{
                    this.generate()
                }).catch((err)=>{
                    alert('Error:Try again')
                })
            }
        }

       
      },
      computed:{
        ...mapState([
            'user'
        ]),
        router() {
            return this.$route.name;
        },
        // function to show month name
        formattedDate() {
                if (this.generateDate) {
                    const date = new Date(this.generateDate);
                    const month = date.toLocaleString('default', { month: 'long' });
                    const year = date.getFullYear();
                    return `${month} ${year}`;
                }
                return null;
        },
        unitItems(){
            const user = getItem('user');
            let data
            if(user.rights == '1' ||  user.hpn == '1'){
                if(['1','3','4','8'].includes(user.restrict)){
                    data = [
                        {text:'My Office', value:'1'},
                        {text:'Unit', value:'2'},
                        // {text:'HPN', value:'3'},
                        {text:'All', value:'3'},
                    ]
                }else{
                    data = [
                        {text:'My Office', value:'1'},
                        // {text:'Unit', value:'2'},
                    ] 
                }
                
            }else{
                if(user.office != '-'){
                    data = [
                        {text:'Sub Unit', value:'1'},
                        {text:'Unit', value:'2'}
                    ]
                }else{
                    data = [
                        {text:'Sub Unit', value:'1'}
                    ]
                }
                
            }
            
                return data
            },
      },
      watch:{
        report(val,oldVal){
            if(val || val != oldVal){
                this.navData()
            }
        },
        // generateDate(val,oldVal){
        //     if(val || val != oldVal){
        //         this.navData()
        //     }
        // }
      },
      mounted(){
        this.navData()
      } 
    }
</script>

<style  scoped>
.input-unit{
    margin-top: 10px;
}
.input-subunit{
    margin-bottom:-40px;margin-top:-30px;
}
</style>