<template>
    <div>
        <v-container fluid>
            <div align="center">
                <h1>Maintenance Readiness</h1>
                <h2>{{ nav.formattedDate }}</h2>
            </div>
            <v-divider  />
            <v-row v-if="Object.keys(obj).length > 0">
                <v-col cols="12" md="12" lg="12" xl="12">
                    <v-tabs  justify-center v-model="tab">
                        <v-tab href="#1" @click="goTab('1')" >MAINTENANCE ITEM DETAILS</v-tab>
                        <v-tab href="#2" @click="goTab('2')" >MAINTENANCE READINESS REPORT</v-tab>
                    </v-tabs>
                </v-col>
            </v-row>
            <v-tabs-items v-model="tab">
                <!-- tab 1 -->
                <v-tab-item :value="'1'">
                    <v-row>
                        <v-col cols="12" md="3" lg="3" xl="3">
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
                        <v-col cols="12" md="9" lg="9" xl="9">
                            <v-card elevation="3">
                                <!-- personnel table (tab 1) -->
                                <Tab1  
                                    :generate="generate"
                                    :obj="obj"
                                    :nav="nav"
                                    id="table"
                                />
                            </v-card>
                        </v-col>
                    </v-row>
                </v-tab-item>
                <!-- tab 2 -->
                <v-tab-item :value="'2'">
                    <v-container>
                        <Tab2 
                            :obj="obj"

                        />
                    </v-container>
                </v-tab-item>
            </v-tabs-items>
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
            generate(){
                // this.obj = {}
                data.overlay = true
                if(!data.department && !data.section){
                    unitFilter.unitInformation(data.unitCode)
                }
                axiosHttp.post('getMaintenance',{
                    unit : data.unitCode,
                    month : this.nav.month,
                    name : this.user.name
                }).then((res)=>{
                    
                    if(res.data == 'no'){
                        alert('No equiment table submitted')
                    }else{
                        let item = decryptBase(res.data)
                        let val = item[0]
                        // console.log(val)
                        this.obj = {
                            id : val.id,
                            tableData :  val.details,
                            assessment : JSON.parse(val.assessment),
                            declined : JSON.parse(val.declined),
                            noted : JSON.parse(val.noted),
                            result : val.result,
                            master : val.master,
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
                            table : 'maintenances',
                        }
                      console.log(this.obj)
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