<template>
    <div>   
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header height="80vh" id="table" ref="table">
                <thead>
                    <tr>
                        <th>UNIT</th>
                        <th>Percentage<br>ORAC</th>
                        <th>Percentage<br>METT</th>
                        <th>READINESS</th>
                        <th>RedCon</th>
                    </tr>
                </thead>
                 <tbody>
                    <tr v-for="(item,i) in tableData" :key="i">
                        <td class="font-weight-bold">{{item.unit}}</td>
                        <td>{{item.result?item.result.oracValue.toFixed(2) + '%':''}}</td>
                        <td>{{item.result?item.result.metValue.toFixed(2) + '%':''}}</td>
                        <td>{{item.result?item.result.readiness.toFixed(2) + '%':''}}</td>
                        <td :class="item.result?red.redStyle(red.redCon(item.result.readiness)):''">{{ item.result?red.redCon(item.result.readiness):'' }}</td>
                    </tr>
                    <tr class="font-weight-bold" v-if="readiness.training">
                        <td class="total-bg">{{ readiness.training.name }}</td>
                        <td class="total-bg">{{ readiness.training.oracValue.toFixed(2) + '%' }}</td>
                        <td class="total-bg">{{ readiness.training.metValue.toFixed(2) + '%' }}</td>
                        <td class="total-bg">{{ readiness.training.readiness.toFixed(2) + '%' }}</td>
                        <td :class="readiness.training.readiness?red.redStyle(red.redCon(readiness.training.readiness)):''">{{ readiness.training.readiness?red.redCon(readiness.training.readiness):'' }}</td>
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card>

        <assessmentTable 
            :obj="obj"
            v-if="obj.reportTab != '6'"
        />
    </div>
</template>

<script>
    import assessmentTable from '../../layouts/assessmentTable.vue'
    import{ red } from '../../details/redcon.js'
    export default {
        components:{
            assessmentTable
        },
        props:[
            'obj',
            'readiness'
        ],
        data() {
            return {
                red : red,
            }
        },
        computed:{
            tableData(){
                if(this.obj.tableData){
                    return this.obj.tableData.trainings
                }else{
                    return []
                }
            }
        }

    }
</script>

<style  scoped>

</style>