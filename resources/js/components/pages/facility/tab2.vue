<template>
    <div>
        <v-card>
            <v-simple-table class="elevation-1 custom-table" fixed-header height="90vh">
                <thead>
                    <tr>
                        <th width="20%" rowspan="2">Facility</th>
                        <th colspan="3">Quantitative</th>
                        <th>Qualitative</th>
                        <th rowspan="2">Rating</th>
                        <th rowspan="2">REDCON</th>
                    </tr>
                    <tr>
                        <th>Required</th>
                        <th>Actual</th>
                        <th>Rating</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                 <tbody>
                    <template v-for="(item,i) in obj.tableData" >
                        <tr :key="'main' + i" >
                            <td 
                            :class="item.title?'item-title':'item-detail'"
                            :colspan="item.title ?'5':'1'"
                        >
                            {{ item.description }}
                        </td>
                          <template  v-if="!item.title">
                            <td >{{ item.required }}</td>
                            <td >{{ item.actual }}</td>
                            <td >{{ item.rating?item.rating.toFixed(2)+'%':'' }}</td>
                            <td >{{ item.total?item.total.toFixed(2)+'%':'' }}</td>
                          </template>
                          <td 
                            v-if="item.count > 0" 
                            :rowspan="item.count > 0 ? item.count : 1"
                            class="text-h5  justify-center align-center"
                          >
                           {{ rating(item) }}
                          </td>
                          <td 
                                v-if="item.count > 0" 
                                :rowspan="item.count > 0 ? item.count : 1" 
                                :class="rating(item)?`${red.redStyle(red.redCon(rating(item)))} text-h5` :''"
                            >
                                {{ rating(item)?red.redCon(rating(item)).substr(0,2):'' }}
                          </td>
                        </tr>
                        <template >
                          <tr v-if="!item.count && (!obj.tableData[i + 1] || obj.tableData[i + 1].count)" class="bg-detail" :key="'sub'+ i">
                            <td class="font-weight-bold" colspan="3">Average Rating</td>
                            <td class="font-weight-bold">{{ quantitative(item) }}</td>
                            <td class="font-weight-bold">{{ qualitative(item) }}</td>
                            <td ></td>
                            <td  colspan="2"></td>
                          </tr>
                        </template>
                    </template>
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
                red: red
            }
        },
        methods:{
            rating(item){
                let rating = this.obj.master
                switch (item.info) {
                    case 1:
                        return rating.pa_facilityrating.toFixed(2) + '%'
                        break;
                    case 2:
                        return rating.sa_facilityrating.toFixed(2) + '%'
                        break;
                    case 3:
                        return rating.hs_facilityrating.toFixed(2) + '%'
                        break;
                    case 4:
                        return rating.bp_facilityrating.toFixed(2) + '%'
                        break;
                
                    default:
                        break;
                }
            },
            quantitative(item){
                let rating = this.obj.master
                switch (item.info) {
                    case 1:
                        return rating.pa_quantitative.toFixed(2) + '%'
                        break;
                    case 2:
                        return rating.sa_quantitative.toFixed(2) + '%'
                        break;
                    case 3:
                        return rating.hs_quantitative.toFixed(2) + '%'
                        break;
                    case 4:
                        return rating.bp_quantitative.toFixed(2) + '%'
                        break;
                    default:
                        break;
                }
            },
            qualitative(item){
                let rating = this.obj.master
                switch (item.info) {
                    case 1:
                        return rating.pa_qualitative.toFixed(2) + '%'
                        break;
                    case 2:
                        return rating.sa_qualitative.toFixed(2) + '%'
                        break;
                    case 3:
                        return rating.hs_qualitative.toFixed(2) + '%'
                        break;
                    case 4:
                        return rating.bp_qualitative.toFixed(2) + '%'
                        break;
                    default:
                        break;
                }
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
}
.bg-detail{
    background-color: lightslategray;
    font-weight: bold !important;
}
</style>