<template>
    <div>   
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header height="80vh" id="table" ref="table">
                <thead>
                    <tr>
                        <th>UNIT</th>
                        <th>TO</th>
                        <th>Actual<br>Assigned</th>
                        <th>PSGR<br>(Actual<br>/TO x<br>100)</th>
                        <th>Rank<br>(Nr<br>/Aligned)<br>PRR</th>
                        <th>Specialty<br>(Nr<br>/Aligned)<br>FOS<br>and<br>Rating)<br>PSR</th>
                        <th>PRR<br>(Aligned<br>/Actual x<br>100)</th>
                        <th>PSR<br>(Aligned<br>/Actual x<br>100)</th>
                        <th>READINESS</th>
                        <th>RedCon</th>
                    </tr>
                </thead>
                 <tbody>
                    <tr v-for="(item,i) in tableData" :key="i">
                        <td class="font-weight-bold">{{item.unit}}</td>
                        <td>{{item.required}}</td>
                        <td>{{item.actual}}</td>
                        <td>{{item.result?item.result.psgRating.toFixed(2) + '%':''}}</td>
                        <td>{{item.grade}}</td>
                        <td>{{item.afpos}}</td>
                        <td>{{item.result?item.result.gradeRating.toFixed(2) + '%':''}}</td>
                        <td>{{item.result?item.result.afposRating.toFixed(2) + '%':''}}</td>
                        <td>{{item.result?item.result.readiness.toFixed(2) + '%':''}}</td>
                        <td :class="item.result?red.redStyle(red.redCon(item.result.readiness)):''">{{ item.result?red.redCon(item.result.readiness):'' }}</td>
                    </tr>
                    <tr class="font-weight-bold" v-if="readiness.personnel">
                        <td class="total-bg">{{ readiness.personnel.name }}</td>
                        <td class="total-bg">{{ readiness.personnel.required }}</td>
                        <td class="total-bg">{{ readiness.personnel.actual }}</td>
                        <td class="total-bg">{{ readiness.personnel.psgRating.toFixed(2) + '%' }}</td>
                        <td class="total-bg">{{ readiness.personnel.grade }}</td>
                        <td class="total-bg">{{ readiness.personnel.afpos }}</td>
                        <td class="total-bg">{{ readiness.personnel.gradeRating.toFixed(2) + '%' }}</td>
                        <td class="total-bg">{{ readiness.personnel.afposRating.toFixed(2) + '%' }}</td>
                        <td class="total-bg">{{ readiness.personnel.readiness.toFixed(2) + '%' }}</td>
                        <td :class="readiness.personnel.readiness?red.redStyle(red.redCon(readiness.personnel.readiness)):''">{{ readiness.personnel.readiness?red.redCon(readiness.personnel.readiness):'' }}</td>
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
        components: {
            assessmentTable,
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
        methods:{
        
        },
        computed:{
            tableData(){
                if(this.obj.tableData){
                    return this.obj.tableData.personnels
                }else{
                    return []
                }
            }
        }

    }
</script>

<style  scoped>

</style>