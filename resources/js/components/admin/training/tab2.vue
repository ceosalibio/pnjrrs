<template>
    <div>
        <v-simple-table >
            <thead>
                <tr>
                    <th>ACTIVITY FOR YEAR {{ data.year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,i) in activity" :key="i">
                    <td style="text-align:left !important;">{{ item.activity }}</td>
                </tr>
            </tbody>
        </v-simple-table>
    </div>
</template>

<script>
    export default {
        props:[
            'data',
            'tableData'
        ],
        computed:{
            activity(){
                if(this.tableData && this.tableData.activity){
                    return this.tableData.activity
                }
            }
        }
    }
</script>

<style  scoped>

</style>