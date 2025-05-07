<template>
    <div>
        <!-- {{ tableData }} -->
        <v-card max-width="100%">
            <div v-if="tableData.length > 0 ">
                <v-simple-table
                    fixed-header
                    class="elevation-1 custom-table"
                >
                    <thead>
                        <tr>
                            <th>Key Measurement Area (KMA)</th>
                            <th>Rating</th>
                            <th>RedCon Level</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr v-for="(item,i) in tableData" :key="i" >
                        
                            <td :class="item && item.total?'total text-h4':''">{{ item?item.column:'' }}
                                <template v-if="item && item.tab">
                                    <v-icon v-if="status(item)" color="success">mdi-check-circle</v-icon>
                                    <v-icon v-else color="red">mdi-alpha-x-circle</v-icon>
                                </template>
                            
                            </td>
                            <td :class="item && item.total?'total text-h4':''">{{ item?item.readiness.toFixed(2) + '%':'' }}</td>
                            <td :class="item && item.readiness?red.redStyle(red.redCon(item.readiness)):''">{{ item && item.readiness?red.redCon(item.readiness):'' }}</td>
                        </tr>
                        
                    </tbody>
                </v-simple-table>
            </div>
            
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
    import{ data } from '../../details/data.js'
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
                alert : data
            }
        },
        methods:{
            status(item){
                if(item.tab){
                    let data = false
                    this.obj.tabStatus.forEach(r => {
                        if(r.tabNo == item.tab && r.tabStatus == '5'){
                            console.log(r)
                            data = true
                        }
                    });
                    return data
                }
                
            }
        },
        computed:{
            tableData(){
                const tables = [
                    { table: 'personnel', name: 'Personnel Readiness',tab:1 },
                    { table: 'training', name: 'Training Readiness',tab:2 },
                    { table: 'equipment', name: 'Equipment Readiness',tab:3 },
                    { table: 'maintenance', name: 'Maintenance Readiness',tab:4 },
                    { table: 'facility', name: 'Facility Readiness',tab:5 }
                ];
                let totalReadiness = 0;
                let countReadiness = 0;
                let overallReadiness = 0;
                const data = [];
                tables.forEach((table) => {
                    const name = table.name;
                    const tab = table.tab;
                    const item = this.readiness[table.table];

                    if (item) {
                        const val = item
                        if (val && val.readiness) {
                            totalReadiness += val.readiness;
                            countReadiness += 1;
                            item.tab = tab;
                            item.column = name;
                            item.readiness = val.readiness;
                        }
                    }
                    data.push(item);
                });

                if (countReadiness > 0) {
                    overallReadiness = totalReadiness / countReadiness;
                }

                const overallRating = { column: 'OVERALL RATING', readiness: overallReadiness, total: true };
                data.push(overallRating);
                
                setTimeout(() => {
                    this.alert.overlay = false
                }, 1500);

                return data
                
            },

        }
    }
</script>

<style  scoped>
.total{
    font-weight: bold;
}
</style>