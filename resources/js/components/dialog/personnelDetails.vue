<template>
    <div>
        <v-dialog persistent width="500px" v-model="data.dialog">
            <v-card>
                <v-card-title class="vcard-title">UPDATE
                    <v-spacer /><v-btn  @click="data.dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text>
                    <v-form ref="save" @submit.prevent="save">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field 
                                        label="Branch"
                                        dense
                                        outlined
                                        style="margin-top:10px;margin-bottom:-30px;"
                                        v-model="obj.BranchName"
                                        readonly
                                        :rules="rules.required"
                                     
                                    />
                         
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field 
                                        label="Position"
                                        outlined
                                        dense
                                        v-model="obj.PositionName"
                                        readonly
                                        style="margin-bottom:-30px;"
                                        :rules="rules.required"
                                      
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field 
                                        label="Name"
                                        placeholder="Firstname MI Lastname"
                                        outlined
                                        dense
                                        v-model="obj.Name"
                                        clearable
                                        style="margin-bottom:-30px;"
                                        :rules="rules.required"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-autocomplete 
                                        label="Rank"
                                        outlined
                                        dense
                                        style="margin-bottom:-30px;"
                                        v-model="obj.Rank"
                                        :items="list.rankList"
                                        item-text="Rank"
                                        item-value="Rank"
                                        clearable
                                        :rules="rules.required"
                                        @change="afpolist()"
                                    
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-autocomplete 
                                        label="Branch of Service"
                                        outlined
                                        dense
                                        style="margin-bottom:-30px;"
                                        v-model="obj.Service"
                                        :items="list.serviceItems"
                                        clearable
                                        :rules="rules.required"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field 
                                        label="Serial Number"
                                        outlined
                                        dense
                                        style="margin-bottom:-30px;"
                                        v-model="obj.Serial"
                                        :rules="rules.required"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-autocomplete 
                                        label="FOS"
                                        outlined
                                        dense
                                        v-model="obj.AfposActual"
                                        :items="newAfposlist"
                                        item-text="AfposName"
                                        item-value="AfposCode"
                                        clearable
                                        :rules="rules.required"
                                        @change="getAfposPoint()"
                                    />
                                </v-col>
                               
                                <v-col cols="12">
                                    <v-btn color="success" medium block type="submit">UPDATE</v-btn>
                                    <!-- <v-btn  medium block @click="resetUpdate()">CLEAR</v-btn> -->
                                </v-col>
                   
                            
                        </v-row>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import{ list } from '../details/list.js'
    import{ unitFilter } from '../details/unitFilter.js'
    import{ data } from '../details/data.js'
    import { encryptData, decryptData, decryptBase } from '../details/crytp'
    import axiosHttp from '../../axiosHttp'
    export default {
        props:[
            'month'
        ],
        data() {
            return {
                data:data,
                list : list,
                unitFilter : unitFilter,
                dialog: false,
                obj : {},
                rules:{
                    required: [
                        v => !!v || 'Field is required'
                    ],
                },
                newAfposlist:[],
            }
        },
        methods:{
            openDialog(item){
                console.log(item)
                this.obj = {...item}
                unitFilter.getRank()
                unitFilter.getAfpos()
                this.newAfposlist = []
                data.dialog = true
            },
            // 
         async  afpolist(){
                this.newAfposlist = []
                let val = await list.rankList.find(r=> r.Rank == this.obj.Rank)
                this.obj.GradeActual = await list.rankList.find(r=> r.Rank == this.obj.Rank)?.Grade;
                list.afposList.filter(r=>{
                    if(r.DivisionCode == val.DivisionCode){
                        this.newAfposlist.push(r)
                    }
                })

                await this.getGradePoint()


            },
            // 
           async getGradePoint(){
                if(this.obj.GradeName == 'E4/E5/E6' &&  ['E4','E5'].includes(this.obj.GradeActual)){
                    this.obj.GradePoint = 1
                }else if(this.obj.GradeName == 'E7/E8' &&  ['E7','E8'].includes(this.obj.GradeActual)){
                    this.obj.GradePoint = 1
                }else if(this.obj.GradeName == 'O3/O4' &&  ['O3','O4'].includes(this.obj.GradeActual)){
                    this.obj.GradePoint = 1
                }else if(this.obj.GradeName){

                    const pattern = /([A-Z]+)(\d+)/; // Regular expression to split letter and number
                    const match1 = pattern.exec(this.obj.GradeName);
                    const match2 = pattern.exec(this.obj.GradeActual);

                    if (match1 && match2) {
                        const letter1 = match1[1];
                        const letter2 = match2[1];
                        const number1 = parseInt(match1[2], 10);
                        const number2 = parseInt(match2[2], 10);

                        if (letter1 === letter2) {
                            if(number1 - number2 === 1 || number1 - number2 === 0){
                                this.obj.GradePoint = 1
                            }else{
                                this.obj.GradePoint = ''
                            }
                        }else{
                            this.obj.GradePoint = ''
                        }
                    }else{
                        this.obj.GradePoint = ''
                    }
                }
            },
            getAfposPoint(){
                if(this.obj.AfposName.toUpperCase() == 'OPEN RATING' || this.obj.AfposName.toUpperCase().includes(this.obj.AfposActual.toUpperCase())){
                    this.obj.AfposPoint = 1;
                }else{
                    this.obj.AfposPoint = '';
                }
            },
            capitalizeAfterSpace(str) {
                // Split the string by spaces
                let words = str.split(' ');

                // Capitalize the first letter of each word
                let capitalizedWords = words.map(word => {
                if (word.length > 0) {
                    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                }
                return '';
                });

                // Join the words back together with spaces
                let result = capitalizedWords.join(' ');

                return result;
            },
            save(){
                this.obj.Name = this.obj.GradeActual.includes('O')?this.obj.Name.toUpperCase():this.capitalizeAfterSpace(this.obj.Name);
                if(this.$refs.save.validate()){
                    data.overlay = true
                    this.$emit('updatedData',this.obj)
                }
            },
            checkSerial(){
                axiosHttp.post('checkSerial',{
                    month : this.month.month,
                    serial : this.obj.Serial
                }).then((res)=>{
                    return res.data
                }).catch(err=>{
                    console.log(err)
                })
            }
        },
        computed:{
            afplist(){
                return list.afposList
            }
        },
        watch:{
            afplist(val){
                if(val){
                    this.afpolist()
                }
            }
        }
        
    }
</script>

<style  scoped>

</style>