<template>
    <div class="ma-2">
        <div>
            <h2>UPLOAD UNITS</h2>
        </div>
        <v-row>
            <v-col cols="3">
                <v-select  
                    v-model="units"
                    :items="items"
                    item-text="text"
                    item-value="value"
                    dense
                    outlined
                />
            </v-col>
            <v-col cols="2">
                <input type="file" ref="fileInput" @change="handleFileSelect" accept=".csv"/>
            </v-col>
            <v-col cols="2">
                <v-btn color="success" block @click="updaloadFile">UPLOAD</v-btn>
            </v-col>
            
        </v-row>
    </div>
</template>

<script>
    import Papa from 'papaparse';
    import axiosHttp from '../../axiosHttp'
    export default {
        data() {
            return {
                units: null,
                items : [
                    {text:'Department', value:'unitdepartments'},
                    {text:'Section', value:'unitsections'},
                    {text:'Team', value:'unitteams'},
                    {text:'Relation', value:'unitrelations'},
                ],
                tempFile:[],
                uploadFile:[]
            }
        },
        methods:{
            handleFileSelect(event) {
                // console.log(this.$refs.fileInput)
                const file = event.target.files[0];
                // this.selectedFile = event.target.files[0];
                // this.selectedFileValue = event.target.files[0];
            
                const reader = new FileReader();

                reader.onload = (e) => {
                    const contents = e.target.result;

                    const parsedData = Papa.parse(contents, {
                    header: true,
                    encoding: 'UTF-8',
                    });
                    const rows = parsedData.data;
                    // Process each row
                    const headers = Object.keys(rows[0]);
                    for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rowData = {};
                    for (let j = 0; j < headers.length; j++) {
                        let value = row[headers[j]];
                        rowData[headers[j]] = value;
                    }
        

                    this.uploadFile.push(rowData);
                    }
                    console.log(this.uploadFile,'data')
                };
        
                reader.readAsText(file);
            },
          async  updaloadFile(){
                const res = await axiosHttp.post('uploadUnits',{
                    unit : this.units,
                    data : this.uploadFile
                })
                console.log(res)
            }
        }
    }
</script>

<style  scoped>

</style>