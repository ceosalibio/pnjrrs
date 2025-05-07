<template>
    <div>
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header height="80vh" id="table" ref="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th width="10%">Required</th>
                        <th width="10%">Actual</th>
                        <th width="10%">Physical<br>Apperance</th>
                        <th width="10%">Safety</th>
                        <th width="10%">Functionality<br>Sanitation</th>
                        <th width="10%">Building Plans<br>Documents</th>
                        <th width="10%" v-if="obj.status == '0'">Update</th>
                        <th width="10%" v-else>View</th>
                    </tr>
                </thead>
                 <tbody v-for="(item,i) in obj.tableData" :key="i" >
                    <tr >
                        <td 
                            :class="item.title?'item-title':'item-detail'"
                            :colspan="item.title ?'8':'1'"
                        >
                            {{ item.description }}
                        </td>
                        <td v-if="!item.title">{{ item.required }}</td>
                        <v-tooltip bottom  transition="slide-x-reverse-transition">
                            <template v-slot:activator="{on: tooltip}">
                                <td 
                                    v-if="!item.title"
                                    @dblclick="edit = true; index = i" 
                                    :class="obj.status == '0' && item.required && user.approver == 1?'editable':''" 
                                    v-on="{...tooltip}"
                                >
                                    <div v-if="edit && index === i && obj.status == '0' && user.approver == 1 && item.required"  class="mt-3">
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
                            <span v-if="item.required">Double click to edit</span>
                        </v-tooltip>
                        <td v-if="!item.title">{{ item.pa?item.pa + '%':'' }}</td>
                        <td v-if="!item.title">{{ item.sa?item.sa + '%':'' }}</td>
                        <td v-if="!item.title">{{ item.hs?item.hs + '%':'' }}</td>
                        <td v-if="!item.title">{{ item.bp?item.bp + '%':'' }}</td>
                        <td v-if="item.required">
                            <v-tooltip bottom  transition="slide-x-reverse-transition">
                                <template v-slot:activator="{on: tooltip}">
                                    <v-btn icon  v-on="{...tooltip}">
                                        <v-icon color="warning" v-if="obj.status == '0' && user.approver == 1" @click="openDialog(item)">mdi-table-edit</v-icon>
                                        <v-icon color="success" v-if="obj.status != '0'">mdi-eye</v-icon>
                                    </v-btn>
                                </template>
                                <span v-if="item.required">Click to open updating details</span>
                            </v-tooltip>
                        </td>
                
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card>
        <!-- facility dialog -->
        <facility-details 
            ref="facilitiesDetails" 
            :obj="obj"
            :generate="generate"
        />
    </div>
</template>

<script>
    import axiosHttp from '../../../axiosHttp' 
    import facilityDetails from '../../dialog/facilityDetails.vue'
    import{ data } from '../../details/data.js'
    import { encryptData, decryptData, decryptBase } from '../../details/crytp'
    import { mapState } from 'vuex'
    export default {
        components:{
            facilityDetails
        },
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
                const table = [];
                this.obj.tableData.map(r=>{
                    if(item.id == r.id){
                        if(r.required >= r.actual){

                            r.rating = (r.actual / r.required) * 100
                        }else{
                            alert('Actual is bigger than required value')
                            r.rating = 100
                            this.edit = false
                            this.index = null
                        }
                    }
                    table.push(r)
                })
                this.edit = false
                this.index = null
                data.overlay = true
                axiosHttp.post('updateFacility',{
                    id : this.obj.id,
                    details : encryptData(table),
                    master : encryptData(this.obj.master),
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
            openDialog(item){
                this.$refs.facilitiesDetails.showDialog(item)
            }

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