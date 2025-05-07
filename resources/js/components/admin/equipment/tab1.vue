<template>
    <div>
        <v-card elevation="3" >
            <!-- <v-card-text> -->
                <v-simple-table
                    fixed-header
                    class="elevation-1 custom-table"
                    height="80vh"
                >
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Required</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,i) in tableData" :key="i">
                            <td :class="item.Title?'equip-title':'text-left'">{{ item.description }}</td>
                            <td>{{ item.required }}</td>
                            <td>
                                <v-btn icon @click="deleteItem(item)"><v-icon color="error">mdi-trash-can-outline</v-icon></v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
            <!-- </v-card-text> -->
        </v-card>
    </div>
</template>

<script>
    import{ data } from '../../details/data.js'
    import axiosHttp from '../../../axiosHttp'
    export default {
        props:[
            'tableData',
            'getTable',
            'data',
            'obj'
        ],
        methods:{
            async deleteItem(item){
                let ask = confirm('Are you sure you want to delete this item?')
                    if(ask){
                        // console.log(item)
                        // return
                        try {
                            data.overlay = true
                            const newDetails = data.tempData.filter(r=>r.id != item.id)
                            const res = await axiosHttp.post('deleteEquipment',{
                                id : this.obj.id,
                                unit : data.unitCode,
                                details : JSON.stringify(newDetails),
                                required1 : item.Info == 'primary' && item.required? this.obj.required1 - item.required:this.obj.required1,
                                required2 : item.Info == 'secondary' && item.required? this.obj.required2 - item.required:this.obj.required2
                            });
                            if(res.status == 200){
                                this.getTable()
                            }
                        } catch (error) {
                            console.log(error)
                            data.overlay = false
                        }
                        
                    }
                    
            },
        }
    }
</script>

<style scoped>
.equip-title{
    font-weight:bold;
    text-align:left !important;
}
</style>