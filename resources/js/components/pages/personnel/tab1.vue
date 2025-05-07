<template>
    <div>
        <v-simple-table
        fixed-header
        class="elevation-1 custom-table"
        height="80vh"
    >
        <thead>
            <tr>
                <th>DESCRIPTION/POSITION</th>
                <th width="25%">NAME</th>
                <th>RANK REQUIRED</th>
                <th>ACTUAL</th>
                <th>POINTS</th>
                <th>AFPOS</th>
                <th>ACTUAL</th>
                <th>POINTS</th>
                <th>REQUIRED</th>
                <th v-if="obj.status == '0'">UPDATE</th>
            </tr>
        </thead>
        <tbody v-for="(item, i) in obj.tableData" :key="i">
            <tr v-if="item.OfficeName">
                <td style="font-weight:bold;font-size:15px;background-color:lightgrey !important;" colspan="9">{{ item.OfficeName }}</td>
            </tr>
            <tr v-if="item.BranchName && !item.PositionName">
                <td style="font-weight:bold;font-size:15px;" colspan="2">{{ item.BranchName }}</td>
            </tr>
            <tr v-if="item.PositionName">
                <td style="text-align:left !important">{{ item.PositionName }}</td>
                <td style="text-align:left !important">{{ item.Rank }} {{ item.Name }} {{ item.Service }}</td>
                <td>{{ item.GradeName }}</td>
                <td>{{ item.GradeActual }}</td>
                <td>{{ item.GradePoint }}</td>
                <td>{{ item.AfposName }}</td>
                <td>{{ item.AfposActual }}</td>
                <td>{{ item.AfposPoint }}</td>
                <td>{{ item.RequiredMp }}</td>
                <td v-show="obj.status == '0' && user.approver == 1">
                    <v-tooltip bottom  transition="slide-x-reverse-transition">
                        <template v-slot:activator="{on: tooltip}">
                            <v-btn icon @click="rowDialog(item)" v-on="{...tooltip}"><v-icon color="warning">mdi-table-edit</v-icon></v-btn>
                        </template>
                        <span>Click to open updating details</span>
                    </v-tooltip>
                </td>
            </tr>
        </tbody>
    </v-simple-table>
    <UpdateDialog ref="updateDialog" @updatedData="updatedData" :month="nav"/>
    <SnackBar  :snackbar="snackbar"/>
    </div>
</template>

<script>
    import UpdateDialog from  '../../dialog/personnelDetails.vue'
    import { encryptData, decryptData, decryptBase } from '../../details/crytp'
    import SnackBar from '../../layouts/snackbar.vue'
    import{ data } from '../../details/data.js'
    import { mapState } from 'vuex'
    import axiosHttp from '../../../axiosHttp'
    import { personnel } from '../../data/personnel'
    export default {
        components: {
            UpdateDialog,
            SnackBar
        },
        props:[
            'generate',
            'obj',
            'nav'
        ],
        data() {
            return {
                data:data,
                snackbar:{}
            }
        },
        methods:{
            rowDialog(item){
                this.$refs.updateDialog.openDialog(item)
            },
            updatedData(obj){
                axiosHttp.post('updateRow',{
                    id : this.obj.id,
                    rowId : obj.id,
                    row : encryptData(obj),
                    unit : data.unitCode,
                    month : this.nav.month
                }).then((res)=>{
                    // console.log(this.obj,'obj')
                    if(res.data == 'serial exist'){
                        this.snackbar = {
                            message: `Serial number already exists!!`,
                            color: 'error',
                            icon: 'mdi-alert-circle-outline',
                            show: true
                        };
                        data.overlay = false
                    }else{
                        this.generate()
                        data.dialog = false
                        this.snackbar = {
                            message: `Record was updated!`,
                            color: 'success',
                            icon: 'mdi-check-circle-outline',
                            show: true
                        };
                    }
                    
                }).catch(err=>{
                    alert('Error:Try again')
                    console.log(err)
                    data.overlay = false
                })
            },
            // tab3Rating(){
            //     console.log(this.obj.actual , this.obj.required)
            //     let result = {}
            //         result.psgRating = this.obj.actual / this.obj.required * 100
            //         result.gradeRating = this.obj.grade / this.obj.actual * 100
            //         result.gradeRating04 =  result.gradeRating * 0.4
            //         result.afposRating = this.obj.afpos / this.obj.actual * 100
            //         result.afposRating06 = result.afposRating * 0.6
            //         result.psgRating05 = result.psgRating?result.psgRating * 0.5:0
            //         result.pqrRatingTotal = result.gradeRating04 + result.afposRating06
            //         result.pqrRating05 = result.pqrRatingTotal * 0.5
            //         result.totalRating = result.psgRating05 + result.pqrRating05
            //         personnel.rating = result
            //         return result
                    
            // },
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