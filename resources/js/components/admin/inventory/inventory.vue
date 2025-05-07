<template>
    <div>
        <v-container fluid>
            <div align="center">
                <h1>Inventory Table</h1>
            </div>
            <div class="text-h5" align="center">
                {{ departmentName }}
            </div>
            <div class="text-h6" align="center">
                {{ formattedDate }}
            </div>
            <div class="text-h6" align="center">
                {{ tabName }}
            </div>
            <v-divider  />
            <v-row>
                <v-col cols="12" md="12" lg="12" xl="12">
                    <v-tabs  justify-center v-model="tab">
                        <v-tab href="#1" @click="goTab('1')" v-if="['4','11'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">MOBILITY</v-tab>
                        <v-tab href="#2" @click="goTab('2')" v-if="['4'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">WEAPON</v-tab>
                        <v-tab href="#3" @click="goTab('3')" v-if="['4','6'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">COMMUNICATION</v-tab>
                        <v-tab href="#4" @click="goTab('4')" v-if="['1','4'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">MEDICAL</v-tab>
                        <v-tab href="#5" @click="goTab('5')" v-if="['1','4'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">DENTAL</v-tab>
                        <v-tab href="#6" @click="goTab('6')" v-if="['4'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">QUARTERMASTER</v-tab>
                        <v-tab href="#7" @click="goTab('7')" v-if="['4'].includes(user.restrict) || user.hpn == 1 && user.office == '3'">ALL</v-tab>
                    </v-tabs>
                </v-col>
            </v-row>
            <v-container>
                <v-row>
                    <v-col cols="12" md="3" lg="3" xl="3">
                        <v-autocomplete 
                            label="Unit"
                            dense
                            outlined
                            v-model="unit"
                            :items="addNewDepartment"
                            item-text="name"
                            item-value="code"
                        />
                    </v-col>
                    <v-col cols="12" md="3" lg="3" xl="3">
                        <v-dialog ref="dialog" v-model="generateMonthDialog" :return-value.sync="generateDate" persistent width="290px">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field v-model="formattedDate" 
                                label="Select Month" prepend-inner-icon="mdi-calendar" 
                                readonly 
                                v-bind="attrs" v-on="on" 
                                dense 
                                outlined 
                                class="text-center" 
                                />
                            </template>
                            <v-date-picker v-model="generateDate" type="month" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="generateMonthDialog = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="[$refs.dialog.save(generateDate),tableData = []]">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>
                    <v-col cols="12" md="2" lg="2" xl="2">
                        <v-btn color="warning" medium block @click="generate()"  class="mb-2" :disabled=" unit && generateDate?false:true">GENERATE</v-btn>
                    </v-col>
                    <v-col cols="12" md="2" lg="2" xl="2">
                        <v-btn color="primary" medium block @click="print()"  class="mb-2" :disabled="tableData.length > 0?false:true">PRINT</v-btn>
                    </v-col>
                </v-row>
                <!-- total man power -->
                <v-row v-show="generateDate">
                    <v-col cols="12" md="12" lg="12" xl="12">
                        <v-card color="yellow" height="10vh" class="d-flex align-center justify-center">
                            <div class="text-h3" align="center">
                                TOTAL MANPOWER : {{ manpower }}
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
                <!-- table data -->
                <v-row>
                    <v-col cols="12" md="12" lg="12" xl="12">
                       
                        <div class="mt-4">
                            
                            <tabledata 
                                :table="tableData"
                                :tab="tab"
                            />
                        </div>
                    </v-col>
                </v-row>

            </v-container>
        </v-container>
        <print 
            ref="print"
            :unit="unit"
            :tabName="tabName"
            :tab="tab"
            :unitName="departmentName"
            :month="formattedDate"
        />

        <!-- overlay -->
        <v-overlay :value="data.overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import { unitFilter } from '../../details/unitFilter'
    import { list } from '../../details/list'
    import { data } from '../../details/data'
    import axiosHttp from '../../../axiosHttp'
    import tabledata from './table.vue';
    import print from './pdf.vue';
    import { decryptBase } from '../../details/crytp'
    export default {
        components:{
            tabledata,
            print
        },
        data:()=>({
            tab: '1',
            list : list,
            data : data,
            unit:null,
            generateMonthDialog: false,
            generateDate:null,
            manpower:0,
            tableData:[]
            
        }),
        methods:{
            goTab(val){
                this.tab = val
            },
            generate(){
                data.overlay = true
                axiosHttp.post('getInventory',{
                    unit : this.unit,
                    month : this.generateDate,
                    name : this.user.name,
                    tab : this.tab
                }).then((res)=>{
                    let val = decryptBase(res.data)
                    // let val = res.data
                    this.manpower = val.manpower
                    this.tableData = val.table
                    data.overlay = false
                }).catch(err=>{
                    alert('Error: Try again')
                    console.error(err)
                    data.overlay = false
                })
            },
            print(){
                this.$refs.print.getPrint(this.tableData)
            }
        },
        computed:{
            ...mapState([
                'user'
            ]),
            formattedDate() {
                if (this.generateDate) {
                    const date = new Date(this.generateDate);
                    const month = date.toLocaleString('default', { month: 'long' });
                    const year = date.getFullYear();
                    return `${month} ${year}`;
                }
                return null;
            },
            addNewDepartment() {
                let obj = { name: 'ALL', code: 'all' };
                let data = list.departmentList.concat(obj);
                return data;
            },
            departmentName(){
                const department = this.addNewDepartment.find(r => r.code === this.unit);
                if(department){
                    if(department.name != 'ALL'){
                        return department.name
                    }else{
                        return 'PHILIPPINE NAVY'
                    }
                }
            
            },
            tabName(){
                let data = ''
                switch (this.tab) {
                        case '1':
                            data = 'MOBILITY'
                            break;
                        case '2':
                            data = 'WEAPON'
                            break;
                        case '3':
                            data = 'COMMUNICATION'
                            break;
                        case '4':
                            data = 'MEDICAL'
                            break;
                        case '5':
                            data = 'DENTAL'
                            break;
                        case '6':
                            data = 'QUARTERMASTER'
                            break;
                        case '7':
                            data = 'ALL'
                            break;
                    }
                return data;
            }
        },
        watch:{
            tab(val){
                if(val){
                    this.generate()
                    switch (this.user.restrict) {
                        case '11':
                            tab = '1'
                            break;
                        case '6':
                            tab = '3'
                            break;
                        case '1':
                            tab = '4'
                            break;
                        default:
                            tab = '7'
                            break;
                    }
                    
                }
            }
        },
        mounted(){
            unitFilter.getDepartment()
        }
    }
</script>

<style scoped>
.text-color{
    color:white;
}
</style>