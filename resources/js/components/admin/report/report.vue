<template>
    <div>
        <v-container fluid>
            <div align="center">
                <h1>Check Report</h1>
            </div>
            <v-divider  />
            <br  />
            <v-row>
                <v-col cols="12" md="3" lg="3" xl="3">
                    <Navigation 
                        @navData="navData"
                        :generate="generate"
                        :obj="obj"
                    />
                </v-col>

                <v-col cols="12" md="9" lg="9" xl="9" >
                    <v-card >
                        <TableData 
                            :obj="obj"
                            :generate="generate"
                        />
                    </v-card>
                    
                </v-col>
            </v-row>
        </v-container>
         <!-- overlay -->
        <v-overlay :value="data.overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
        
    </div>
</template>

<script>
    import axiosHttp from '../../../axiosHttp'
    import Navigation from './navigation.vue'
    import TableData from './table.vue'
    import{ data } from '../../details/data'
    export default {
        components:{
            Navigation,
            TableData
        },
        data() {
            return {
                data : data,
                obj : {},
                nav : {}
            }
        },
        methods:{
            generate(){
                data.overlay = true
                axiosHttp.post('checkReport',{
                    month : this.nav.month,
                    status : this.nav.status,
                    table : this.nav.report,
                    unit : this.nav.unit
                }).then(res=>{
                    let val = res.data
                    this.obj = {
                        tableData : val,
                        status : this.nav.status
                    }
                }).catch((err)=>{
                    alert('Error:Try again')
                    console.log(err)
                }).finally(()=>{
                    data.overlay = false
                })
                
            },
            navData(val){
                this.nav = {...val}
            },
        }

    }
</script>

<style  scoped>

</style>