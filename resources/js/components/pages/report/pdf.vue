<template>
    <div>

    </div>
</template>

<script>
    import pdfMake from 'pdfmake/build/pdfmake';
    import pdfFonts from 'pdfmake/build/vfs_fonts';
    import moment from 'moment'
    import {mapState} from 'vuex'
    pdfMake.vfs = pdfFonts.pdfMake.vfs;
    export default {
        props:[
            'readiness'
        ],
        data() {
            return {
                imageDataUrl:'' ,
                fullname:'',
                rank:'',
                service:'',
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
            remarksFunction(remarksBody){
                let data = [
                            { 
                                text:[
                                    "\n",
                                    "\n",
                                ]
                             },
                            { text: 'Assessment:',alignment: 'left', fontSize: 13,bold:true,margin: [0, 0, 0, 0]},
                            { text: remarksBody[0] || '',alignment: 'left', fontSize: 12,bold:true,margin: [0, 0, 0, 0]},
                            { text: remarksBody[1] || '',alignment: 'left', fontSize: 12,margin: [20, 0, 0, 0]},
                            { text: remarksBody[2] || '',alignment: 'left', fontSize: 12,bold:true,margin: [0, 0, 0, 0]},
                            { text: remarksBody[3] || '',alignment: 'left', fontSize: 12,margin: [20, 0, 0, 0]},
                            { text: remarksBody[4] || '',alignment: 'left', fontSize: 12,bold:true,margin: [0, 0, 0, 0]},
                            { text: remarksBody[5] || '',alignment: 'left', fontSize: 12,margin: [20, 0, 0, 0]},
                            { text: remarksBody[6] || '',alignment: 'left', fontSize: 12,bold:true,margin: [0, 0, 0, 0]},
                            { text: remarksBody[7] || '',alignment: 'left', fontSize: 12,margin: [20, 0, 0, 0]},
                ]
                return data
            },
            signBy(data){
                let signname = data.noted[0].noted_by
                // let signname = signBy[0].prepared_by
             
                const parts = signname.split(' ');
                
 
            // Extract the name parts (excluding "GEN" and "PN")
            const nameParts = parts.slice(1, -1); // Remove first and last elements
           
            // Join the name parts with a space to form the full name
            this.fullname = nameParts.join(' ');
            this.rank = parts[0]
            this.service = parts[parts.length-1]
            
            
            },
            getPrint(data){
                this.signBy(data)
                let personnelTable = []
                let trainingTable = []    
                let equipmentTable = [] 
                let maintenanceTable = []  
                let facilityTable = []      
                let overAll = []

                let assessments = {
                            assess1: [],
                            assess2: [],
                            assess3: [],
                            assess4: [],
                            assess5: []
                        };

                // personnel
                data.tableData.personnels.forEach(item => {
                    let itemData = [
                        { text: item.unit || '', alignment: 'center',
                         fontSize:9,bold:true},
                        { text: item.required || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.actual || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.result?item.result.psgRating.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.grade || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.afpos || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.result?item.result.gradeRating.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.result?item.result.gradeRating.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: item.result?item.result.readiness.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9 },
                        { text: this.redCon(item.result.readiness).substr(0,2) || '', alignment: 'center', fontSize: data.select != 'hpn'?10:9,style:this.redConStyle(this.redCon(item.result.readiness)), },

                    ];

                    personnelTable.push(itemData);
                });
               
                // training
                data.tableData.trainings.forEach(item => {
                    let itemData = [
                        { text: item.unit || '', alignment: 'center', fontSize: 10 ,bold:true},
                        { text: item.result?item.result.oracValue.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: 10 },
                        { text: item.result?item.result.metValue.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: 10 },
                        { text:item.result?item.result.readiness.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: 10 },
                        { text: this.redCon(item.result.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(item.result.readiness)) },

                    ];

                    trainingTable.push(itemData);
                });
                

                // equipment
                data.tableData.equipments.forEach(item => {
                    let itemData = [
                        { text: item.unit || '', alignment: 'center', fontSize: 10 ,bold:true},
                        { text: item.result?item.result.readiness.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: 10 },
                        { text: this.redCon(item.result.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(item.result.readiness))},

                    ];

                    equipmentTable.push(itemData);
                });

                // maintenance
                data.tableData.maintenances.forEach(item => {
                    let itemData = [
                        { text: item.unit || '', alignment: 'center', fontSize: 10 ,bold:true},
                        { text: item.result?item.result.readiness.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: 10 },
                        { text: this.redCon(item.result.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(item.result.readiness))},

                    ];
                    maintenanceTable.push(itemData);
                });

                // facility
                data.tableData.facilities.forEach(item => {
                    let itemData = [
                        { text: item.unit || '', alignment: 'center', fontSize: 10 ,bold:true},
                        { text: item.result?item.result.readiness.toFixed(2) + '%':'' || '', alignment: 'center', fontSize: 10 },
                        { text: this.redCon(item.result.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(item.result.readiness))},

                    ];

                    facilityTable.push(itemData);
                });

                // overall
                this.tableData.forEach(item => {
                    let itemData = [
                        { text: item.column || '', alignment: 'center', fontSize: 10 ,bold:true},
                        { text: item.readiness.toFixed(2) + '%' || '', alignment: 'center', fontSize: 10, bold:item.column == 'OVERALL RATING'?true:false },
                        { text: this.redCon(item.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(item.readiness))},

                    ];

                    overAll.push(itemData);
                });

                //***********ASSESSSMENT
                data.allAssessment.forEach(row => {
                  for (let i = 1; i <= 5; i++) {
                        if(row.tab == i){
                            let remarks = JSON.parse(row.remarks)
                            remarks.forEach(item => {
                                if (item.question) {
                                    let question = [{ text: item.question || '', alignment: 'left', fontSize: 10, fillColor: 'lightgrey', bold: true }];
                                    assessments[`assess${i}`].push(question);
                                }
                                if (item.assessment) {
                                    let remarks =[{ text: item.assessment || '', alignment: 'left', fontSize: 10 }];
                                    assessments[`assess${i}`].push(remarks);
                                }
                            });
                        }
                    }     
                });

                
                

                let docDefinition = {
                content: [],
                    
                    // pageOrientation: 'portrait',

                    styles: {
                        tableHeader: {
                            fillColor: 'lightgrey',
                            bold: true,
                            alignment: 'center'
                        },
                        // tableBody: bodyStyle,
                        redCon1: { color: "white",fillColor: 'green'}, // Define styles as needed
                        redCon2: { color: "white",fillColor: 'blue' },
                        redCon3: { fillColor: 'yellow' },
                        redCon4: { color: "white",fillColor: 'red' },
                    },
                    footer(currentPage, pageCount) {
                        return { text: `Page ${currentPage.toString()} of ${pageCount.toString()}`, alignment: 'right', margin: [50, 0],fontSize: '6', };
                    },          
                };

                        

                            docDefinition.content.push(
                               // overall
                                {
                                    margin: [100, 0, 0, 0],
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
                                                { text: 'PHILIPPINE NAVY', alignment: 'center', fontSize: 15, margin: [0, 0, 0, 2] },
                                                "\n",
                                                { text: data.department, alignment: 'center', fontSize: 12, margin: [0, 0, 0, 2] },
                                                "\n",
                                                { text: this.user.departmentaddress, alignment: 'center', fontSize: 11, margin: [0, 0, 0, 2] },
                                                "\n",
                                                
                                            ],
                                            
                                        },
                                    ],
                                },
                                {
                                    text:[
                                        "\n",
                                        "\n"
                                ]
                                 },
                              
                                {
                                    text: [
                                            { text: moment().format('DD MMMM YYYY'), alignment: 'right', fontSize: 10, margin: [0, 0, 0, 0] },
                                    ],
                                },
                                {
                                    text: [
                                            
                                                { text: 'From: Commander,    ', alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0] },
                                                { text: data.department, alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0],bold:true },
                                                "\n",
                                                { text: 'To: ', alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0] },
                                                { text: `Flag Officer in Command,  Philippine Navy`, alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0],bold:true },
                                                "\n",
                                                { text: '.   ', alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0],color:'white', },
                                                { text: '(Attn: AC of NS for Operations, N3)', alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0] },
                                                "\n",
                                                "\n",
                                                { text: `Subj:  Joint Readiness Reporting System (JRRS) for ${data.monthString} `, alignment: 'left', fontSize: 10, margin: [0, 0, 0, 0],bold:true },

                                            ],
                                },
                                {
                                    text:[
                                        "\n",
                                        "\n"
                                    ]
                                },
                                {
                                    text:[
                                        "\n",
                                        
                                    ]
                                },
                                {
                                    text:[
                                        { text: `${this.user.departmentabr} JOINT READINESS REPORTING SYSTEM`, alignment: 'center', fontSize: 12,bold:true, decoration: 'underline' }
                                    ]
                                },
                            
                                {
                                    table: {
                                    headerRows: 1,
                                    widths: ['40%', '30%', '30%'],
                                    body: [
                                        [
                                            { text: "Key Measurement Area (KMA)", alignment: 'center', fontSize: 10,style:'tableHeader' },
                                            { text: "Rating", alignment: 'center', fontSize: 10,style:'tableHeader' },
                                            { text: "RedCon Level", alignment: 'center', fontSize: 10,style:'tableHeader' }
                                        ],
                                         ...overAll
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
                                
                                 
                            )
                      
                           
                        
                                 
                  
                                // OVER ALL
                                
                                docDefinition.content.push(
                                    {},
                                    { text: 'Recommendation:',alignment: 'left', fontSize: 12,bold:true,margin: [0, 20, 0, 0]},
                                    {
                                        text: data.recommend || '', alignment: 'left', fontSize: 10, // Extracting the 'text' property and joining into a string
                                        alignment: 'left',
                                        fontSize: 10,
                                        margin: [0, 20, 0, 0]
                                    },
                                    {},
                                    {
                                        margin: [0, 70, 0, 0],
                                        columns:[
                                            {},
                                            {},
                                            {},
                                            {
                                                text: [
                                                    {
                                                     text: 'APPROVED BY:',alignment: 'center', fontSize: 12},
                                                    "\n",
                                                    "\n",
                                                    "\n",
                                                    "\n",
                                                    { text: this.fullname, fontSize: 12,bold:true,margin: [0, 0, 0, 0]},
                                                    "\n",
                                                    { text: this.rank, fontSize: 12,},
                                                    { text: this.fullname.slice(this.service.length,this.fullname.length - (this.service.length + 2)),color:'white'},
                                                    { text: this.service, fontSize: 12,},
                                                ]
                                            }
                                        ],
                                    },  
                                    {},
             
                                    { text: '', pageBreak: 'after' },
                                )
            // PERSONNEL-----------------------
                    docDefinition.content.push(
                        {
                            text:[
                                "\n",
                                "\n",
                                { text: `${this.user.departmentabr} PERSONNEL READINESS REPORT`, alignment: 'left', fontSize: 12,bold:true, decoration: 'underline' }
                            ]
                        },
                        
                        {
                            table: {
                                headerRows: 1,
                                widths: ['10%', '10%', '10%','10%', '10%', '10%','10%','10%', '10%', '10%'],
                                body: [
                                    [
                                        { text: "UNIT", style:'tableHeader',margin: [0, 15],fontSize:8},
                                        { text: "TO", style:'tableHeader',margin: [0, 15],fontSize:8 },
                                        { text: "Actual \n Assigned", style:'tableHeader',margin: [0, 15],fontSize:8 },
                                        { text: "PSGR \n (Actual \n /TO x \n 100)", style:'tableHeader',margin: [0, 10],fontSize:7 },
                                        { text: "Rank \n (Nr \n /Aligned) \n PRR)", style:'tableHeader',margin: [0, 10],fontSize:7 },
                                        { text: "Specialty \n (Nr / Aligned) \n FOS and \n Rating)\n PSR", style:'tableHeader',fontSize:7 },
                                        { text: "PRR \n (Aligned \n /Actual x \n 100)", style:'tableHeader',margin: [0, 10],fontSize:7 },
                                        { text: "PSR \n (Aligned \n /Actual x \n 100)", style:'tableHeader',margin: [0, 10],fontSize:7 },
                                        { text: "READINESS", style:'tableHeader', margin: [0, 15],fontSize:8},
                                        { text: "RedCon", style:'tableHeader', margin: [0, 15],fontSize:8}
                                    
                                    ],
                                    ...personnelTable,
                                    [
                                        { text: this.readiness.personnel.name, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.required, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.actual, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.psgRating.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.grade, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.afpos, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.gradeRating.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.afposRating.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.readiness.personnel.readiness.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                        { text: this.redCon(this.readiness.personnel.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(this.readiness.personnel.readiness)),bold:true},
                                    ]
                                    
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
                         
                        this.remarksFunction(assessments.assess1),
            
                        { text: '', pageBreak: 'after' },
                    )
                        // }

                    // ******TRAINING----------------------------------
  
                            
                    docDefinition.content.push(
                        {
                            text:[
                                "\n",
                                "\n",
                                { text: `${this.user.departmentabr} TRAINING READINESS REPORT`, alignment: 'left', fontSize: 12,bold:true, decoration: 'underline' }
                            ]
                        },
                        
                        {
                            table: {
                            headerRows: 1,
                            widths: ['20%', '20%', '20%','20%', '20%'],
                            body: [
                                [
                                    { text: "UNIT", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "Percentage \n ORAC", alignment: 'center', fontSize: 10,style:'tableHeader', },
                                    { text: "Percentage \n METT", alignment: 'center', fontSize: 10,style:'tableHeader' },
                                    { text: "READINESS", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "RedCon", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},

                                ],
                                ...trainingTable,
                                [
                                    { text: this.readiness.training.name, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.readiness.training.oracValue.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.readiness.training.metValue.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.readiness.training.readiness.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.redCon(this.readiness.training.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(this.readiness.training.readiness)),bold:true},
                                ]
                                
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
                        this.remarksFunction(assessments.assess2),
                        {},
                        { text: '', pageBreak: 'after' }, 
                    )
                   

                    // ******EQUIPMENT----------------------------------
  
                            
                    docDefinition.content.push(
                        {
                            text:[
                                "\n",
                                "\n",
                                { text: `${this.user.departmentabr} EQUIPMENT READINESS REPORT`, alignment: 'left', fontSize: 12,bold:true, decoration: 'underline' }
                            ]
                        },
                        
                        {
                            table: {
                            headerRows: 1,
                            widths: ['40%', '30%', '30%'],
                            body: [
                                [
                                    { text: "UNIT", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "READINESS", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "RedCon", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},

                                ],
                                ...equipmentTable,
                                [
                                    { text: this.readiness.equipment.name, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.readiness.equipment.readiness.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.redCon(this.readiness.equipment.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(this.readiness.equipment.readiness)),bold:true},
                                ]
                                
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
                        this.remarksFunction(assessments.assess3),
                        {},
                        { text: '', pageBreak: 'after' }, 
                    )

                    // ******MAINTENANCE----------------------------------
  
                            
                    docDefinition.content.push(
                        {
                            text:[
                                "\n",
                                "\n",
                                { text: `${this.user.departmentabr} MAINTENANCE READINESS REPORT`, alignment: 'left', fontSize: 12,bold:true, decoration: 'underline' }
                            ]
                        },
                        
                        {
                            table: {
                            headerRows: 1,
                            widths: ['40%', '30%', '30%'],
                            body: [
                                [
                                    { text: "UNIT", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "READINESS", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "RedCon", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},

                                ],
                                ...maintenanceTable,
                                [
                                    { text: this.readiness.maintenance.name, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.readiness.maintenance.readiness.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.redCon(this.readiness.maintenance.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(this.readiness.maintenance.readiness)),bold:true},
                                ]
                                
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
                        this.remarksFunction(assessments.assess4),
                        {},
                        { text: '', pageBreak: 'after' }, 
                    )

                    // ******FACILITY----------------------------------
  
                            
                    docDefinition.content.push(
                        {
                            text:[
                                "\n",
                                "\n",
                                { text: `${this.user.departmentabr} FACILITY READINESS REPORT`, alignment: 'left', fontSize: 12,bold:true, decoration: 'underline' }
                            ]
                        },
                        
                        {
                            table: {
                            headerRows: 1,
                            widths: ['40%', '30%', '30%'],
                            body: [
                                [
                                    { text: "UNIT", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "READINESS", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},
                                    { text: "RedCon", alignment: 'center', fontSize: 10, margin:[0,2],style:'tableHeader'},

                                ],
                                ...facilityTable,
                                [
                                    { text: this.readiness.facility.name, alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.readiness.facility.readiness.toFixed(2)+'%', alignment:'center',fontSize:10,bold:true,fillColor: 'lightgrey',},
                                    { text: this.redCon(this.readiness.facility.readiness).substr(0,2) || '', alignment: 'center', fontSize: 10,style:this.redConStyle(this.redCon(this.readiness.facility.readiness)),bold:true},
                                ]
                                
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
                        this.remarksFunction(assessments.assess5),
                        {}
                    )

                    pdfMake.createPdf(docDefinition).download('READINESS_REPORT.pdf');
            }
        },
        computed:{
            tableData(){
                const tables = [
                    { table: 'personnel', name: 'Personnel Readiness',tab:1 },
                    { table: 'training', name: 'Training Readiness',tab:2 },
                    { table: 'equipment', name: 'Equipment Readiness',tab:3 },
                    { table: 'maintenance', name: 'Maintenance Readiness',tab:4 },
                    { table: 'facility', name: 'Facility Readiness',tab:5 }
                ];
                let totalReadiness = 0;
                let countReadiness = 0;
                let overallReadiness = 0;
                const data = [];
                tables.forEach((table) => {
                    const name = table.name;
                    const tab = table.tab;
                    const item = this.readiness[table.table];

                    if (item) {
                        const val = item
                        if (val && val.readiness) {
                            totalReadiness += val.readiness;
                            countReadiness += 1;
                            item.tab = tab;
                            item.column = name;
                            item.readiness = val.readiness;
                        }
                    }
                    data.push(item);
                });

                if (countReadiness > 0) {
                    overallReadiness = totalReadiness / countReadiness;
                }

                const overallRating = { column: 'OVERALL RATING', readiness: overallReadiness, total: true };
                data.push(overallRating);
                return data
                
            },
            ...mapState([
                'user'
            ])
        },
        mounted(){
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
        }
    }
</script>

<style scoped>

</style>