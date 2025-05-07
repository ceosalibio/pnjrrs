<template>
    <div>
        <v-dialog v-model="dialog" persistent width="70%" >
            <v-card>
                <v-card-title style="background-color: #0275d8;color:white;">{{ title }}
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text >
                    <br>
                    <v-simple-table  class="elevation-1 custom-table" fixed-header height="70vh">
                        <thead>
                            <tr>
                                <th width="40%">Facility</th>
                                <th width="10%">Percentage</th>
                                <th width="30%">Parameter</th>
                                <th width="20%">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, i) in temp.facility" :key="i">
                                <td 
                                    :class="!item.percent?'item-title':'item-detail'"
                                    :colspan="!item.percent && !item.code ?'4':!item.percent && item.code?'3':'1'"
                                >
                                    {{ item.detail }}
                                </td>
                                <td v-if="item.percent">{{ item.percent?item.percent+'%':'' }}</td>
                                <td v-if="item.percent">
                                    <v-autocomplete 
                                        v-model="item.parameter"
                                        :readonly="obj.status != 0?true:false"
                                        :clearable="obj.status == 0?true:false"
                                        dense
                                        outlined
                                        :items="parameter"
                                        item-text="text"
                                        item-value="value"
                                        class="custom-autocomplete"
                                        @change="parameterChange(item)"
                                    />
                                </td>
                                <td v-if="item.percent">{{ item.rating?item.rating+'%':'' }}</td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="success" block large @click="updateDetails()" :disabled="obj.status != '0'?true:false">UPDATE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import axiosHttp from '../../axiosHttp'
    import { encryptData, decryptData, decryptBase } from '../details/crytp'
    export default {
        props:[
            'generate',
            'obj'
        ],
        data() {
            return {
                dialog: false,
                title:'',
                parameter:[
                    {text:'Beyond economical repair/Document are incomplete and not updated',value:1},
                    {text:'Needs major repair/Documents are complete but not updated',value:2},
                    {text:'Needs minor repair/Documents are lacking but the lacking documents are under process',value:3},
                    {text:'Need no repair/Documents are complete',value:4},
                    {text:'Not Applicable',value:'4'},
                ],
                id:null,
                temp : {}
            }
        },
        methods:{
            showDialog(item){
                
                this.id = item.id
                this.title = item.description
                this.dialog = true
                this.temp = {...item}
                // console.log(this.obj)

            },
            parameterChange(item){
                let rating = item.parameter/4 * item.percent
                item.rating = Number(rating)
            },
            updateDetails(){
                const data =  this.obj.tableData.reduce((total,item)=>{
                    if(this.id == item.id){
                        item.pa = 0;
                        item.sa = 0;
                        item.hs = 0;
                        item.bp = 0;
                        item.total = 0;
                        for (const val of item.facility) {
                            let rating = val.rating?Number(val.rating):0
                            if(val.code && val.code.includes('PA') && val.rating){
                                item.pa += rating
                            }
                            if(val.code && val.code.includes('SA') && val.rating){
                                item.sa += rating
                            }
                            if(val.code && val.code.includes('HS') && val.rating){
                                item.hs += rating
                            }
                            if(val.code && val.code.includes('BP') && val.rating){
                                item.bp += rating
                            }
                            if(val.code && val.rating){
                                item.total += rating
                            }  
                        }
                    }
                    let index = total.findIndex(r=> r.info == item.info)
                    if(index == -1){
                        item.count = 1
                        total.push(item)
                    }else{
                        total[index].count += 1
                        total.push(item)
                    }
                    return total
                },[])
                
                let master = this.masterRating(data)
                // console.log(data)
                // return
                axiosHttp.post('updateFacility',{
                    id : this.obj.id,
                    master : encryptData(master),
                    details : encryptData(data)
                }).then(()=>{
                    this.generate()
                    this.dialog = false
                }).catch(err=>{
                    console.log(err)
                })
                
            },
            masterRating(data){
                let rating =  {}
                let nameRate = ['pa_','sa_','hs_','bp_']
                nameRate.forEach((name,i) => {
                    rating[`${name}name`] = '';
                    rating[`${name}countrow`] = 0;
                    rating[`${name}countquan`] = 0;
                    rating[`${name}countqual`] = 0;
                    rating[`${name}rating`] = 0;
                    rating[`${name}total`] = 0;
                    rating[`${name}quantitative`] = 0;
                    rating[`${name}qualitative`] = 0;
                    rating[`${name}facilityrating`] = 0;
                    // readiness table
                    rating[`${name}required`] = 0;
                    rating[`${name}actual`] = 0;
                    rating[`${name}quantitativeResult`] = 0;
                    rating[`${name}qualitativeResult`] = 0;
                    rating[`${name}totalRating`] = 0;
                    rating[`${name}weight`] = 0;
                        for (const item of data) {
                            if(item.info == i + 1){
                                switch (item.info) {
                                    case 1:
                                        rating[`${name}name`] = 'Operational Facilities'
                                        break;
                                    case 2:
                                        rating[`${name}name`] = 'Operational Support Facilities'
                                        break;
                                    case 3:
                                        rating[`${name}name`] = 'Base Utilities and Facilities'
                                        break;
                                    case 4:
                                        rating[`${name}name`] = 'Community Facilities'
                                        break;
                                
                                    default:
                                        break;
                                }
                                if(item.required){
                                    rating[`${name}required`] += item.required
                                }       
                                if(item.actual){
                                    rating[`${name}actual`] += Number(item.actual)
                                }
                                if(item.rating){
                                    rating[`${name}countquan`] += 1
                                    rating[`${name}rating`] += item.rating
                                }
                                if(item.total){
                                    rating[`${name}countqual`] += 1
                                    rating[`${name}total`] += item.total
                                }
                                rating[`${name}countrow`] += 1;
                                if (rating[`${name}countquan`] > 0) {
                                    rating[`${name}quantitative`] = (rating[`${name}rating`] / rating[`${name}countquan`]) * 0.5
                                    rating[`${name}quantitativeResult`] = rating[`${name}quantitative`] * 2
                                }
                                if (rating[`${name}countqual`] > 0) {
                                    rating[`${name}qualitative`] = (rating[`${name}total`] / rating[`${name}countqual`]) * 0.5
                                    rating[`${name}qualitativeResult`] = rating[`${name}qualitative`] * 2
                                }
                                
                                rating[`${name}facilityrating`] =  rating[`${name}quantitative`] + rating[`${name}qualitative`]
                                rating[`${name}totalRating`] = (rating[`${name}quantitativeResult`] + rating[`${name}qualitativeResult`]) / 2
                                
                                // readiness value
                                if(this.obj.rest == '13'){
                                    if(item.info == '1'){
                                        rating[`${name}weight`] =  rating[`${name}totalRating`]?rating[`${name}totalRating`] *  0.5:0
                                    }else if(item.info == '2'){
                                        rating[`${name}weight`] =  rating[`${name}totalRating`]?rating[`${name}totalRating`] *  0.3:0
                                    }else{
                                        rating[`${name}weight`] =  rating[`${name}totalRating`]?rating[`${name}totalRating`] *  0.1:0
                                    }
                                }else{
                                    if(item.info == '1'){
                                        rating[`${name}weight`] =  rating[`${name}totalRating`]?rating[`${name}totalRating`] *  0.6:0
                                    }else{
                                        rating[`${name}weight`] =  rating[`${name}totalRating`]?rating[`${name}totalRating`] *  0.4:0
                                    }
                                }
                            }
                        }
                });
                return rating
            }
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
    white-space: pre-wrap;
}
.custom-autocomplete{
    /* Set the width as needed */
   height: 30px; /* Set the desired height */
   vertical-align: middle !important;
 }
</style>