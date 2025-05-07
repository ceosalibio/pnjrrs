<template>
    <div>
        <v-dialog v-model="dialog" width="1000px" persistent> 
            <v-card>
                <v-card-title class="vcard-title">Name Details
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <v-card-text>
                    <br>
                    
                    <v-form ref="addName" @submit.prevent="addName">
                        <div class="d-flex align-center">
                            <v-row>
                                <v-col cols="2">
                                    <v-select 
                                        v-model="rank" 
                                        dense 
                                        outlined
                                        label="Rank"
                                        :items="list.rankList"
                                        item-text="Rank"
                                        item-value="Rank"
                                        :rules="rules.required"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field 
                                        v-model="name" 
                                        label="Name"
                                        dense 
                                        outlined
                                        :rules="rules.required"
                                    />
                                </v-col>
                               
                                <v-col cols="2">
                                    <v-select 
                                        v-model="service" 
                                        label="Service"
                                        dense 
                                        outlined
                                        class="custom-select"
                                        :items="list.serviceItems"
                                        :rules="rules.required"
                                    />
                                </v-col>
                                <v-col cols="2">
                                    <v-btn type="submit" color="success" >SAVE</v-btn>
                                </v-col>
                                
                                
                            </v-row>
                        </div>
                    </v-form>
                        
                    
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import{ list } from '../details/list.js'
    export default {
        props:[
            'rules'
        ],
        data() {
            return {
                list : list,
                dialog : false,
                name:'',
                rank:'',
                service:'',
            }
        },
        methods: {
            openDialog(){
                this.dialog = true
            },
            addName(){
                if(this.$refs.addName.validate()){
                    let obj = {
                        rank : this.rank,
                        name : this.name.toUpperCase(),
                        service : this.service
                    }
                    this.$emit('addName',obj)
                    this.dialog = false
                    this.$refs.addName.reset()
                }
            }
        },
    }
</script>

<style  scoped>

</style>