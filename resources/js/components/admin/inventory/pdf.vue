<template>
    <div>

    </div>
</template>

<script>
    import pdfMake from 'pdfmake/build/pdfmake';
    import pdfFonts from 'pdfmake/build/vfs_fonts';
    pdfMake.vfs = pdfFonts.pdfMake.vfs;
    export default {
        props: [
            'tabName',
            'unit',
            'tab',
            'unitName',
            'month'

        ],
        data(){
            return{
                imageDataUrl:'',
            }
        },
        methods:{
           
            async getPrint(data){
                
                let tableData = []
             

          
                data.forEach(row => {
                    let rowData = [
                        { text: this.tab != '7'?row.des:row.cat || '', alignment: 'center', fontSize: 10 ,bold:true},
                        { text: row.req || '', alignment: 'center', fontSize: 10 },
                        { text: row.act || '', alignment: 'center', fontSize: 10 }
                    ];

                    tableData.push(rowData);
                });
                

                
                const tableHeader = [
                    'Item',
                    'Required',
                    'Actual'
                ];

                const headerStyle = {
                    fillColor: 'lightgrey',
                    fontSize: 11,
                    bold: true,
                    alignment: 'center',
                    noWrap: true
                };
               
                let docDefinition = {
                content: [
                    

                    ],
                    
                    pageOrientation: 'portait',
                    styles: {
                    tableHeader: headerStyle
                    // tableBody: bodyStyle
                    },
                    footer(currentPage, pageCount) {
                        return { text: `Page ${currentPage.toString()} of ${pageCount.toString()}`, alignment: 'right', margin: [50, 0],fontSize: '6', };
                    },          
                };

                    // mobility-----------------------
                    // if(data.tab == 'mobility'){
                        docDefinition.content.push(
                            {
                                margin: [120, 0, 0, 0],
                                columns: [
                                    {
                                        image: this.imageDataUrl, // Replace with the path to your image
                                        width: 60, // Adjust the width as needed
                                        alignment: 'right', // Adjust the alignment as needed
                                        margin: [0, 0, 0, 0], // Adjust the margins as needed
                                    },
                                    {
                                    width: 'auto', // Adjust the width as needed
                                        text: [
                                            { text: 'PHILIPPINE NAVY', alignment: 'center', fontSize: 15, margin: [0, 0, 0, 2] },
                                            this.unit == 'all' ? null : "\n",
                                            this.unit == 'all' ? null :{ text: this.unitName, alignment: 'center', fontSize: 12, margin: [0, 0, 0, 2] },
                                    
                                            "\n",
                                            { text: `${this.tabName} REPORT`, alignment: 'center', fontSize: 13, margin: [0, 0, 0, 2] },
                                            "\n",
                                            { text: this.month, alignment: 'center', fontSize: 10, margin: [0, 0, 0, 2] },
                                            "\n",
                                            "\n",

                                        ],
                                        
                                    },
                                ],
                            },
                            
                            {
                                table: {
                                headerRows: 1,
                                widths: ['*','*','*'],
                                body: [
                                    tableHeader.map(headerCell => ({
                                        text: headerCell,
                                        style: 'tableHeader'
                                    })),
                                    ...tableData                           
                                    
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
                            // {},
                            // { text: 'Assessment:',alignment: 'left', fontSize: 15,bold:true,margin: [0, 20, 0, 0]},
                            // {
                            //     table: {
                                    
                            //         widths: ['*'],
                            //         body: remarksBody
                            //     }
                            // },
                            // {},
                            // {
                            //     ...signatories
                            // },
                            

                            // { text: '', pageBreak: 'after' },
                        )
                    // }

                        
                   
                    

                    
                    pdfMake.createPdf(docDefinition).download('INVENTORY_REPORT.pdf');
            }
        },
        computed:{
        
        },
        watch:{
            unit(val){
                let imageDept
                if(val == 'all'){
                    imageDept = 'D01'
                }else{
                    imageDept = val
                }
                let imageSrc = `storage/img/${imageDept}.png`
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
            }
        },
        created(){
            // let imageSrc = `storage/img/${this.unit}.png`
            // const getImageDataUrl = (imageSrc, callback) => {
            // const img = new Image();
            // img.crossOrigin = "Anonymous"; // Enable CORS for the image if it's hosted on a different domain
            // img.onload = () => {
            //     const canvas = document.createElement("canvas");
            //     canvas.width = img.width;
            //     canvas.height = img.height;
            //     const ctx = canvas.getContext("2d");
            //     ctx.drawImage(img, 0, 0);
            //     const dataUrl = canvas.toDataURL("image/png"); // Replace 'image/png' with the appropriate MIME type if needed
            //     callback(dataUrl);
            // };
            // img.src = imageSrc;
            // };

            // // Call the function to get the data URL
            // getImageDataUrl(imageSrc, (dataUrl) => {
            // this.imageDataUrl = dataUrl;
            // });
        },
        mounted() {
            
            
        }
        
    }
</script>

<style scoped>

</style>