<template>
    <div>
                <!-- EQUIPMENT -->
            <v-card>
                <v-simple-table class="elevation-1 custom-table" fixed-header>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>TE</th>
                            <th>ACTUAL</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody v-for="(item,i) in obj.master" :key="i">
                        <tr v-if="['MOBILITY','FIREPOWER'].includes(item.category) && item.category == item.sub">
                            <td class="detail-title">{{ item.category }}</td>
                        </tr>
                        <tr v-if="!item.title">
                            <td class="detail-title">{{ !['MOBILITY','FIREPOWER'].includes(item.category)?item.category:'' }}</td>
                            <td :class="item.break?'detail-title':''">{{ sub(item)  }}</td>
                            <td :class="item.break?'detail-title':''">{{ item.required }}</td>
                            <td :class="item.break?'detail-title':''">{{ item.actual }}</td>
                            <td :class="item.break?'detail-title':''">{{ percent(item) }}</td>
                        </tr>
                            <template v-if="!obj.master[i +1]">
                            <tr>
                                <td colspan="4" class="readiness">MEE + SEE</td>
                                <td class="readiness">{{ obj.result.readiness.toFixed(2)+'%' }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" :class="obj.result.readiness?red.redStyle(red.redCon(obj.result.readiness)):''">{{ obj.result.readiness?red.redCon(obj.result.readiness):'' }}</td>
                            </tr>
                        </template>
                    </tbody>
                </v-simple-table>
            </v-card>
                    
        <!-- assessment table -->
        <assessmentTable 
        :obj="obj"
        />
    </div>
</template>

<script>
    import{ red } from '../../details/redcon.js'
    import assessmentTable from '../../layouts/assessmentTable.vue'
    export default {
        components: {
            assessmentTable,
        },
        props:[
            'obj'
        ],
        data() {
            return {
                red : red,
            }
        },
        methods:{
            sub(item){
                let data 
                    if(item.info == '1' && !item.break){
                        data = item.sub
                    }else{
                        switch (item.sub) {
                            case 'Total1':
                                data = 'Total'
                                break;
                            case 'Total2':
                                data = 'Total'
                                break;
                            case 'MEE':
                                data = 'MISSION ESSENTIAL EQUIPMENT (80%)'
                                break;
                            case 'SEE':
                                data = 'SECONDARY EQUIPMENT (20%)'
                                break;
                        
                            default:
                                break;
                        }
                    }
                return data
            },
            percent(item){
                let data
                if(!item.break){
                    data = item.percent.toFixed(2) + '%'
                }else{
                    switch (item.sub) {
                        case 'Total1':
                            data = this.obj.result.avePrimary.toFixed(2) + '%'
                            break;
                        case 'Total2':
                            data = this.obj.result.aveSecondary.toFixed(2) + '%'
                            break;
                        case 'MEE':
                            data = this.obj.result.mee.toFixed(2) + '%'
                            break;
                        case 'SEE':
                            data = this.obj.result.see.toFixed(2) + '%'
                            break;
                    
                        default:
                            break;
                    }
                }
                return data
            }
        }
    }
</script>

<style  scoped>
.detail-title{
    font-weight: bold;
}
.readiness{
    font-weight: bold;
}
</style>