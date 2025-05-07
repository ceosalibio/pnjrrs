<template>
    <div>
        <v-dialog width="40%" persistent scrollable v-model="dialog">
            <v-card elevation="2">
                <v-card-title style="background-color: #0275d8; color: white;">
                    SUBMIT
                    <v-spacer/><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text v-if="tab!= '6'">
                    <br>
                    <div v-for="(item,i) in assessmentData" :key="i" > 
                        <label>{{ item.question }}</label>
                        <v-textarea
                            dense
                            outlined
                            auto-grow 
                            v-model="item.assessment"
                        />
                            
                    </div>
                </v-card-text>

                <v-card-text v-else>
                    <br>
                    <div> 
                        <v-textarea
                            dense
                            outlined
                            auto-grow 
                            v-model="recommend"
                            label="Recommendation"
                        />
                            
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-btn color="success" block medium @click="submit()">SUBMIT</v-btn>
                </v-card-actions>
                <v-overlay :value="data.overlay" absolute>
                    <v-progress-circular
                        indeterminate
                        size="64"
                    ></v-progress-circular>
                </v-overlay>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import{ data } from '../details/data.js'
    import axiosHttp from '../../axiosHttp'
    import moment from 'moment'
    import { mapState } from 'vuex'
    export default {
        props:[
            'generate',
            'tab'
        ],
        data() {
            return {
                dialog:false,
                data : data,
                obj : {},
                assessmentData :[],
                recommend:''
            }
        },
        methods:{
            openDialog(val){
                this.obj = {...val}
                this.assessmentData = val.assessment
                this.tab == '6'?this.recommend = val.recommend:''
                this.dialog = true
            },
            submit(){
                let ask =confirm('Are you sure you want to submit this report?')
                if(ask){
                    const noted = this.obj.noted
                    noted[0].prepared_by = this.user.name
                    noted[0].prepared_date = moment().format('YYYY-MM-DD HH:mm:ss')
                    data.overlay = true
                        axiosHttp.post('submitReport',{
                            id : this.obj.id,
                            assessment : JSON.stringify(this.assessmentData),
                            recommend:this.tab == '6'?this.recommend:null,
                            noted : JSON.stringify(noted),
                            table : this.obj.table,
                            office : this.obj.office,
                            hpn : this.obj.hpn,
                            rest : this.obj.rest,
                            tab:this.tab?this.tab:''
                        }).then(res=>{
                            this.dialog = false
                            this.generate()
                        }).catch(err=>{
                            alert('Error:Try again')
                            data.overlay = false
                        })
                        // .finally(()=>{
                        //     data.overlay = false
                        // })                
                }
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

</style>