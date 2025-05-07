<template>
    <div>
        <v-dialog v-model="dialog" persistent width="400px">
            <v-card elevation="3" class="justify-center" scrollable>
                <v-card-title  class="card-title">UPDATE USER
                    <v-spacer />
                    <v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text >
                    <br>
                    <v-form ref="edit" @submit.prevent="edit">
                        
                        <v-autocomplete 
                            label="Category"
                            :items="list.categoryList"
                            item-text="category_name"
                            item-value="category_code"
                            v-model="category"
                            dense
                            outlined
                            :rules="rules.required"
                            clearable
                        />
                        <v-autocomplete 
                            label="Unit"
                            dense
                            outlined
                            :items="list.departmentList"
                            item-text="name"
                            item-value="code"
                            v-model="department"
                            :rules="rules.required"
                            clearable
                           
                        />
                        <v-autocomplete 
                            label="Sub-Unit"
                            dense
                            outlined
                            :items="list.sectionList"
                            item-text="name"
                            item-value="code"
                            v-model="section"
                            :rules="rules.required"
                            clearable
                         
                        />
                        <v-autocomplete 
                            label="Offices"
                            item-text="name"
                            item-value="code"
                            dense
                            outlined
                            :items="list.teamList"
                            v-model="team"
                            :rules="rules.required"
            
                            clearable
                            v-if="data.office"
                        />
                        
                        <v-divider  />
                        <br>
                        
                        <v-autocomplete
                            label="Unit Approver"
                            outlined
                            dense
                            v-model="obj.approver"
                            :items="approverItems"
                            item-text="text"
                            item-value="value"
                            :rules="rules.required"
                            clearable
            
                        />
                        <v-autocomplete
                            label="Office Admin"
                            v-model="obj.office"
                            :items="restrictionItems"
                            item-text="text"
                            item-value="value"
                            outlined
                            dense
                            :rules="rules.required"
                            clearable
                        />

                        <v-text-field
                            label="Name"
                            v-model="obj.name"
                            outlined
                            dense
                            :rules="rules.required"
                            clearable

                        />

                        <v-text-field
                            label="Email Address"
                            v-model="obj.email"
                            outlined
                            dense
                           
                        />
                        <v-text-field
                            label="Username"
                            v-model="obj.username"
                            outlined
                            dense
                            :rules="rules.required"
                            maxlength="10"
                            hint="Maximum of 10 characters"
                        />
                        <v-text-field
                            label="Password"
                            v-model="obj.repas"
                            outlined
                            dense
                            :rules="rules.required"
                            maxlength="6"
                            hint="Maximum of 6 characters"
                        />
                        <v-btn block color="success" type="submit">ADD</v-btn>
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
    import axiosHttp from '../../axiosHttp'
    export default {
        props:[
            'rules',
            'approverItems',
            'restrictionItems',
            'getuser'

        ],
        data() {
            return {
                dialog: false,
                obj:{},
                list : list,
                unitFilter : unitFilter,
                data : data,
                category:null,
                department:null,
                section:null,
                team:null,
                
            }
        },
        methods:{
            openDialog(obj){
                this.obj = {...obj}
                this.dialog = true

            },
          async  edit(){
            console.log(this.obj)
                if(this.$refs.edit.validate()){
                    try {
                        const res = await axiosHttp.post('editUser',{data : this.obj})
                            if(res.status == 200){
                                this.getuser()
                                this.dialog = false
                                this.$refs.edit.reset()
                                data.category = ''
                                data.department = ''
                                data.section = ''
                                data.team = ''
                                data.unitCode = ''
                            }
                    } catch (error) {
                        console.error(error)
                    }
                  
                }
            }
        },
        computed:{

            unitcode(){
                return data.unitCode
            }
        },
        watch:{
            category(val,oldVal){
                if(val != oldVal){
                    data.category = val
                    this.obj.categorycode = val
                    unitFilter.getDepartment()
                }
            },
            department(val,oldVal){
                if(val != oldVal){
                    data.department = val
                    this.obj.departmentcode = val
                    unitFilter.getSection()
                }
            },
            section(val,oldVal){
                if(val != oldVal){
                    data.section = val
                    this.obj.sectioncode = val
                    unitFilter.getTeam()
                }
            },
            team(val,oldVal){
                if(val != oldVal){
                    data.team = val
                    this.obj.teamcode = val
                    unitFilter.unitInformation()
                }
            },
            unitcode(val){
                this.obj.unitcode = val
            }
        },
        mounted () {
            if(this.category){
                unitFilter.getDepartment()
            }
        },
    }
</script>

<style  scoped>

</style>