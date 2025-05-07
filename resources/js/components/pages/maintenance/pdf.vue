<template>
    <div>

    </div>
</template>

<script>
    import pdfMake from 'pdfmake/build/pdfmake';
    import pdfFonts from 'pdfmake/build/vfs_fonts';
    pdfMake.vfs = pdfFonts.pdfMake.vfs;
    import { mapState } from 'vuex';
    export default {
        data(){
            return{
                imageDataUrl:'',
                obj : {}
            }
        },
        methods:{
            redCon(data){
                // console.log(val,'val')
                let val
                if(data <= 100 && data >= 85){
                    val = 'R1 - Fully Mission Capable'
                }
                else if(data <= 84 && data >= 75){
                    val = 'R2 - Mission Capable'
                }
                else if(data <= 74 && data >= 51){
                    val = 'R3 - Partially Mission Capable'
                }
                else{
                    val = 'R4 - Not Mission Capable'
                }
               
                return val;
            },
            redConStyle(val){
                let style
                if(val.includes('R1')){
                    style = 'redCon1'
                }
                else if(val.includes('R2')){
                    style = 'redCon2'
                }
                else if(val.includes('R3')){
     
                    style = 'redCon3'
                }
                else{
                    style = 'redCon4'
                }
                return style
            },
            async getPrint(data){
                this.obj = data
                // ,this.table(data)
                Promise.all([this.table(data),this.readiness(data)]).then(function(){
                    alert('Files are printed!')
                })
            },
           async table(data) {
            console.log(data,'ceo')
                // let tableData = data.details
                let tableBody = [];
                const tableHeader = [
                    'Description',
                    'Required',
                    'Onhand',
                    'OPL',
                    'For RPR',
                    'TI/BER'
                ];

                data.tableData.forEach(row => {
             
                        let rowMain = [
                            { text: row.description || '', alignment: 'left', fontSize: row.description && row.description.length > 20 ? 8 : 10,bold:row.Title?true:false },
                            { text: row.required?row.required || '':'', alignment: 'center', fontSize: 10},
                            { text: row.actual || '', alignment: 'center', fontSize: 10 },
                            { text: row.operational || '', alignment: 'center', fontSize: 10 },
                            { text: row.repair || '', alignment: 'center', fontSize: 10 },
                            { text: row.dispose || '', alignment: 'center', fontSize: 10 },
                        ];
                        tableBody.push(rowMain);  
                });

                const headerStyle = {
                    fillColor: 'lightgrey',
                    fontSize: 11,
                    bold: true,
                    alignment: 'center',
                    noWrap: true
                };

                const bodyStyle = {
                    fontSize: 8,
                    noWrap: true
                };
                const docDefinition = {
                    width: '100%',
                    content: [
                        { text: data.team, alignment: 'center', fontSize: 15, margin: [0, 0, 0, 2] },
                        { text: 'MAINTENANCE', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                        { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                        {
                            table: {
                            headerRows: 1,
                            widths: ['50%', '10%', '10%', '10%', '10%', '10%'],
                            body: [
                                tableHeader.map(headerCell => ({
                                text: headerCell,
                                style: 'tableHeader'
                                })),
                                ...tableBody
                            ],
                
                            }
                        }
                    ],
                    pageOrientation: 'portait',
                    styles: {
                    tableHeader: headerStyle,
                    tableBody: bodyStyle
                    },
                    footer(currentPage, pageCount) {
                        return { text: `Page ${currentPage.toString()} of ${pageCount.toString()}`, alignment: 'right', margin: [50, 0],fontSize: '6', };
                    },
                };

                pdfMake.createPdf(docDefinition).download('MAINTENANCE.pdf');
            },


            
            // readiness


            async readiness(data) {

                    let remarksBody = [];
                        
                    if (data.assessment) {
                        data.assessment.forEach(row => {
                            if (row.question) {
                                let question = [
                                    { text: row.question || '', alignment: 'left', fontSize: 10, fillColor: 'lightgrey', bold: true },
                                ];
                                remarksBody.push(question);
                            }
                            if (row.assessment) {
                                let remarks = [
                                    { text: row.assessment || '', alignment: 'left', fontSize: 10 }
                                ];
                                remarksBody.push(remarks);
                            }
                        });
                    }
                    let docDefinition = {
                    content: [
                    {
                            margin: [80, 0, 0, 0],
                            columns: [
                                {
                                    image: this.imageDataUrl, // Replace with the path to your image
                                    width: 50, // Adjust the width as needed
                                    alignment: 'right', // Adjust the alignment as needed
                                    margin: [0, 0, 0, 0], // Adjust the margins as needed
                                },
                                {
                                width: 'auto', // Adjust the width as needed
                                    text: [
                                        { text: data.team, alignment: 'center', fontSize: 15, margin: [0, 0, 0, 2] },
                                        "\n",
                                        { text: 'MAINTENANCE READINESS REPORT', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                                        "\n",
                                        { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                                    ],
                                    
                                },
                            ],
                        },

                        { text: '\n' },

                        { text: '\n' },
                
                        {   
                            table: {
                                headerRows: 1,
                                widths: ['*','*','10%','10%','10%'],
                                body: [
                                    [
                                        { text: '', style: 'tableHeader' },
                                        { text: '', style: 'tableHeader' },
                                        { text: 'TE', style: 'tableHeader' },
                                        { text: 'ACTUAL', style: 'tableHeader' },
                                        { text: '%', style: 'tableHeader' },
                                    ],
                                    ...data.master.flatMap((item, i) => {
                                            const rows = [];

                                            if (['MOBILITY','FIREPOWER'].includes(item.category) && item.category == item.sub) {
                                            rows.push([
                                                { text: item.category, colSpan: 3, style: 'groupDivision' },
                                                {},
                                                {},
                                                {},
                                                {},
                                            ]);
                                            }
                                            if(!item.title){
                                                rows.push([
                                                    { text: !['MOBILITY','FIREPOWER'].includes(item.category)?item.category:'',  style: 'groupDivision'},
                                                    { text: this.sub(item),  style: 'body',bold:item.break?true:false},
                                                    { text: item.required, style: 'body',bold:item.break?true:false},
                                                    { text: item.actual, style: 'body',bold:item.break?true:false},
                                                    { text: this.percent(item), style:'body',bold:item.break?true:false}
                                                ]);
                                            }
                                            // if(item.break){
                                            //     rows.push([
                                            //         { text: '', style: 'total' },
                                            //         { text: 'TOTAL', style: 'total' },
                                            //         { text: item.info == 'primary'?data.totalPrimaryRequired:data.totalSecondaryRequired, style: 'total' },
                                            //         { text: item.info == 'primary'?data.totalPrimaryActual:data.totalSecondaryActual, style: 'total' },
                                            //         { text: item.info == 'primary' ?data.averagePrimary+'%':data.averageSecondary+'%', style: 'total' },
                                            //     ]);
                                            //     rows.push([
                                            //         { text: '', style: 'subTotal' },
                                            //         { text: item.info == 'primary' ? 'MISSION ESSENTIAL EQUIPMENT (80%)' : 'SECONDARY EQUIPMENT', style: 'subTotal', colSpan: 3 },
                                            //         {},
                                            //         {},
                                            //         { text: item.info == 'primary' ? data.mee.toFixed(2)+'%' : data.see.toFixed(2)+'%', style: 'subTotal' },
                                            //     ]);
                                            // }
                                                

                                                if (!data.master[i +1]) {
                                                    rows.push(
                                                        [
                                                            { text: 'MEE + SEE', style: 'total',colSpan: 4 },
                                                            {},
                                                            {},
                                                            {},
                                                            { text: data.result.readiness.toFixed(2)+'%', style: 'total' },
                                                        ],
                                                        [
                                                            { text: this.redCon(data.result.readiness), style: this.redConStyle(this.redCon(data.result.readiness)),colSpan:5,alignment:'center' },
                                                            {},
                                                            {},
                                                            {},
                                                            {},
                                                        ],
                                                    );
                                                }
                                        
                                            

                                            return rows;
                                        }),
                                ],
                            },
                        },
                        {},
                        { text: 'Assessment:',alignment: 'left', fontSize: 15,bold:true,margin: [0, 20, 0, 0]},
                        {
                            table: {
                                
                                widths: ['*'],
                                body: remarksBody
                            }
                        },
                        {},
                        // {
                        //     margin: [0, 70, 0, 0],
                        //     columns:[
                                
                        //         {
                        //             text: [
                        //                 { text: 'PREPARED BY:',alignment: 'center', fontSize: 12},
                        //                 "\n",
                        //                 "\n",
                        //                 "\n",
                        //                 "\n",
                        //                 { text: data.sign.PreparedBy,alignment: 'center', fontSize: data.sign.PreparedBy.length < 23?12:10,bold:true,margin: [0, 0, 0, 0]},
                        //                 "\n",
                        //             ],
                                
                        //         },
                        //         {
                        //             text: [
                        //                 { text: 'NOTED BY:',alignment: 'center', fontSize: 12},
                        //                 "\n",
                        //                 "\n",
                        //                 "\n",
                        //                 "\n",
                        //                 { text:data.sign.NotedBy,alignment: 'center', fontSize: data.sign.NotedBy && data.sign.NotedBy.length < 23?12:10,bold:true,margin: [0, 0, 80, 0]},
                        //                 "\n",
                        //             ],
                        //         },
                        //         {
                        //             text: [
                        //                 { text: 'APPROVED BY:',alignment: 'center', fontSize: 12},
                        //                 "\n",
                        //                 "\n",
                        //                 "\n",
                        //                 "\n",
                        //                 { text: data.sign.ApprovedBy,alignment: 'center', fontSize: data.sign.ApprovedBy && data.sign.ApprovedBy.length < 23?12:10,bold:true,margin: [0, 0, 0, 0]},
                        //                 "\n",
                        //             ]
                        //         }
                        //     ],
                        // }     
                    ],
                    // functions: { // Pass the functions to the docDefinition
                    //     roundUpValue: roundUpValue,
                    //     itemPercentage: itemPercentage
                    // },
                    styles: {
                        tableHeader: {
                            fontSize: 10,
                            bold: true,
                            // fillColor: '#0275d8',
                            fillColor: 'lightgray',
                            alignment: 'center',
                        },
                        total: {
                            fontSize: 10,
                            bold: true,
                            alignment:'center',
                        },
                    
                        groupDivision: {
                            bold: true,
                            fontSize: 10,
                            alignment:'left',
                        },
                        body: {
                            fontSize: 8,
                            alignment:'center',
                        },
                        subTotal: {
                            bold: true,
                            fontSize: 10,
                            fillColor: 'lightgray',
                            alignment:'center',
                        },
                        redCon1: { color: "white",fillColor: 'green'}, // Define styles as needed
                        redCon2: { color: "white",fillColor: 'blue' },
                        redCon3: { fillColor: 'yellow' },
                        redCon4: { color: "white",fillColor: 'red' },
                    },
                };

                    // Generate PDF using pdfmake
                pdfMake.createPdf(docDefinition).download('MRR.pdf');
            },
            sub(item){
                let data 
                    if(item.info == '1' && !item.break){
                        data = item.sub
                    }else{
                        switch (item.sub) {
                            case 'Total1':
                                data = 'Total'
                                break;
                            case 'Total2':
                                data = 'Total'
                                break;
                            case 'MEE':
                                data = 'MISSION ESSENTIAL EQUIPMENT (80%)'
                                break;
                            case 'SEE':
                                data = 'SECONDARY EQUIPMENT (20%)'
                                break;
                        
                            default:
                                break;
                        }
                    }
                return data
            },
            percent(item){
                let data
                if(!item.break){
                    data = item.percent.toFixed(2) + '%'
                }else{
                    switch (item.sub) {
                        case 'Total1':
                            data = this.obj.result.avePrimary.toFixed(2) + '%'
                            break;
                        case 'Total2':
                            data = this.obj.result.aveSecondary.toFixed(2) + '%'
                            break;
                        case 'MEE':
                            data = this.obj.result.mee.toFixed(2) + '%'
                            break;
                        case 'SEE':
                            data = this.obj.result.see.toFixed(2) + '%'
                            break;
                    
                        default:
                            break;
                    }
                }
                return data
            }
        },
        computed:{
            ...mapState([
                'user'
            ]),
        },
        mounted() {
            // Import the image using the 'import' statement or 'require' if you're using webpack
           
            // Create a function to convert the image to a data URL
            let imageSrc = `storage/img/${this.user.departmentcode}.png`
            const getImageDataUrl = (imageSrc, callback) => {
            const img = new Image();
            img.crossOrigin = "Anonymous"; // Enable CORS for the image if it's hosted on a different domain
            img.onload = () => {
                const canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0);
                const dataUrl = canvas.toDataURL("image/png"); // Replace 'image/png' with the appropriate MIME type if needed
                callback(dataUrl);
            };
            img.src = imageSrc;
            };

            // Call the function to get the data URL
            getImageDataUrl(imageSrc, (dataUrl) => {
            this.imageDataUrl = dataUrl;
            });
        },
    }
</script>

<style scoped>

</style>