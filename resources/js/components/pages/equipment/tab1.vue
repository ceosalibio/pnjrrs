<template>
    <div>
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header height="80vh" id="table" ref="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th width="20%">Required</th>
                        <th width="20%">Actual</th>
                    </tr>
                </thead>
                 <tbody v-for="(item,i) in obj.tableData" :key="i" >
                    <tr >
                        <td 
                            :class="item.Title?'item-title':'item-detail'"
                            :colspan="!item.required ?'3':'1'"
                        >
                            {{ item.description }}
                        </td>
                        <td v-if="item.required">{{ item.required }}</td>
                        <v-tooltip bottom  transition="slide-x-reverse-transition">
                            <template v-slot:activator="{on: tooltip}">
                                <td 
                                    v-if="item.required && !item.na"
                                    @dblclick="edit = true; index = i" 
                                    :class="obj.status == '0' && item.required && !item.na && user.approver == 1?'editable':''" 
                                    v-on="{...tooltip}"
                                >
                                    <div v-if="edit && index === i && obj.status == '0' && user.approver == 1" class="mt-3">
                                        <v-text-field 
                                            type="number" 
                                            class="text-center" 
                                            v-model="item.actual"  
                                            @blur="updateDetail(item)"
                                            min="0"
                                            dense
                                            outlined
                                        />
                                    </div>
                                    <div v-else>
                                         <div style="white-space: pre-wrap;" >{{ item.actual?item.actual:'' }}</div>
                                    </div>
                                </td>
                            </template>
                            <span>Double click to edit</span>
                        </v-tooltip>
                
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card>
    </div>
</template>

<script>
    import axiosHttp from '../../../axiosHttp' 
    import{ data } from '../../details/data.js'
    import { encryptData, decryptData, decryptBase } from '../../details/crytp'
    import { mapState } from 'vuex'
    export default {
        props:[
            'generate',
            'nav',
            'obj'
        ],
        data() {
            return {
                edit: false,
                index:null
            }
        },
        methods:{
            updateDetail(item){
    
                this.edit = false
                this.index = null
                data.overlay = true
                axiosHttp.post('updateEquip',{
                    id : this.obj.id,
                    details : encryptData(this.obj.tableData),
                    item : item,
                    unit : this.obj.unit,
                    month : this.obj.month
                }).then(res=>{
                    this.generate()
                }).catch(err=>{
                    console.log(err)
                    alert('Error:Try again')
                    data.overlay = false
                })
            },

        },
        computed:{
            ...mapState([
                'user'
            ])
        }  
    }
</script>

<style  scoped>
.item-title{
    font-weight:bold;
    text-align:left !important;
}
.item-detail{
    text-align:left !important;
}
</style>