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
                imageDataUrl:''
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
                // Promise.all([this.printMETT(data),this.printORAC(data),this.printTRR(data)]).then(r=>{
                Promise.all([this.printMETT(data),this.printORAC(data),this.printTRR(data)]).then(function(){
                    alert('Files are printed!')
                })
            },
           async printMETT(obj) {
            let tableBody = [];
                const tableHeader = [
                    'MISSION ESSENTIAL TASK',
                    '1st Qtr',
                    '2nd Qtr',
                    '3rd Qtr',
                    '4th Qtr',
                    'Required',
                    'Actual',
                    'Percentage',
                ];

                obj.tableData.forEach(row => {
                    let rowData = [
                        { text: row.met || '', alignment: 'center', fontSize: row.met && row.met.length > 20 ? 8 : 10 },
                    ];

                    for (let i = 1; i <= 4; i++) {
                        rowData.push({
                        text: `${row[`q${i}`] || ''}
                        \n
                        ${row[`q${i}activity`] || ''}
                        \n
                        ${row[`q${i}date`] || ''}`,
                        alignment: 'center',
                        fontSize: row[`q${i}`] && row[`q${i}`].length > 15 ? 8 : 10,
                        lineHeight:0.4
                    });
                    }

                    rowData.push(
                        { text: row.required || '', alignment: 'center', fontSize: 10 },
                        { text: row.actual || '', alignment: 'center', fontSize: 10 },
                        { text: row.percent ? row.percent.toFixed(2) + '%' : '', alignment: 'center', fontSize: 10 },
                    );

                    tableBody.push(rowData);
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
                    noWrap: true,
                    alignment: 'center',
                    valign: 'middle',

                };
                
                let additionalRowData = [
                    { text: '' ,colSpan: 5}, // Empty cells for colspan="5"
                    { }, // Empty cells for colspan="5"
                    { }, // Empty cells for colspan="5"
                    { }, // Empty cells for colspan="5"
                    { }, // Empty cells for colspan="5"
                    { text: obj.required || '', alignment: 'center', fontSize: 10, bold: true },
                    { text: obj.actual || '', alignment: 'center', fontSize: 10, bold: true },
                    { text: obj.result.metPercentage.toFixed(2)+'%' || '', alignment: 'center', fontSize: 10, bold: true },
                ];

                tableBody.push(additionalRowData);

                const docDefinition = {
                    width: '100%',
                    content: [
                        { text: obj.team, alignment: 'center', fontSize: 15, margin: [0, 0, 0, 2] },
                        { text: 'MISSION ESSENNTIAL TASK TRAINING', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                        { text: obj.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                        {
                            table: {
                            headerRows: 1,
                            widths: ['auto', 'auto', 'auto', 'auto', 'auto', '*', '*', '*'],
                            body: [
                                tableHeader.map(headerCell => ({
                                text: headerCell,
                                style: 'tableHeader'
                                })),
                                ...tableBody
                            ],
                            layout: {
                                fillColor: 'autoTableAlternate',
                                hLineWidth: function (i, node) {
                                return (i === 0 || i === 1 || i === node.table.body.length) ? 1 : 0;
                                },
                                vLineWidth: function () {
                                return 0;
                                },
                                hLineColor: function (i) {
                                return i === 1 ? '#000000' : '#00000000';
                                }
                            }
                            }
                        }
                    ],
                    pageOrientation: 'landscape',
                    styles: {
                    tableHeader: headerStyle,
                    tableBody: bodyStyle
                    },
                    footer(currentPage, pageCount) {
                        return { text: `Page ${currentPage.toString()} of ${pageCount.toString()}`, alignment: 'right', margin: [50, 0],fontSize: '6', };
                    },
                };

                pdfMake.createPdf(docDefinition).download('METT.pdf');
            },
            // ORAC


            async printORAC(data) {
    
                let tableBody = [];
                const tableHeader = [
                    'NR OF PERSONNEL PER UMD',
                    'NR ACTUAL ASSIGNED',
                    'ORAC',
                    'PERCENTAGE'
                ];

                    let rowData = [
                        { text: data.personnelrequired || '', alignment: 'center', fontSize: 10 },
                        { text: data.personnelactual || '', alignment: 'center', fontSize: 10 },
                        { text: data.orac || '', alignment: 'center', fontSize: 10 },
                        { text: data.result.oracPercentage.toFixed(2)+'%' || '', alignment: 'center', fontSize: 10 },
                    ];

                    tableBody.push(rowData);
              

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
                        { text: 'CONSOLIDATED OPERATIONALLY & AVAILABLE CREW', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                        { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                        {
                            table: {
                            headerRows: 1,
                            widths: ['25%', '25%', '25%', '25%'],
                            body: [
                                tableHeader.map(headerCell => ({
                                text: headerCell,
                                style: 'tableHeader'
                                })),
                                ...tableBody
                            ],
                            layout: {
                                fillColor: 'autoTableAlternate',
                                hLineWidth: function (i, node) {
                                return (i === 0 || i === 1 || i === node.table.body.length) ? 1 : 0;
                                },
                                vLineWidth: function () {
                                return 0;
                                },
                                hLineColor: function (i) {
                                return i === 1 ? '#000000' : '#00000000';
                                },
                                minHeight: 40
                           
                            }
                            }
                        },
                        {},
                        { text: data.reason?'NOTE:':'',alignment: 'left', fontSize: 15,bold:true,margin: [0, 20, 0, 0]},
                        { text: data.reason || '', alignment: 'left', fontSize: 12 }

                    ],
                    pageOrientation: 'landscape',
                    styles: {
                    tableHeader: headerStyle,
                    tableBody: bodyStyle
                    },
                    footer(currentPage, pageCount) {
                        return { text: `Page ${currentPage.toString()} of ${pageCount.toString()}`, alignment: 'right', margin: [50, 0],fontSize: '6', };
                    },
                };

                pdfMake.createPdf(docDefinition).download('ORAC.pdf');
                },


            async printTRR(data){
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

                    let tableBody = [];
                    const tableHeader = [
                        'PERCENTAGE ORAC',
                        'PERCENTAGE METT',
                        'TAINING READINNESS RATING'
                    ];

                        let rowData = [
                            { text: data.result.oracValue.toFixed(2)+'%' || '', alignment: 'center', fontSize: 10 },
                            { text: data.result.metValue.toFixed(2)+'%' || '', alignment: 'center', fontSize: 10 },
                            { text: data.result.readiness.toFixed(2)+'%' || '', alignment: 'center', fontSize: 10 },
                            
                        ];

                        tableBody.push(rowData);
                        let rating = [
                            { text: 'REDCON', alignment: 'center', fontSize: 10 , style:'tableHeader',colSpan:2},
                            {},
                            { text: this.redCon(data.result.readiness) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(data.result.readiness)) },
                        ]
                        tableBody.push(rating);
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
                                        { text: 'TRAINING READINESS REPORT', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                                        "\n",
                                        { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                                    ],
                                    
                                },
                            ],
                        },
                        {
                            table: {
                            headerRows: 1,
                            widths: ['30%', '30%', '40%'],
                            body: [
                                tableHeader.map(headerCell => ({
                                text: headerCell,
                                style: 'tableHeader'
                                })),
                                ...tableBody
                            ],
                            layout: {
                                fillColor: 'autoTableAlternate',
                                hLineWidth: function (i, node) {
                                return (i === 0 || i === 1 || i === node.table.body.length) ? 1 : 0;
                                },
                                vLineWidth: function () {
                                return 0;
                                },
                                hLineColor: function (i) {
                                return i === 1 ? '#000000' : '#00000000';
                                },
                           
                            }
                            }
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
                    styles: {
                        tableHeader: headerStyle,
                        tableBody: bodyStyle,
                        redCon1: { color: "white",fillColor: 'green'}, // Define styles as needed
                        redCon2: { color: "white",fillColor: 'blue' },
                        redCon3: { fillColor: 'yellow' },
                        redCon4: { color: "white",fillColor: 'red' },
                    },
                }
                if (remarksBody.length === 0) {
                    docDefinition.content.splice(-1); // Remove the empty table from the content
                }
                pdfMake.createPdf(docDefinition).download('TRR.pdf');
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