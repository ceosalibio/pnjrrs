<template>
    <div>
        <v-row>
            <v-col cols="12" md="3" lg="3" xl="3">
                <v-card>
                    <v-simple-table
                        class="elevation-1 custom-table"
                        fixed-header
                    >
                        <thead>
                            <tr>
                                <th colspan="2">TOTAL</th>
                            </tr>
                        </thead>
                            <tbody>
                                <tr>
                                    <td>TO</td>
                                    <td  class="total">{{ obj.required }}</td>
                                </tr>
                                <tr>
                                    <td>ACTUAL</td>
                                    <td  class="total">{{ obj.actual }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="vcard-title"><b>PSG RATING</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2"  class="total">{{ psgRating?psgRating.toFixed(2)+'%':'' }}</td>
                                </tr>
                            </tbody>
                    </v-simple-table>
                </v-card>
            </v-col>
            <!--  -->
            <v-col cols="12" md="9" lg="9" xl="9">
                <v-card max-width="100%">
                    <v-simple-table
                        fixed-header
                        class="elevation-1 custom-table"
                        height="80vh"
                    >
                        <thead>
                            <tr>
                            <th>GRADE</th> 
                            <th>TO</th> 
                            <th>ACTUAL</th> 
                            </tr>
                        </thead>
                        <tbody v-for="(item,i) in tableData" :key="i">
                            <tr v-if="item.Division">
                                <td style="font-weight:bold;font-size:15px;">{{ item.Division }}</td>
                            </tr>
                            <tr>
                                <td >{{ item.GradeName }}</td>
                                <td >{{ item.required }}</td>
                                <td >{{ item.actual?item.actual:'' }}</td>
                            </tr>
                            <template v-if="!item.Division && (!tableData[i + 1] || tableData[i + 1].Division)">
                                <tr style="background-color:lightgray;">
                                    <td style="font-weight:bold;font-size:15px;">Sub-Total</td>
                                    <td style="font-weight:bold;font-size:15px;">{{ item.requiredTotal }}</td>
                                    <td style="font-weight:bold;font-size:15px;">{{ item.actualTotal }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </v-simple-table>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import{ data } from '../../details/data.js'
    import{ list } from '../../details/list.js'
    import { personnel } from '../../data/personnel'
    export default {
        components: {
            
        },
        props:[
            'obj',
            'psg'
        ],
        data() {
            return {
                data : data,
                list : list,
                tableData: personnel.psgTable,
                
            }
        },
        computed:{
            // psgRating(){
            //     let result
            //     if(this.obj.required){
            //         result = this.obj.actual / this.obj.required * 100
            //         return result
            //     }else{
            //         return '0'
            //     }
            // },
            psgRating(){
                if(this.obj && this.obj.result){
                    return this.obj.result.psgRating
                }else{
                    return 0
                }
                
            }
        },

    }
</script>

<style  scoped>
.total{
    font-size: 20px !important;
    font-weight: bold;
  }
</style>