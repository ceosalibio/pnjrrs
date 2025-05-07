<template>
    <div>

    </div>
</template>

<script>
    import pdfMake from 'pdfmake/build/pdfmake';
    import pdfFonts from 'pdfmake/build/vfs_fonts';
    pdfMake.vfs = pdfFonts.pdfMake.vfs;
    import { mapState } from 'vuex';
    // import Approved from '../Details/Approved';
    export default {
        data(){
            return{
                imageDataUrl:''
            }
        },
        methods:{
            redCon(data){
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

                Promise.all([this.printPQR(data),this.printPSG(data),this.printPSR(data)]).then(function(){
                    alert('Files are printed!')
                })
            },
           async printPQR(val) {
                let data = val.details
                let tableBody = [];
                const tableHeader = [
                    'DESCRIPTION/POSITION',
                    'NAME',
                    'RANK REQUIRED',
                    'ACTUAL',
                    'POINTS',
                    'AFPOS',
                    'ACTUAL',
                    'POINTS',
                    'REQUIRED'
                ];

                data.tableData.forEach(row => {
                    if (row.OfficeName) {
                        let groupRowData = [
                            { text: row.OfficeName || '', bold: true, fontSize: 13, alignment: 'center', colSpan: 9 }
                        ];
                        tableBody.push(groupRowData);
                    }

                    if (row.BranchName && !row.PositionName) {
                        let groupRowData = [
                            { text: row.BranchName || '', bold: true, fontSize: 12, alignment: 'left', colSpan: 9 }
                        ];
                        tableBody.push(groupRowData);
                    }
                    if(row.PositionName){
                        let rowData = [
                            { text: row.PositionName || '', alignment: 'left', fontSize: row.PositionName && row.PositionName.length > 20 ? 8 : 10 },
                            { text: `${row.Rank || ''} ${row.Name || ''} ${row.Serivce || ''}`, alignment: 'left', fontSize: row.Name && row.Name.length > 15 ? 8 : 10 },
                            { text: row.GradeName || '', alignment: 'center', fontSize: 10 },
                            { text: row.GradeActual || '', alignment: 'center', fontSize: 10 },
                            { text: row.GradePoint || '', alignment: 'center', fontSize: 10 },
                            { text: row.AfposName || '', alignment: 'center', fontSize: 10 },
                            { text: row.AfposActual || '', alignment: 'center', fontSize: 10 },
                            { text: row.AfposPoint || '', alignment: 'center', fontSize: 10 },
                            { text: row.RequiredMp || '', alignment: 'center', fontSize: 10 }
                            ];

                            tableBody.push(rowData);
                        
                    }
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
                        { text: 'PERSONNEL QUALIFICATION RATING', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                        { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                        {
                            table: {
                            headerRows: 1,
                            widths: ['auto', 'auto', 'auto', '*', '*', '*', '*', '*', '*'],
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

                pdfMake.createPdf(docDefinition).download('Personnel Qualification Rating.pdf');
            },
            // PSG


         async  printPSG(val){
            let data = val.details
            let rating = val.rating
            let psg = val.psgTable

            
            let docDefinition = {
            content: [
                { text: data.team, alignment: 'center', fontSize: 15, margin: [0, 0, 0, 2] },
                { text: 'PERSONNEL STRENGTH BY GRADE (PSG)', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                {
                columns: [

                    {
                        width: '30%',
                    table: {
                        headerRows: 1,
                        widths: ['70%', '30%'],
                        body: [
                            [
                                { text: 'TOTAL TOE', style: 'tableHeader' },
                                { text: data.required.toString() || '', alignment: 'center', style: 'total' },
                            ],
                            [
                                { text: 'TOTAL ACTUAL', style: 'tableHeader' },
                                { text: data.actual.toString() || '', alignment: 'center', style: 'total' },
                            ],
                            [
                                { text: 'PSG RATING', style: 'tableHeader' },
                                { text: rating.psgRating.toFixed(2)+'%' || '', alignment: 'center', style: 'total' },
                            ],
                        ]
                    },
                    },

                    {},

                    {
                    width: '65%',
                    table: {
                        headerRows: 1,
                        widths: ['30%', '30%', '30%'],
                        body: [
                            [
                                { text: 'GRADE', style: 'tableHeader' },
                                { text: 'TOE', style: 'tableHeader' },
                                { text: 'ACTUAL', style: 'tableHeader' },
                            ],
                                ...psg.flatMap((item, i) => {
                                const rows = [];
                                if (item.Division) {
                                rows.push([
                                    { text: item.Division, colSpan: 3, style: 'groupDivision' },
                                    {},
                                    {},
                                ]);
                                }
                                if (item.GradeName !== '-') {
                                rows.push([
                                    { text: item.GradeName,style: 'body'},
                                    { text: item.required ? item.required: '',style: 'body' },
                                    { text: item.actual ? item.actual : '',style: 'body' },
                                ]);
                                }
                                if (
                                (!psg[i + 1] || psg[i + 1].Division) &&
                                rows.length > 0
                                ) {
                                rows.push([
                                    { text: 'Sub-Total', style: 'subTotal'},
                                    {text: item.requiredTotal, style: 'subTotal'},
                                    {text: item.actualTotal, style: 'subTotal'},
                                ]);
                                }
                                return rows;
                            }),
                        ],
                    },
                    },
                ],
                },
            ],
                styles: {
                    tableHeader: {
                    fontSize: 10,
                    bold: true,
                    // fillColor: '#0275d8',
                    fillColor: 'lightgray',
                    alignment: 'center',
                    },
                    total: {
                    fontSize: '12',
                    bold: true,
                    },
                    psgRatingHeader: {
                    fontSize: 8,
                    bold: true,
                    fillColor: '#0275d8',
                    color: 'white',
                    },
                    psgRating: {
                    fontSize: 8,
                    },
                    groupDivision: {
                    bold: true,
                    fontSize: 10,
                    alignment:'center',
                    },
                    body: {
                    fontSize: 8,
                    alignment:'center',
                    },
                    subTotal: {
                    bold: true,
                    fontSize: 8,
                    fillColor: 'lightgray',
                    alignment:'center',
                    },
                },
            };

            // Generate PDF using pdfmake
            pdfMake.createPdf(docDefinition).download('Personnel Strength by Grade.pdf');

            },


            async printPSR(val){
                    let data = val.details
                    let rating = val.rating
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
                    // let noted
                    // let approved
                    // if(['2','3','4'].includes(data.status)){
                    //     console.log('testtt...')
                    //     noted = {
                    //         text: [
                    //             { text: 'NOTED BY:',alignment: 'center', fontSize: 12},
                    //             "\n",
                    //             "\n",
                    //             "\n",
                    //             "\n",
                    //             { text: this.oic,alignment: 'center', fontSize: this.oic && this.oic.length < 23?12:10,bold:true,margin: [0, 0, 80, 0]},
                    //             "\n",
                               
                    //         ],
                    //     }
                    // }

                    // if(data.status == 5){
                    //     approved = {
                    //         text: [
                    //             { text: 'APPROVED BY:',alignment: 'center', fontSize: 12},
                    //             "\n",
                    //             "\n",
                    //             "\n",
                    //             "\n",
                    //             { text: this.commander,alignment: 'center', fontSize:this.commander && this.commander.length < 23?12:10,bold:true,margin: [0, 0, 0, 0]},
                    //             "\n",
                    
                    //         ]
                    //     }
                    // }
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
                                            { text: 'PERSONNEL READINESS REPORT', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                                            "\n",
                                            { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                                        ],
                                        
                                    },
                                ],
                            },
                        {
                            table: {
                            headerRows: 1,
                            widths: ['50%', '25%', '25%'],
                            body: [
                                    [
                                        { text: 'RATING', style: 'tableHeader' },
                                        { text: 'PERCENTAGE', style: 'tableHeader' },
                                        { text: 'WEIGHT PERCENTAGE', style: 'tableHeader' },
                                    ],
                                    [
                                        { text: 'PSGR (50%)' ,style: 'percentTitle'},
                                        { text: rating.psgRating.toFixed(2)+'%',style: 'percent' },
                                        { text: rating.psgRating05.toFixed(2)+'%',style: 'percent'},
                                    ],
                                    [
                                        { text: 'Personnel Qualification Rating (50%)',style: 'percentTitle'},
                                        { text: rating.pqrRating05.toFixed(2)+'%',style: 'percent' },
                                        { text: rating.pqrRatingTotal.toFixed(2)+'%',style: 'percent'},
                                    ],
                                    [
                                        { text: 'PRR (40%)',style: 'percentTitle'},
                                        { text: rating.gradeRating.toFixed(2)+'%',style: 'percent' },
                                        { text: rating.gradeRating04.toFixed(2)+'%',style: 'percent'},
                                    ],
                                    [
                                        { text: 'PSR (60%)',style: 'percentTitle'},
                                        { text: rating.afposRating.toFixed(2)+'%',style: 'percent' },
                                        { text: rating.afposRating06.toFixed(2)+'%' ,style: 'percent'},
                                    ],
                                    [
                                        { text: 'TOTAL (PSGR + PQR)',style: 'percentTitle'},
                                        { text: '' },
                                        { text: rating.readiness.toFixed(2)+'%' ,style: 'percent'},
                                    ],
                                    [
                                        { text: 'REDCON',style: 'tableHeader', },
                                        { text: this.redCon(rating.readiness.toFixed(2)+'%') ,style:this.redConStyle(this.redCon(rating.readiness)),colSpan:2,alignment:'center'},
                                        {},
                                    ],
                                ],
                              
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
                        tableHeader: {
                            fontSize: 10,
                            bold: true,
                            // fillColor: '#0275d8',
                            fillColor: 'lightgray',
                            alignment: 'center',
                        },
                        percent:{
                            alignment: 'center',
                            fontSize: 10,
                        },
                        percentTitle:{
                            bold: true,
                            fontSize: 10,
                        },
                        redCon1: { color: "white",fillColor: 'green'}, // Define styles as needed
                        redCon2: { color: "white",fillColor: 'blue' },
                        redCon3: { fillColor: 'yellow' },
                        redCon4: { color: "white",fillColor: 'red' },
                    },
                }
                if (remarksBody.length === 0) {
                    docDefinition.content.splice(-1); // Remove the empty table from the content
                }
                pdfMake.createPdf(docDefinition).download('Personnel Readiness Rating.pdf');
            }
        },
        computed:{
            ...mapState([
                'user'
            ]),
            // encoder(){
            //     let data
            //     let signatories = this.$store.state.signatories
            //     signatories.map(res=>{
            //         if(res.Approver == 1 && res.Name == this.$store.state.user.name){
            //            data = res.Name
            //         }
            //     })
            //     return data
            // },
            // oic(){
            //     let data
            //     let signatories = this.$store.state.signatories
            //     signatories.map(res=>{
            //         if(res.Approver == 2){
            //            data = res.Name
            //         }
            //     })
            //     return data
            // },
            // commander(){
            //     let data
            //     let signatories = this.$store.state.signatories
            //     signatories.map(res=>{
            //         if(res.Approver == 5){
            //            data = res.Name
            //         }
            //     })
            //     return data
            // }
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