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
                obj : {},
                name : ['pa_','sa_','hs_','bp_']
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
                // ,this.readiness(data)
                Promise.all([this.readiness(data)]).then(function(){
                    alert('Files are printed!')
                })
            },
           async table(data) {
                // let tableData = data.details
                let tableBody = [];
                    data.tableData.forEach((item,i) => {
                    
                    if(item.description != ''){
                        console.log(item)
                        const row = [
                            { 
                                text: item.description, colSpan: item.title? 5 : 1 ,
                                fontSize:item.title == '1'?10:item.title == '2'?9:8,
                                bold:item.title == '1'?true:false
                            },
                            !item.title ? { text: item.required || '',style:'body'} : '',
                            !item.title ? { text: item.actual || '',style:'body'} : '',
                            !item.title ? { text:item.rating ? item.rating.toFixed(2) + '%' : '',style:'body'} : '',
                            !item.title ? { text:item.total ? item.total.toFixed(2) + '%' : '',style:'body'} : '',
                            item.count > 0 ? { text: this.rating(item),
                                            alignment:'center',
                                            margin: [0, (item.count > 1 ? (item.count - 1) * 5 : 0)], // Adjust the margin based on the count
                                            lineHeight: (item.count > 1 ? 1.5 : 1),
                                            rowSpan: item.count } : '',
                            item.count > 0 ? { text: this.rating(item)?this.redCon(this.rating(item)):'',
                                            rowSpan: item.count,
                                            alignment:'center',
                                            margin: [0, (item.count > 1 ? (item.count - 1) * 5 : 0)], // Adjust the margin based on the count
                                            lineHeight: (item.count > 1 ? 1.5 : 1),
                                            style:this.redConStyle(this.redCon(this.rating(item)))
                                            } : ''
                        ];
              
                    tableBody.push(row);
                    }
                    

                    if (!item.count && (!data.tableData[i + 1] || data.tableData[i + 1].count)) {
                        const avgRow = [
                            { text: 'Average Rating', bold: true, fontSize: 10, colSpan: 3 },
                            {},
                            {},
                            { text: this.quantitative(item), bold: true, fontSize: 10 },
                            { text: this.qualitative(item), bold: true, fontSize: 10 },
                            {},
                            {}
                        ];
                        tableBody.push(avgRow);
                    }
                });

                const docDefinition = {
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
                                        { text: 'FACILITY RATING', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                                        "\n",
                                        { text: data.monthString, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                                    ],
                                    
                                },
                            ],
                        },
                        {
                            table: {
                                headerRows: 2,
                                widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'], // Adjust column widths as needed
                                body: [
                                    [
                                        { text: 'Facility', style: 'tableHeader',  },
                                        { text: 'Quantitative', style: 'tableHeader', colSpan: 3 },
                                        {},
                                        {},
                                        { text: 'Qualitative', style: 'tableHeader' },
                                        { text: 'Rating', style: 'tableHeader',  },
                                        { text: 'REDCON', style: 'tableHeader',  }
                                    ],
                                    [
                                        {},
                                        { text: 'Required', style: 'tableHeader' },
                                        { text: 'Actual', style: 'tableHeader' },
                                        { text: 'Rating', style: 'tableHeader' },
                                        { text: 'Rating', style: 'tableHeader' },
                                        {},
                                        {}
                                    ],
                                    ...tableBody
                                
                                ],
                                layout: {
                                    fillColor: 'autoTableAlternate',
                                    hLineWidth: function (i, node) {
                                    return (i === 0 || i < node.table.body.length - 1) ? 1 : 0;
          
                                    },
                                    vLineWidth: function () {
                                    return 0;
                                    },
                                    hLineColor: function (i) {
                                    return i === 1 ? '#000000' : '#00000000';
                                    },
                                    margin: [0, 0, 0, 10]
                                }
                            }
                        }
                    ],
                    
                    pageOrientation: 'portrait',
                    styles: {
                        tableHeader: {
                            bold: true,
                            alignment: 'center',
                            fontSize:10,
                        },
                        body:{
                            alignment: 'center',
                            fontSize:8,
                            margin: [0, 5],
                        },
                        redCon1: { color: "green" }, // Define styles as needed
                        redCon2: { color: "blue" },
                        redCon3: { color: "orange" },
                        redCon4: { color: "red" },
                    },
                    footer(currentPage, pageCount) {
                        return { text: `Page ${currentPage.toString()} of ${pageCount.toString()}`, alignment: 'right', margin: [50, 10,50,50],fontSize: '6', };
                    },
                };

                // Generate the PDF
                pdfMake.createPdf(docDefinition).download('Facility Rating.pdf');
            },


            
            // readiness


            async readiness(data) {

                    let remarksBody = [];
                    let tableData = [];
                        
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
                    
                    this.name.forEach(item => {
                        const row = [
                            {text:data.master[`${item}name`], bold:true, style:'body'},
                            {text:data.master[`${item}required`], alignment:'center', style:'body'},
                            {text:data.master[`${item}actual`], alignment:'center', style:'body'},
                            {text:data.master[`${item}quantitativeResult`].toFixed(2) + '%', alignment:'center', style:'body'},
                            {text:data.master[`${item}qualitativeResult`].toFixed(2) + '%', alignment:'center', style:'body'},
                            {text:data.master[`${item}totalRating`].toFixed(2) + '%', alignment:'center',style:'body'},
                            {text:data.master[`${item}weight`].toFixed(2) + '%', alignment:'center', style:'body'},
                        ]
                        tableData.push(row)
                    });
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
                                        { text: 'FACILITY READINESS REPORT', alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
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
                                widths: ['*','10%','10%','10%','10%','10%','20%'],
                                body: [
                                    [
                                        { text: 'Category', style: 'tableHeader' },
                                        { text: 'Required Area', style: 'tableHeader' },
                                        { text: 'Actual Area', style: 'tableHeader' },
                                        { text: 'Quantitative', style: 'tableHeader' },
                                        { text: 'Qualitative', style: 'tableHeader' },
                                        { text: 'Total', style: 'tableHeader' },
                                        { text: 'Percentage', style: 'tableHeader' }
                                    ],
                                    ...tableData,
                                    [
                                        {text:'TOTAL', bold:true, colSpan:'6', alignment:'right'},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {text:data.result.toFixed(2) + '%', bold:true,fontSize: 10,alignment:'center'},
                                    ],
                                    [
                                        {text:'REDCON STATUS', bold:true, colSpan:'6', alignment:'right',fontSize: 10},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {text:this.redCon(data.result) ,style:this.redConStyle(this.redCon(data.result)),fontSize: 8},
                                    ]

                                   
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
                pdfMake.createPdf(docDefinition).download('FRR.pdf');
            },
            rating(item){
                let rating = this.obj.master
                switch (item.info) {
                    case 1:
                        return rating.pa_facilityrating.toFixed(2) + '%'
                        break;
                    case 2:
                        return rating.sa_facilityrating.toFixed(2) + '%'
                        break;
                    case 3:
                        return rating.hs_facilityrating.toFixed(2) + '%'
                        break;
                    case 4:
                        return rating.bp_facilityrating.toFixed(2) + '%'
                        break;
                
                    default:
                        break;
                }
            },
            quantitative(item){
                let rating = this.obj.master
                switch (item.info) {
                    case 1:
                        return rating.pa_quantitative.toFixed(2) + '%'
                        break;
                    case 2:
                        return rating.sa_quantitative.toFixed(2) + '%'
                        break;
                    case 3:
                        return rating.hs_quantitative.toFixed(2) + '%'
                        break;
                    case 4:
                        return rating.bp_quantitative.toFixed(2) + '%'
                        break;
                    default:
                        break;
                }
            },
            qualitative(item){
                let rating = this.obj.master
                switch (item.info) {
                    case 1:
                        return rating.pa_qualitative.toFixed(2) + '%'
                        break;
                    case 2:
                        return rating.sa_qualitative.toFixed(2) + '%'
                        break;
                    case 3:
                        return rating.hs_qualitative.toFixed(2) + '%'
                        break;
                    case 4:
                        return rating.bp_qualitative.toFixed(2) + '%'
                        break;
                    default:
                        break;
                }
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