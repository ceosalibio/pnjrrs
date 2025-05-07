<template>
    <div >
        <v-container>
            <v-card class="ma-2">
                <v-simple-table class="elevation-1 custom-table">
                    <thead>
                        <tr>
                            <!-- <th>UNIT</th> -->
                            <th>NR OF PERSONNEL<br>PER UMD</th>
                            <th>NR ACTUAL<br>ASSIGNED</th>
                            <th>ORAC</th>
                            <th>PERCENTAGE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="orac-td">
                            <td class="orac-td">{{ obj.personnelrequired }}</td>
                            <td class="orac-td">{{ obj.personnelactual }}</td>
                
                            <td  
                                :class="obj.status == 0 && user.approver == 1?'editable orac-td':''" 
                                @dblclick="edit = true;"
                                v-if="obj.personnelrequired != 0" 
                                width="17%"
                            >
                                <template v-if="edit && obj.status == 0 && obj.personnelrequired != 0 && user.approver == 1">
                                    <v-text-field class="text-center" type="number" min="0" :max="obj.personnelactual" v-model="obj.orac" dense outlined @blur="checkorac()"/>
                                </template>
                                <template v-else>
                                    <v-tooltip bottom  transition="slide-x-reverse-transition">
                                        <template v-slot:activator="{on: tooltip}">
                                            <span style="white-space: pre-wrap;" class="orac-td" v-on="{...tooltip}">{{obj.orac }}</span>
                                        </template>
                                        <span>Double click to edit</span>
                                    </v-tooltip>
                                  </template>
                            </td>
                            <td class="orac-td">{{ obj.result && obj.result.oracPercentage?obj.result.oracPercentage.toFixed(2)+'%':'' }}</td>
        
                        </tr>
                    </tbody>
                </v-simple-table>
            </v-card>
            <br  />
            <v-divider  />
            <div v-if="obj.orac && obj.personnelactual - obj.orac ">
                <v-card >
                    <div class="ma-2">
                        <h3><span class="lacking">{{ obj.personnelactual - obj.orac }}</span> crew is/are not available:</h3>
                    </div>
                    <div class="ma-2">
                        <v-textarea  
                            placeholder="Input your reason here"
                            label="Reason"
                            auto-grow
                            outlined
                            v-model="obj.reason"
                            @blur="checkorac()"
                            :readonly="obj.status != '0'?true:false"
                        />
                    </div>
                </v-card>
            </div>
        </v-container>
    </div>
</template>

<script>
    import { training } from '../../data/training'
    import axiosHttp from '../../../axiosHttp'
    import {mapState } from 'vuex'
    export default {
        props:[
            'obj',
            'generate'
        ],
        data() {
            return {
                trainingJs : training,
                edit:false
            }
        },
        methods: {
          async  checkorac() {
                if(this.obj.status == '0'){
                    if(this.obj.orac > this.obj.personnelactual){
                        alert(`Error: Actual is bigger than Required, the Required Mp for the ${this.obj.monthString} are ${ this.obj.personnelactual}`)
                        this.obj.orac = this.obj.personnelactual
                        this.edit = false
                    }else{
                        this.edit = false
                    }
                    training.lackingMp = this.obj.personnelactual - this.obj.orac
                    training.oracPercentage = this.obj.orac / this.obj.personnelactual * 100

                    try {
                    const res = await  axiosHttp.post('updateOrac',{
                            id : this.obj.id,
                            personnelactual : this.obj.personnelactual,
                            orac : this.obj.orac,
                            reason : this.obj.reason,
                            actual :this.obj.actual
                        })
                        if(res.status == 200){
                            this.generate()
                        }
                    } catch (error) {
                        alert('Error: Try again')
                        console.log(error)
                    }
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
.orac-td {
  height:20vh;  
  font-size: 30px !important;
  font-weight:bold;
  vertical-align: middle !important;
}
.text-center{
    font-size: 30px !important;
}
.lacking{
    font-size: 25px !important;
    color:red;
}
</style>