<template>
    <div>
        <v-dialog v-model="dialog" persistent width="70%" scrollable>
            <v-card>
                <v-card-title class="vcard-title">Decline reason
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text class="ma-3">
                    <div>
                        <br />
                        <v-divider/>
                        <v-textarea
                            outlined
                            dense
                            v-model="declineReason.reason"
                            auto-grow
                            label="Reason for Decline"
                            style="white-space: pre-wrap;"
                        />
                    </div>
                    
                </v-card-text>
                <v-card-actions v-show="declineReason.reason">
                    <v-btn block color="success" @click="declined()">SAVE</v-btn>
                </v-card-actions>
                <v-overlay :value="data.overlay">
                    <v-progress-circular
                    indeterminate
                    size="70"
                    />
                    <br />
                     <span>SAVING</span>
                </v-overlay>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import axiosHttp from '../../axiosHttp'
    import moment from 'moment'
    import {data} from '../details/data'
    import { mapState } from 'vuex'
    export default {
        props:[
           'generate',
        ],
        data() {
            return {
                dialog:false,
                declineReason:{reason:'',time:moment().format('YYYY-MM-DD HH:mm:ss'),incharge:''},
                obj : {},
                data : data,
            }
        },
        methods:{
            openDialog(data){
                this.obj = {...data}
                console.log(this.obj)
                this.dialog = true
            },
            declined(){
                let ask = confirm('Are you sure you want to declined this report?')
                if(ask){
                    data.overlay = true
                    let temp = this.obj.declined
                    temp.push(this.declineReason)
                    axiosHttp.post('delinedReport',{
                        id : this.obj.id,
                        data : JSON.stringify(temp),
                        table : this.obj.table
                    }).then((res)=>{
                        this.generate()
                        this.dialog = false
                    }).catch(err=>{
                        alert('Error:Try again')
                    }).finally(()=>{
                        data.overlay = true
                    })
                }
            }
        },
        computed:{
            ...mapState([
                'user'
            ])
        },
        created() {
            this.declineReason.incharge = this.user.name;
        }
    }
</script>

<style  scoped>

</style>