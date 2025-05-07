<template>
    <div >
        <v-card elevation="3" >
            <!-- <v-card-text> -->
                <v-simple-table
                    fixed-header
                    class="elevation-1 custom-table"
                >
                    <thead>
                        <tr>
                            <th>Mission Essential Task</th>
                            <th>Quarter1</th>
                            <th>Quarter2</th>
                            <th>Quarter3</th>
                            <th>Quarter4</th>
                            <!-- <th>Required</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,i) in details" :key="i">
                            <td  class="met-activity"><b>{{ item.met }}</b></td>
                            <td class="met-activity">{{ item.q1 }}</td>
                            <td class="met-activity">{{ item.q2 }}</td>
                            <td class="met-activity">{{ item.q3 }}</td>
                            <td class="met-activity">{{ item.q4 }}</td>
                        </tr>
                    </tbody>
                </v-simple-table>
            <!-- </v-card-text> -->
        </v-card>
    </div>
</template>

<script>
    export default {
        props:[
            'data',
            'year',
            'tableData'
        ],
        computed:{
            details(){
                if(this.tableData && this.tableData.details){
                    return this.tableData.details
                }
            }
        },
        mounted(){
            
        }
    }
</script>

<style  scoped>
.met-activity{
    height: 10vh !important;

}
</style>