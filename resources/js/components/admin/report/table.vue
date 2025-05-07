<template>
    <div>
        <v-card>
            <v-simple-table
                fixed-header
                class="elevation-1 custom-table"
                height="80vh"
            >
                <thead>
                    <tr>
                        <th>UNIT</th>
                        <th>PREPARED BY</th>
                        <th>APPROVED BY</th>
                        <th>STATUS</th>
                        <th v-if="obj.status == '2'">ACTION</th>
                        <th v-if="user.username == 'ceo'">DELETE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in obj.tableData" :key="i" v-show="showTable(item.status)">
                        <td  class="text-start font-weight-bold" >{{ item.unit }}</td>
                        <td>{{ item.prepared_by }}</td>
                        <td>{{ item.noted_by }}</td>
                        <td :class="statusStyle(item.status)">{{ statusText(item.status) }} </td>
                        <td v-if="obj.status == '2'">
                            <div class="ma-2">
                                <v-btn color="warning" width="50%" @click="unlockedReport(item)">
                                    UNLOCKED
                                </v-btn>
                            </div>
                            <div  class="ma-2">
                                <v-btn color="error" width="50%" @click="deleteReport(item)">
                                    DELETE
                                </v-btn>
                            </div>
                           
                        </td>
                        <td v-if="user.username == 'ceo' && item.id">
                            <div  class="ma-2">
                                <v-btn color="error" width="50%" @click="deleteReport(item)">
                                    DELETE
                                </v-btn>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card>
    </div>
</template>

<script>
    import{ data } from '../../details/data'
    import { getItem } from '../../details/crytp'
    import axiosHttp from '../../../axiosHttp'
    export default {
        props:[
            'obj',
            'generate'
        ],
        data() {
            return {
                user : getItem('user')
            }
        },
        methods:{
            statusText(val){
                let data
                switch (val) {
                    case '1':
                        data = 'SUBMITTED'
                        break;
                    case '2':
                        data = 'ONGOING'
                        break;
                    default:
                        data = 'NOT YET STARTED'
                        break;
                }
                return data
            },
            statusStyle(val){
                let data
                switch (val) {
                    case '1':
                        data = 'submitted'
                        break;
                    case '2':
                        data = 'ongoing'
                        break;
                    default:
                        data = 'nyr'
                        break;
                }
                return data
            },
            showTable(val){
                if(this.obj.status == '1'){
                    return true
                }else if(this.obj.status == '2' && val == '1'){
                   return true  
                }else if(this.obj.status == '3' && val != '1'){
                    return true
                }
            },
            unlockedReport(val){
                let ask = confirm('Are you sure yo want to unlocked this report')
                if(ask){
                    data.overlay = true
                    axiosHttp.post('unlockedReport',{
                        id : val.id,
                        table : val.table
                    }).then(()=>{
                        this.generate()
                        alert(`${val.unit} REPORT was unlocked`)
                    }).catch(err=>{
                        console.log(err)
                    })
                }

            },
            deleteReport(val){
                let ask = confirm('Are you sure yo want to delete this report')
                if(ask){
                    data.overlay = true
                    axiosHttp.post('deleteReport',{
                        id : val.id,
                        table : val.table
                    }).then(()=>{
                        this.generate()
                        alert(`${val.unit} REPORT was deleted`)
                    }).catch(err=>{
                        console.log(err)
                    })
                }
            },
        }
    }
</script>

<style scoped>
.submitted{
    color:blue;
    font-weight: bold;
}
.ongoing{
    font-weight: bold;
}

.nyr{
    color:red;
    font-weight: bold;
}
</style>