<template>
    <div>
        <v-card>
            <v-simple-table class="elevation-1 custom-table">
                <thead>
                    <tr>
                        <th>PERCENTAGE<br>ORAC</th>
                        <th>PERCENTAGE<br>METT</th>
                        <th>TRR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ obj.result.oracPercentage?obj.result.oracPercentage.toFixed(2)+'%':0 }}</td>
                        <td>{{ obj.result.metPercentage?obj.result.metPercentage.toFixed(2)+'%':0 }}</td>
                        <td rowspan="2"><b>{{ obj.result.readiness?obj.result.readiness.toFixed(2)+'%':0 }}</b></td>
                    </tr>
                    <tr>
                        <td>{{ obj.result.oracValue?obj.result.oracValue.toFixed(2)+'%':0 }}</td>
                        <td>{{ obj.result.metValue?obj.result.metValue.toFixed(2)+'%':0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td :class="obj.result.readiness?red.redStyle(red.redCon(obj.result.readiness)):''">{{ obj.result.readiness?red.redCon(obj.result.readiness):'' }}</td>
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
            }
        },
    }
</script>

<style  scoped>

</style>