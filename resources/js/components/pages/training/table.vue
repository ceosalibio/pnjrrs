<template>
    <div>
        <v-container fluid>
            <div align="center">
                <h1>Training Readiness</h1>
                <h2>{{ nav.formattedDate }}</h2>
            </div>
            <v-divider  />
            <v-row v-if="Object.keys(obj).length > 0">
                <v-col cols="12" md="12" lg="12" xl="12">
                    <v-tabs  justify-center v-model="tab">
                        <v-tab href="#1" @click="goTab('1')" >OPERATIONALLY READY & AVAILABLE CREW</v-tab>
                        <v-tab href="#2" @click="goTab('2')" >MISSION ESSENNTIAL TASK TRAINING</v-tab>
                        <v-tab href="#3" @click="goTab('3')" >TRAINING READINESS REPORT</v-tab>
                    </v-tabs>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="3" lg="3" xl="3" v-show="tab != '3'">
                    <Navigation
                        @navData="navData"
                        :generate="generate"
                        :val="obj"
                        :print="obj"
                        :printPdf="printPdf"
                        :printExcel="printExcel"
                        :tab="tab"
                       
                    />
                </v-col>
                <v-col cols="12" :md="tab != '3'?'9':'12'" :lg="tab != '3'?'9':'12'" :xl="tab != '3'?'9':'12'">
                    <v-tabs-items v-model="tab">
                        
                        <!-- tab 1 -->
                            <v-tab-item :value="'1'">
                                
                                
                                <!-- training table (tab 1) -->
                                <Tab1  
                                    :generate="generate"
                                    :obj="obj"
                                    :nav="nav"
                                />
                            </v-tab-item>
                            <!-- tab 2 -->
                            <v-tab-item :value="'2'">
                        
                                    <Tab2 
                                        :generate="generate"
                                        :obj="obj"
                                    />
                            
                            </v-tab-item>
                        
                        <!-- tab 3 -->
                        <v-tab-item :value="'3'">
                            <v-container>
                                <Tab3 
                                    :obj="obj" 
                                />
                            </v-container>
                        </v-tab-item>
                    </v-tabs-items>
                </v-col>
            </v-row>
        </v-container>
        <!-- overlay -->
        <v-overlay :value="data.overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
        <!-- print pdf -->
        <PrintData ref="PrintData" />
        <!-- print excel -->
        <ExcelData ref="ExcelData"  :excelData="obj"/>
    </div>
</template>

<script>
    import Navigation from '../../layouts/navigation.vue'  
    import Tab1 from './tab1.vue'
    import Tab2 from './tab2.vue'
    import Tab3 from './tab3.vue'
    import moment from 'moment'
    import{ unitFilter } from '../../details/unitFilter.js'
    import{ data } from '../../details/data.js'
    import { encryptData, decryptData, decryptBase } from '../../details/crytp'
    import { mapState } from 'vuex'
    import axiosHttp from '../../../axiosHttp'
    import PrintData from './pdf.vue'
    import ExcelData from './excel.vue'
    export default {
        components:{
            Navigation,
            Tab1,
            Tab2,
            Tab3,
            PrintData,
            ExcelData
        },
        data() {
            return {
                tab:'1',
                nav:{},
                data : data,
                obj:{},
                print :{},
            }
        },
        methods:{
            goTab(tab){
                this.tab = tab
            
            },
            navData(val){
                this.nav = {...val}
            },
            generate(tab){
                this.obj = {}
                data.overlay = true
                if(!data.department && !data.section){
                    unitFilter.unitInformation(data.unitCode)
                }
                axiosHttp.post('getTraining',{
                    unit : data.unitCode,
                    month : this.nav.month,
                    name : this.user.name
                }).then((res)=>{
                    
                    if(res.data == 'no'){
                        alert('No uploaded training')
                    }else{
                        let item = decryptBase(res.data)
                        let val = item[0]
                        this.obj = {
                            id : val.id,
                            tableData :  val.details,
                            activity : val.activity,
                            assessment : JSON.parse(val.assessment),
                            declined : JSON.parse(val.declined),
                            noted : JSON.parse(val.noted),
                            result : val.result,
                            personnelrequired : val.personnelRequired,
                            personnelactual : val.personnelActual,
                            required : val.required,
                            actual : val.actual,
                            orac : val.orac,
                            reason : val.reason,
                            status : val.status,
                            month : this.nav.month,
                            monthString : moment(this.nav.month).format('MMMM YYYY'),
                            // required all data below
                            tab:this.tab,
                            unit : data.unitCode,
                            logo : `${this.user.departmentcode}.png`,
                            department : this.user.department,
                            team : this.user.offices != '1'?this.user.section:this.user.team,
                            office : this.user.offices,
                            hpn : this.user.hpn,
                            rest : this.user.restrict,
                            table : 'trainings',
                        }

                     
                    }
                }).catch((err)=>{
                    alert('Error:Try again')
                    console.log(err)
                    
                }).finally(()=>{
                    data.overlay = false
                })
            },

            printPdf(item){
                this.$refs.PrintData.getPrint(item)
            },
            printExcel(item){
                this.$refs.ExcelData.getExcel(item)
            }
        },
        computed:{
            ...mapState([
                'user'
            ])
        },
        
        mounted(){
            unitFilter.getDepartment()
            data.unitCode = this.user.unitcode
        },
    }
</script>

<style  scoped>

</style>