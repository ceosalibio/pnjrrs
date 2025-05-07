<template>
    <div class="mt-4">
        <v-card v-if="Object.keys(this.obj).length > 0 ">
            <h4 align="center">TIMELINE STATUS</h4>
            <v-timeline 
                dense
                align-top
                v-for="(item,i) in this.obj.noted"
                :key="i"
            >
                <v-timeline-item small :class="font(obj.status,1)" :color="color(obj.status,1)">
                    <div class="name">
                        {{ item.prepared_by }}
                    </div>
                    <div class="date">
                        {{ item.prepared_date }}
                    </div>
                    <div class="title-line">
                        ENCODER/DRAFTER
                    </div>
                    
                </v-timeline-item>
                

                <div v-if="data.hpn == '1'">
                    <v-timeline-item small :class="font(obj.status,2)" :color="color(obj.status,2)" v-if="data.topOffice != '10'">
                        <div class="name">
                            {{ item.approved_by2 }}
                        </div>
                        <div class="date">
                            {{ item.approved_date2 }}
                        </div>
                        <div class="title-line">
                            OIC CONCURRING
                        </div>
                    </v-timeline-item>

                    <v-timeline-item small :class="font(obj.status,3)" :color="color(obj.status,3)" v-if="data.topOffice != '10'" >
                        <div class="name">
                            {{ item.approved_by3 }}
                        </div>
                        <div class="date">
                            {{ item.approved_date3 }}
                        </div>
                        <div class="title-line">
                            {{ statusLabel(3) }}
                        </div>
                    </v-timeline-item>
                    <v-timeline-item small :class="font(obj.status,4)" :color="color(obj.status,4)" v-if="data.topOffice != '10'">
                        <div class="name">
                            {{ item.approved_by4 }}
                        </div>
                        <div class="date">
                            {{ item.approved_date4 }}
                        </div>
                        <div class="title-line">
                            DEPUTY CONCURRING
                        </div>
                    </v-timeline-item>
                </div>

                <div v-else>
                    <v-timeline-item small :class="font(obj.status,4)" :color="color(obj.status,4)" v-if="data.topOffice != '10'">
                        <div class="name">
                            {{ item.approved_by4 }}
                        </div>
                        <div class="date">
                            {{ item.approved_date4 }}
                        </div>
                        <div class="title-line">
                            OIC CONCURRING
                        </div>
                    </v-timeline-item>
                </div>
                
                <v-timeline-item small :class="font(obj.status,5)" :color="color(obj.status,5)">
                    <div class="name">
                        {{ item.noted_by }}
                    </div>
                    <div class="date">
                        {{ item.noted_date }}
                    </div>
                    <div class="title-line">
                        {{ statusLabel(5) }}
                    </div>
                </v-timeline-item>
            </v-timeline>
            
        </v-card>
    </div>
</template>

<script>
   import{ data } from '../details/data.js'
   import { mapState } from 'vuex';
    export default {
        props:[
            'obj'
        ],
        data() {
            return {
               data : data,
               tableData : [],
            }
        },
        methods: {
            color(status, no) {
                return status >= no ? 'blue' : 'gray';
            },
            font(status, no) {
                return status >= no ? 'approved' : 'notApproved';
            },
            statusLabel(val){
                if(data.hpn != '1'){
                   return val == 3?'CHIEF OF STAFF CONCURRING':'COMMANDER APPROVAL'
                }else{
                    if(data.topOffice != '10'){
                        return val == 3?'EX-O CONCURRING':this.user.team
                    }else{
                        return 'OIC APPROVAL'
                    }
                    
                }
            },
        },
        computed:{
            ...mapState([
                'user'
            ])
        },
        mounted(){
            // if(Object.keys(this.obj).length > 0){
            //     this.tableData = JSON.parse(this.obj.noted)
            // }
        }
    }
</script>

<style  scoped>
.name{
    font-size: 12px;
    font-weight: bold;
}
.date{
    font-size: 11px;
}
.title-line{
    font-size: 13px;
    font-weight: bold;
}
</style>