<template>
  <div>
        <v-dialog v-model="dialog" width="500px">
            <v-card>
                <v-card-title class="vcard-title">{{ dialogTitle }}
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <br/>
                <v-card-text>
                    <v-form ref="save" @submit.prevent="save">
                        <div>
                            <v-autocomplete 
                                label="Activities"
                                dense
                                outlined
                                :items="activity"
                                v-model="temp[`q${id}activity`]"
                                class="text-center"
                                :rules="rules.required"
                            />
                        </div>
                        <div>
                            <v-menu
                                ref="menu"
                                v-model="menu"
                                :close-on-content-click="false"
                                :return-value.sync="temp[`q${id}date`]"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="temp[`q${id}date`]"
                                    label="Picker in menu"
                                    prepend-inner-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    outlined
                                    dense
                                    class="text-center"
                                    :rules="rules.required"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="temp[`q${id}date`]"
                                    no-title
                                    scrollable
                                    :max="today"
                                >
                                <v-spacer></v-spacer>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="menu = false"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.menu.save(temp[`q${id}date`])"
                                >
                                    OK
                                </v-btn>
                                </v-date-picker>
                            </v-menu>
                        </div>
                        <v-btn type="submit" block color="success">SAVE</v-btn>
                        <v-btn  block color="error" @click="cancel()" :disabled="temp[`q${id}stats`] == '1'?false:true">cancel</v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
  </div>
</template>

<script>
import moment from 'moment'
import axiosHttp from '../../axiosHttp'
import { encryptData, decryptData, decryptBase } from '../details/crytp'
export default {
    props:[
        'obj',
        'generate'
    ],
    data(){
        return{
            dialog:false,
            rules:{
                    required: [
                        v => !!v || 'Field is required'
                    ],
                },
            temp:{},
            id:'',
            key:'',
            dialogTitle:'',
            menu:false,
            today:moment().format('YYYY-MM-DD')
        }
    },
    methods:{
        openDialog(item,i){
            this.temp = {...item}
            this.id = i
            this.dialogTitle = item[`q${i}`]
            this.dialog = true
            console.log(item,i)
        },
        async save(){
            if(this.$refs.save.validate()){
                if(this.temp[`q${this.id}activity`] != ""){
                    if(this.temp[`q${this.id}stats`] == '-'){
                        this.temp.actual += 1
                        this.temp[`q${this.id}stats`] = '1'
                        this.temp[`q${this.id}month`] = this.obj.month
                        this.temp[`q${this.id}monthString`] = this.obj.monthString
                        this.obj.actual += 1
                    }  
                }
                
              
                if (this.temp.required) {
                    
                    this.temp.percent = (this.temp.actual / this.temp.required) * 100;
                } else {
                    this.temp.percent = 0;
                }
                await this.updateTable()

            }
        },
       async cancel(){
            this.temp.actual -= 1
            this.temp[`q${this.id}activity`] = ''
            this.temp[`q${this.id}date`] = ''
            this.temp[`q${this.id}stats`] = '-'
            this.temp[`q${this.id}month`] = ''
            this.temp[`q${this.id}monthString`] = ''

            if (this.temp.required) {
                this.obj.actual -= 1
                this.temp.percent = (this.temp.actual / this.temp.required) * 100;
            } else {
                this.temp.percent = 0;
            }
            // console.log(this.temp)
            await this.updateTable()
        },
      async  updateTable(){
            let index = this.obj.tableData.findIndex((obj) => obj.id === this.temp.id);
            if(index !== -1){
                this.obj.tableData[index] = this.temp;
                axiosHttp.post('updateTrainingTable',{
                    id : this.obj.id,
                    details : encryptData(this.obj.tableData),
                    actual :this.obj.actual,
                    personnelactual : this.obj.personnelactual,
                    orac : this.obj.orac
                }).then(res=>{
                    this.generate()
                    this.dialog = false
                }).catch((err)=>{
                    alert('Error:Try again')
                    console.log(err)
                })
            }else{
                alert('Error')
            }
           
        }
    },
    computed:{
        activity(){
            let data = []
            if(this.obj && this.obj.activity){
                this.obj.activity.forEach(element => {
                    data.push(element.activity)
                });
            }
            return data
        }
    }
}
</script>

<style>

</style>