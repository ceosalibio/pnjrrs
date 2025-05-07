<template>
    <div>
        <v-card elevation="2">
            <v-simple-table
                class="custom-table"
            >
                <thead>
                    <tr>
                        <th>RATING</th>
                        <th>PERCENTAGE</th>
                        <th>WEIGHT<br>PERCENTAGE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PSGR (50%)</td>
                        <td>{{ rating.psgRating?rating.psgRating.toFixed(2):0 }}%</td>
                        <td>{{ rating.psgRating05?rating.psgRating05.toFixed(2):0 }}%</td>
                    </tr>
                    <tr>
                        <td>Personnel Qualification Rating (50%)</td>
                        <td>{{ rating.pqrRating05?rating.pqrRating05.toFixed(2):0 }}%</td>
                        <td>{{ rating.pqrRatingTotal?rating.pqrRatingTotal.toFixed(2):0}}%</td>
                    </tr>
                    <tr>
                        <td>PRR (40%)</td>
                        <td>{{ rating.gradeRating?rating.gradeRating.toFixed(2):0 }}%</td>
                        <td>{{ rating.gradeRating04?rating.gradeRating04.toFixed(2):0 }}%</td>
                    </tr>
                    <tr>
                        <td>PSR (60%)</td>
                        <td>{{ rating.afposRating?rating.afposRating.toFixed(2):0 }}%</td>
                        <td>{{ rating.afposRating06?rating.afposRating06.toFixed(2):0}}%</td>
                    </tr>
                    <tr>
                        <td>TOTAL (PSGR + PQR)</td>
                        <td></td>
                        <td><b>{{ rating.readiness?rating.readiness.toFixed(2):0 }}%</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td :class="rating.readiness?red.redStyle(red.redCon(rating.readiness)):''">{{ rating.readiness?red.redCon(rating.readiness):'' }}</td>
                    </tr>
                </tbody>  
            </v-simple-table>
        </v-card>
        <!-- assessment table -->
        <assessmentTable 
            :obj="obj"
        />
    </div>
</template>

<script>
    import{ red } from '../../details/redcon.js'
    import{ list } from '../../details/list.js'
    import { mapState } from 'vuex'
    import axiosHttp from '../../../axiosHttp'
    import assessmentTable from '../../layouts/assessmentTable.vue'
    // import { personnel } from '../../data/personnel'
    export default {
        components: {
            assessmentTable,
        },
        props:[
            'obj'
        ],
        data() {
            return {
                red : red,
            }
        },
        methods:{

        },
        computed: {
          rating(){
            let data ={}
            if(this.obj && this.obj.result){
                data = {...this.obj.result}
            }
            return data
          }
        },
       
    }
</script>

<style  scoped>

</style>