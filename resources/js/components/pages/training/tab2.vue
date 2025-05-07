<template>
    <div>
        <v-container>
            <v-card class="ma-2">
                <v-simple-table class="elevation-1 custom-table" fixed-header>
                    <thead>
                        <tr>
                            <th width="17%">MISSION ESSENTIAL TASK</th>
                            <th width="17%">1st Qtr</th>
                            <th width="17%">2nd Qtr</th>
                            <th width="17%">3rd Qtr</th>
                            <th width="17%">4th Qtr</th>
                            <th width="5%">Required</th>
                            <th width="5%">Actual</th>
                            <th width="5%">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,i) in obj.tableData" :key="i" class="met-table">
                            <td>{{ item.met }}</td>
                            <td v-for="n in 4" :key="n" 
                                :class="item[`q${n}`] && obj.status == '0' && item[`q${n}stats`] != '2' && user.approver == 1?'editable':''"
                            >
                                <div @click="open(item,n,i)">
                                    <div>
                                        <b>{{ item[`q${n}`] }}</b>
                                    </div>
                                    <div class="activity-details" v-if="item[`q${n}activity`]">
                                        <div>
                                            {{ item[`q${n}activity`] }}
                                        </div>
                                        <div>
                                            Performed: {{ item[`q${n}date`] }}
                                        </div>
                                        <div>
                                            Report: {{ item[`q${n}monthString`] }}
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                            </td>
                            <td>{{ item.required }}</td>
                            <td>{{ item.actual }}</td>
                            <td>{{ item.percent?item.percent.toFixed(2)+'%':'' }}</td>
                        </tr>
                        <tr class="met-result">
                            <td colspan="5"></td>
                            <td class="met-font">{{ obj.required }}</td>
                            <td class="met-font">{{ obj.actual }}</td>
                            <td class="met-font">{{ obj.result && obj.result.metPercentage?obj.result.metPercentage.toFixed(2)+'%':'' }}</td>
                        </tr>
                    </tbody>
                </v-simple-table>
            </v-card>
        </v-container>
        
        <TrainingActivities ref="dialogActivity" :obj="obj" :generate="generate" />
    </div>
</template>

<script>
    import { training } from '../../data/training'
    import TrainingActivities from '../../dialog/trainingActivities.vue';
    import {mapState } from 'vuex'
    export default {
        components:{
            TrainingActivities
        },
        props:[
            'generate',
            'obj'
        ],
        data() {
            return {
                trainingJs : training,
                edit:false,
                dialog:false
            }
        },
        methods:{
            open(item,i,key){
                if(this.obj.status == '0' && item[`q${i}stats`] != '2' && this.user.approver == 1){
                    this.$refs.dialogActivity.openDialog(item,i,key)
                }
            }
        },
        computed:{
            ...mapState([
                'user'
            ])
        },
    }
</script>

<style scoped>
.met-table{
    height: 10vh !important;
}
.met-result{
    height: 10vh !important;
}
.met-font{
    font-size:30px !important;
}
.activity-details{
    font-size:10px !important;
}
</style>