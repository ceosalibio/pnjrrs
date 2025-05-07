<template>
    <div>
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header height="80vh" id="table" ref="table">
                <thead>
                    <tr>
                        <th rowspan="2">Description</th>
                        <th rowspan="2">Required</th>
                        <th rowspan="2">Onhand</th>
                        <th colspan="3">Serviceable</th>
                    </tr>
                    <tr>
                        <th style="background-color: green !important;" width="10%">OPL</th>
                        <th style="background-color: lightyellow !important;color:black !important" width="10%">For RPR</th>
                        <th style="background-color: red !important;" width="10%">For TI/BER</th>
                    </tr>
                </thead>
                 <tbody v-for="(item,i) in obj.tableData" :key="i" >
                    <tr >
                        <td 
                            :class="item.Title?'item-title':'item-detail'"
                            :colspan="!item.required ?'6':'1'"
                        >
                            {{ item.description }}
                        </td>
                        <td v-if="item.required">{{ item.required }}</td>
                        <td v-if="item.required && !item.na">{{ item.actual }}</td>
                        <td v-if="item.required && !item.na">{{ item.operational }}</td>
                        <v-tooltip bottom  transition="slide-x-reverse-transition">
                            <template v-slot:activator="{on: tooltip}">
                                <td 
                                    v-if="item.actual && !item.na"
                                    @dblclick="edit1 = true; index1 = i" 
                                    :class="obj.status == '0' && item.actual && !item.na && user.approver == 1?'editable':''" 
                                    v-on="{...tooltip}"
                                >
                                    <div v-if="edit1 && index1 === i && obj.status == '0' && user.approver == 1" class="mt-3">
                                        <v-text-field 
                                            type="number" 
                                            class="text-center" 
                                            v-model="item.repair"  
                                            @blur="updateDetail(item,'repair')"
                                            min="0"
                                            dense
                                            outlined
                                        />
                                    </div>
                                    <div v-else>
                                         <div style="white-space: pre-wrap;" >{{ item.repair || item.repair != 0?item.repair:'' }}</div>
                                    </div>
                                </td>
                                <td v-else-if="item.required && !item.na"></td>
                            </template>
                            <span>Double click to edit</span>
                        </v-tooltip>
                        <!-- dispose -->
                        <v-tooltip bottom  transition="slide-x-reverse-transition">
                            <template v-slot:activator="{on: tooltip}">
                                <td 
                                    v-if="item.actual && !item.na"
                                    @dblclick="edit2 = true; index2 = i" 
                                    :class="obj.status == '0' && item.actual && !item.na && user.approver == 1?'editable':''" 
                                    v-on="{...tooltip}"
                                >
                                    <div v-if="edit2 && index2 === i && obj.status == '0' && user.approver == 1" class="mt-3">
                                        <v-text-field 
                                            type="number" 
                                            class="text-center" 
                                            v-model="item.dispose"  
                                            @blur="updateDetail(item,'dispose')"
                                            min="0"
                                            dense
                                            outlined
                                        />
                                    </div>
                                    <div v-else>
                                         <div style="white-space: pre-wrap;" >{{ item.dispose || item.dispose != '0'?item.dispose:'' }}</div>
                                    </div>
                                </td>
                                <td v-else-if="item.required && !item.na"></td>
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
                edit1: false,
                index1:null,
                edit2: false,
                index2:null,
            }
        },
        methods:{
            updateDetail(item,val){
                const table = [];
                this.obj.tableData.map(r=>{
                    if(item.id == r.id){
                        if(val == 'repair'){
                            if(r.operational >= r.repair){
                                r.operational = r.actual - r.repair
                            }else{
                                alert('Repair is bigger than operational value')
                            }
                        }else if(val == 'dispose'){
                            if(r.operational >= r.dispose){
                                r.operational = r.actual - r.dispose
                            }else{
                                alert('Dispose is bigger than operational value')
                            }
                        }
                    }
                    table.push(r)
                })

    
                this.edit1 = false
                this.edit2 = false
                this.index1 = null
                this.index2 = null
                data.overlay = true
                axiosHttp.post('updateMaintenance',{
                    id : this.obj.id,
                    details : encryptData(table),
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