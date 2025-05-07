<template>
    <div>
        <v-dialog v-model="dialog" persistent width="600px">
            <v-card>
                <v-card-title class="vcard-title">{{ name }}
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <br />
                <v-card-text>
                    <v-simple-table>
                        <thead>
                            <tr>
                                <th>Logged</th>
                                <th>Ipaddress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(val,i) in tableData" :key="i">
                                <td>{{ val.Login }}</td>
                                <td>{{ val.ipaddress }}</td>
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
                dialog:false,
                tableData: [],
                name:null,

            }
        },
        methods:{
            openDialog(obj){
                this.dialog = true
                this.tableData = obj.logs
                this.name = obj.name
            }
        }
    }
</script>

<style  scoped>

</style>