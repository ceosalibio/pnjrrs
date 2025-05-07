<template>
    <div>
        <v-container fluid>
            <div align="center">
                <h1>Readiness Report</h1>
                <h2>{{ nav.formattedDate }}</h2>
            </div>
            <v-divider  />
            <v-row v-if="nav.report != '1'">
                <v-col cols="12" md="12" lg="12" xl="12">
                    <v-tabs  justify-center v-model="tab">
                        <v-tab href="#1" @click="goTab('1')" v-if="user.office == '1' || user.hpn == 1 && user.office == '3'">PERSONNEL</v-tab>
                        <v-tab href="#2" @click="goTab('2')" v-if="user.office == '8' || user.hpn == 1 && user.office == '3'">TRAINING</v-tab>
                        <v-tab href="#3" @click="goTab('3')" v-if="user.office == '4' || user.hpn == 1 && user.office == '3'">EQUIPMENT</v-tab>
                        <v-tab href="#4" @click="goTab('4')" v-if="user.office == '4' || user.hpn == 1 && user.office == '3'">MAINTENANCE</v-tab>
                        <v-tab href="#5" @click="goTab('5')" v-if="user.office == '4' || user.hpn == 1 && user.office == '3'">FACILITY</v-tab>
                        <v-tab href="#6" @click="goTab('6')" v-if="user.office == '3' || user.hpn == 1 && user.office == '3'">OVERALL</v-tab>
                    </v-tabs>
                </v-col>
            </v-row>
            <v-row >
                <v-col cols="12" md="3" lg="3" xl="3">
                    <Navigation
                        @navData="navData"
                        :generate="generate"
                        :val="obj"
                        :print="obj"
                        :printPdf="printPdf"
                        :tab="tab"
                    
                       
                    />
                </v-col>
                <v-col cols="12" md="9" lg="9" xl="9" v-if="nav.report != '1'">
                    <v-tabs-items v-model="tab">
                        
                        <!-- tab 1 personnel-->
                            <v-tab-item :value="'1'">
                      
                                <Tab1  
                                    :generate="generate"
                                    :obj="obj"
                                    :nav="nav"
                                    :readiness="readiness"
                                />
                            </v-tab-item>
                            <!-- tab 2 training-->
                            <v-tab-item :value="'2'">
                        
                                    <Tab2 
                                        :generate="generate"
                                        :obj="obj"
                                        :readiness="readiness"
                                    />
                            
                            </v-tab-item>
                        
                            <!-- tab 3 equipment-->
                            <v-tab-item :value="'3'">
                                <v-container>
                                    <Tab3 
                                        :obj="obj" 
                                        :readiness="readiness"
                                    />
                                </v-container>
                            </v-tab-item>

                            <!-- tab 4 maintenance-->
                            <v-tab-item :value="'4'">
                                <v-container>
                                    <Tab4 
                                        :obj="obj" 
                                        :readiness="readiness"
                                    />
                                </v-container>
                            </v-tab-item>

                            <!-- tab 5 facility-->
                            <v-tab-item :value="'5'">
                                <v-container>
                                    <Tab5 
                                        :obj="obj" 
                                        :readiness="readiness"
                                    />
                                </v-container>
                            </v-tab-item>

                            <!-- tab 6 over all-->
                            <v-tab-item :value="'6'">
                                <v-container>
                                    <Tab6 
                                        :obj="obj" 
                                        :readiness="readiness"
                                    />
                                </v-container>
                            </v-tab-item>
                    </v-tabs-items>
                </v-col>
                <!-- offices -->

                <v-col v-else cols="12" md="9" lg="9" xl="9">
                    <v-container >
                        <Office  
                            class="mr-2"
                            :obj="obj"
                        />
                    </v-container>
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
        <PrintData ref="PrintData" :tab="tab" :readiness="readiness"/>
        <!-- print excel -->
        <!-- <ExcelData ref="ExcelData"  :excelData="obj"/> -->
    </div>
</template>

<script>
    import Navigation from '../../layouts/navigationReport.vue'
    import Office from './office.vue'
    import Tab1 from './tab1.vue'
    import Tab2 from './tab2.vue'
    import Tab3 from './tab3.vue'
    import Tab4 from './tab4.vue'
    import Tab5 from './tab5.vue'
    import Tab6 from './tab6.vue'
    import moment from 'moment'
    import{ unitFilter } from '../../details/unitFilter.js'
    import{ data } from '../../details/data.js'
    import  total  from './readiness'
    import  all  from './readinessAll'
    import { encryptData, decryptData, decryptBase } from '../../details/crytp'
    import { mapState } from 'vuex'
    import axiosHttp from '../../../axiosHttp'
    import PrintData from './pdf.vue'
    // import ExcelData from './excel.vue'
    export default {
        components:{
            Navigation,
            Office,
            Tab1,
            Tab2,
            Tab3,
            Tab4,
            Tab5,
            Tab6,
            PrintData
        },
        data() {
            return {
                tab:'1',
                nav:{},
                data : data,
                obj:{},
                print :{},
                readiness :{}
            }
        },
        methods:{
            goTab(tab){
                this.tab = tab
            },
            navData(val){
                this.nav = {...val}
            },
           
            generate(){
                // this.obj = {}
                data.overlay = true
                if(!data.department && !data.section){
                    unitFilter.unitInformation(data.unitCode)
                }
                axiosHttp.post('getReport',{
                    report : this.nav.report,
                    unit : data.unitCode,
                    month : this.nav.month,
                    name : this.user.name,
                    restriction : this.user.office,
                    dept : this.user.departmentcode
                }).then((res)=>{
                    if(res.data == 'no'){
                        alert('No data')
                    }else{
                        let item = decryptBase(res.data)
                        // if(this.user.office != '-' ){
                        if(this.nav.report != '1' ){
                            axiosHttp.post('reportRestriction',{
                                report : this.nav.report,
                                unit : data.unitCode,
                                month : this.nav.month,
                                name : this.user.name,
                                restriction : this.user.office,
                                dept : this.user.departmentcode,
                                tab: this.tab
                            }).then((rec) =>{
                                let value = decryptBase(rec.data)
                                let val = value[0]
                                this.obj = {
                                    id : val.id,
                                    tableData :  this.nav.report != '3'?item:all.getReadiness(item),
                                    assessment : JSON.parse(val.assessment),
                                    recommend : val.recommend,
                                    declined : JSON.parse(val.declined),
                                    noted : JSON.parse(val.noted),
                                    status : val.status,
                                    month : this.nav.month,
                                    monthString : moment(this.nav.month).format('MMMM YYYY'),
                                    reportTab : val.tab,
                                    tabStatus : val.tabStatus,
                                    allAssessment : val.allAssessment,
                                    // required all data below
                                    tab:this.tab,
                                    unit : data.unitCode,
                                    logo : `${this.user.departmentcode}.png`,
                                    department : this.user.department,
                                    team : this.user.offices != '1'?this.user.section:this.user.team,
                                    office : this.user.offices,
                                    hpn : this.user.hpn,
                                    rest : this.user.restrict,
                                    table : 'reports'
                                }
                               
                                this.readiness = total.getReadiness(this.obj)
                                console.log(this.obj)
                             
                                if(this.tab != '6'){
                                    data.overlay = false
                                }else{
                                    if(this.nav.report != '1'){
                                        data.overlay = true
                                    }else{
                                        data.overlay = false
                                    }
                                }
                            }).catch((err)=>{
                                alert('Report are not completed')
                                console.log(err)
                                data.overlay = false
                                
                            })
                           
                            
                        }else{
                            this.obj = {
                                tableData :  this.nav.report != '3'?item:all.getReadiness(item),
                            }
                            if( this.nav.report != '1'){
                                this.readiness = total.getReadiness(this.obj)
                            }
                            data.overlay = false
                        }       
                    }
                    
                }).catch((err)=>{
                    alert('Report are not completed')
                    console.log(err)
                    data.overlay = false
                    
                })
            },

            printPdf(item){
                this.$refs.PrintData.getPrint(item,this.tab)
            },
            printExcel(item){
                this.$refs.ExcelData.getExcel(item)
            }
        },
        computed:{
            ...mapState([
                'user'
            ]),
        
        },
        watch:{
            tab(val){
                if(val == '6' && this.nav.month){
                    this.generate()
                }else if(['3','4','5'].includes(val) && this.user.office == '4' && this.nav.month){
                    this.generate()
                }
            }
        },
        
        mounted(){
            unitFilter.getDepartment()
            data.unitCode = this.user.unitcode

            switch (this.user.office) {
                case '1':
                    this.tab = '1'
                    break;
                case '8':
                    this.tab = '2'
                    break;
                case '4':
                    this.tab = '3'
                    break;
                case '3':
                    this.tab = '6'
                    break;
            
                default:
                    break;
            }
        },
    }
</script>

<style  scoped>

</style>