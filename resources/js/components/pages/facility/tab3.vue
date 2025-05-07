<template>
    <div>
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header>
                <thead>
                    <tr>
                        <th>Category<br />(a)</th>
                        <th>Required<br />Area<br />(b)</th>
                        <th>Actual<br />Area<br />(c)</th>
                        <th>Quantitative<br />(c/d x 100)<br />(d)</th>
                        <th>Qualitative<br />(e)</th>
                        <th>Total<br />(d + e) / 2</th>
                        <th>Percentage<br />(Weighted<br />distribution)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in name" :key="i">
                        <td>{{ obj.master[`${item}name`] }}</td>
                        <td>{{ obj.master[`${item}required`] }}</td>
                        <td>{{ obj.master[`${item}actual`] }}</td>
                        <td>{{ obj.master[`${item}quantitativeResult`].toFixed(2) + '%' }}</td>
                        <td>{{ obj.master[`${item}qualitativeResult`].toFixed(2) + '%' }}</td>
                        <td>{{ obj.master[`${item}totalRating`].toFixed(2) + '%' }}</td>
                        <td>{{ obj.master[`${item}weight`].toFixed(2) + '%' }}</td>
                    </tr>
                    <tr>
                       <td colspan="6" class="font-weight-bold text-right">TOTAL</td>     
                       <td class="font-weight-bold">{{ obj.result.readiness.toFixed(2) + '%' }}</td>     
                    </tr> 
                    <tr>
                        <td colspan="6" class="font-weight-bold text-right">REDCON STATUS</td>     
                        <td :class="obj.result?red.redStyle(red.redCon(obj.result.readiness)):''">{{ obj.result?red.redCon(obj.result.readiness):'' }}</td>     
                     </tr>   
                </tbody>
            </v-simple-table>
        </v-card>
                    
        <!-- assessment table -->
        <assessmentTable 
            :obj="obj"
        />
    </div>
</template>

<script>
    import{ red } from '../../details/redcon.js'
    import assessmentTable from '../../layouts/assessmentTable.vue'
    export default {
        components: {
            assessmentTable,
        },
        props:[
            'obj'
        ],
        data() {
            return {
                red : red,
                name : ['pa_','sa_','hs_','bp_']
            }
        },
            
    }
</script>

<style  scoped>
.detail-title{
    font-weight: bold;
}
.readiness{
    font-weight: bold;
}
</style>