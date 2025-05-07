<template>
    <div>
        <v-dialog v-model="dialog" persistent width="900px">
            <v-card elevation="5" v-if="tableData.length > 0">
                <v-card-title class="vcard-title">DECLINED HISTORY
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-simple-table height="60vh" fixed-header>
                        <thead>
                            <tr>
                                <th>Reason For Declined</th>
                                <th>Date Declined</th>
                                <th>Declined By</th>
                            </tr>
                        </thead>
                        <tbody v-for="(item, i) in tableData" :key="i">
                            <tr>
                                <td>{{ item.reason }}</td>
                                <td>{{ item.time }}</td>
                                <td>{{ item.incharge }}</td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dialog: false,
                tableData:[]
            }
        },
        methods:{
            openDialog(data){
                this.tableData = data
                this.dialog = true
            }
        }
    }
</script>

<style  scoped>

</style>