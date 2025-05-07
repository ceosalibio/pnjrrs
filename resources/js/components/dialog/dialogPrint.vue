<template>
    <div>
        <v-dialog v-model="dialog" persistent width="300px">
            <v-card>
                <v-card-title class="vcard-title">Download File
                    <v-spacer /><v-btn  @click="dialog = false" icon><v-icon color="white">mdi-close-circle-outline</v-icon></v-btn>
                </v-card-title>
                <br />
                <br />
                <v-card-text>
                    <v-checkbox 
                        v-model="selected"
                        label="Print PDF"
                        value="pdf"
                    />
                    <v-checkbox 
                        v-model="selected"
                        label="Extract Excel"
                        value="excel"
                        v-show="print.tab != '6'"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-btn 
                        color="success" 
                        :disabled="selected.length > 0?false:true" 
                        block 
                        @click="download()"
                        :loading="loading"
                    >
                        DOWNLOAD
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
 
    export default {
        props:[
            'print',
            'printPdf',
            'printExcel'
        ],
        data() {
            return {
                dialog:false,
                selected:[],
                loading:false,
                
            }
        },
        methods: {
            openDialog(){
                this.dialog = true
    
            },
            download(){
                const confirmed = window.confirm('Are you sure you want to download this report?');
                if(confirmed){
                    this.loading = true
                    if(this.selected.includes('pdf')){
                        this.printPdf(this.print)
                        this.dialog = false
                    }
                    if(this.selected.includes('excel')){
                        this.printExcel(this.print)
                        this.dialog = false
                    }
                }
               
               
            }
        },
        watch:{
            dialog(val){
                if(!val){
                    this.selected = []
                    this.loading = false
                }
            }
        }
    }
</script>

<style  scoped>

</style>