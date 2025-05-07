<template>
    <div>
        <v-btn color="primary" block @click="viewAfpos()">REGISTER AFPOS</v-btn>

        <v-dialog v-model="dialog" width="600px" persistent>
            <v-card>
                <v-card-title style="background-color: #0275d8;color:white;">REGISTER AFPOS
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text>
                    <v-form ref="registerBtn" @submit.prevent="registerBtn">
                        <v-row class="ma-2">
                            <v-col cols="4">
                                <v-autocomplete 
                                    outlined
                                    dense
                                    label="Dvision"
                                    v-model="division"
                                    :items="divisionItems"
                                    item-text="text"
                                    item-value="value"
                                    :rules="rules"
                                  
                                />
                            </v-col>
                            <v-col cols="4">
                                <v-text-field 
                                    outlined
                                    dense
                                    label="FOS"
                                    v-model="afposValue"
                                    :rules="rules"
                                />
                            </v-col>
                            <v-col cols="4">
                               <v-btn type="submit" color="success" block>
                                    save
                               </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>

                    <v-divider />
                    <v-simple-table
                        fixed-header
                        height="60vh"
                    >
                        <tbody>
                            <tr v-for="(item,i) in tableData" :key="i">
                                <td>{{item.title}}</td>
                                <td>{{item.AfposName}}</td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    data:() => ({
        dialog:false,
        divisionItems:[{text:'Officer',value:'001'},{text:'Enlisted',value:'002'},{text:'Civillian',value:'003'}],
        division:null,
        afposValue:null,
        rules:[
            (value) => !!value || "Required"
        ],
        tableData:[]
    }),
    methods:{
        viewAfpos(){
            this.dialog = true
            axios.get('api/viewAfpos').then((res)=>{
                this.tableData = res.data.reduce((total,item)=>{
                    let i = total.findIndex(r => {
                        // Check if both r.AfposName and item.AfposName are not null or undefined
                        if (r.AfposName != null && item.AfposName != null) {
                            return r.AfposName.toUpperCase() === item.AfposName.toUpperCase();
                        }
                        return false;
                    });
                    if(i == -1){
                        if(item.DivisionCode == '001'){
                            item.title = 'OFFICER'
                        }
                        if(item.DivisionCode == '002'){
                            item.title = 'ENLISTED'
                        }
                        if(item.DivisionCode == '003'){
                            item.title = 'CIVILLIAN'
                        }
                        total.push(item)
                    }
                    return total
                },[])
            })
        },
        registerBtn(){
            if(this.$refs.registerBtn.validate()){
                axios.post('api/registerAfpos',{
                    division : this.division,
                    value : this.afposValue.toUpperCase(),
                    time : moment().format('YYYY-MM-DD HH:mm:ss')
                }).then((res)=>{
                    alert(res.data)
                    this.$refs.registerBtn.reset()
                    this.viewAfpos()
                }).catch(err=>{
                    alert(err)
                })
            }
        }
    }
}
</script>

<style>

</style>