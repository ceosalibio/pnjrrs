<template>
    <div >
        <v-card elevation="3">
            <v-card-title class="d-flex justify-center vcard-title">
              CONTROL  
            </v-card-title>
            <v-card-text>
                <v-col cols="12">
                    <br>
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
                                class="text-center input-unit" 
                            />
                        </template>
                        <v-date-picker v-model="generateDate" type="month" scrollable :max="month">
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="generateMonthDialog = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="[$refs.dialog.save(generateDate),navData()]">OK</v-btn>
                        </v-date-picker>
                    </v-dialog>
                </v-col>
                <v-col cols="12">
                    <v-autocomplete  
                        label="Status"
                        outlined
                        dense
                        v-model="status"
                        :items="statusList"
                        item-text="text"
                        item-value="value"
                        class="input-subunit" 
                        @change="navData()"
                    />
                </v-col>
                <v-col cols="12">
                    <v-autocomplete  
                        label="Report"
                        outlined
                        dense
                        v-model="report"
                        :items="reportList"
                        item-text="text"
                        item-value="value"
                        @change="navData()"
                    />
                </v-col>
                <v-col cols="12">
                    <v-autocomplete 
                        label="Unit"
                        dense
                        outlined
                        v-model="unit"
                        :items="list.departmentList"
                        item-text="name"
                        item-value="code"
                        class="input-report" 
                        @change="navData()"
                    />
                </v-col>
                <v-col cols="12">
                    <v-btn 
                        color="success" 
                        block 
                        :disabled="generateDate && unit?false:true"
                        @click="generate()"
                    >
                        VIEW
                    </v-btn>
                </v-col>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    
    import { unitFilter } from '../../details/unitFilter'
    import { list } from '../../details/list'
    import { data } from '../../details/data'
    import moment from 'moment'
    export default {
        props:[
            'generate',
            'obj'
        ],
        data() {
            return {
                generateMonthDialog: false,
                generateDate: null,
                month : moment().format('YYYY-MM'),
                status:'1',
                statusList:[
                    {text:'ALL', value:'1'},
                    {text:'Submitted', value:'2'},
                    {text:'Not Submitted', value:'3'}
                ],
                report:'personnels',
                reportList:[
                    {text:'Personnel', value:'personnels'},
                    {text:'Training', value:'trainings'},
                    {text:'Equipment', value:'equipments'},
                    {text:'Maintenance', value:'maintenances'},
                    {text:'Facility', value:'facilities'}
                   
                ],
                list : list,
                data : data,
                unit:''
            }
        },
        methods:{
            navData(){
                if(this.obj && this.obj.tableData){
                    this.obj.tableData = []
                }
                let obj = {
                    month : this.generateDate,
                    status : this.status,
                    report : this.report,
                    unit : this.unit
                }
                this.$emit('navData',obj)
            }
        },
        computed:{
            formattedDate() {
                if (this.generateDate) {
                    const date = new Date(this.generateDate);
                    const month = date.toLocaleString('default', { month: 'long' });
                    const year = date.getFullYear();
                    return `${month} ${year}`;
                }
                return null;
            },
        },
        mounted(){
            unitFilter.getDepartment()
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
.input-report{
    margin-top:-30px;
}
</style>