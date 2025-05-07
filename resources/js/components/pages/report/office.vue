<template>
    <div>
        <v-card max-width="100%">
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
                <tbody>
                    <tr v-for="(item,i) in obj.tableData" :key="i">
                        <template v-if="item && item.column">
                            <td :class="item.total?'total text-h4':''">{{ item.column }}</td>
                            <td :class="item.total?'total text-h4':''">{{ item.readiness.toFixed(2) + '%' }}</td>
                            <td :class="item.readiness?red.redStyle(red.redCon(item.readiness)):''">{{ item.readiness?red.redCon(item.readiness):'' }}</td>
                        </template>
                  
                        
                    </tr>
                 
                    
                </tbody>
            </v-simple-table>
        </v-card>
    </div>
</template>

<script>
    import{ red } from '../../details/redcon.js'
    export default {
        props:[
            'obj'
        ],
        data() {
            return {
                red : red
            }
        },
    }
</script>

<style  scoped>
.total{
    font-weight: bold;
}
</style>