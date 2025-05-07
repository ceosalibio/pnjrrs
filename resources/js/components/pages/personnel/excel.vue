<template>
    <div>

    </div>
</template>

<script>
    import ExcelJS from 'exceljs';
    export default {
        props:[
            'excelData'
        ],
        data() {
            return {
                
            }
        },
        methods: {
            getExcel(){
                Promise.all([this.exportTable(),this.exportReadinnes()])
            },
            async exportTable() {
                // let val = this.excelData
                // Create a new workbook
                const workbook = new ExcelJS.Workbook();
                // Add a worksheet
                const worksheet = workbook.addWorksheet('Sheet1');
                // Define the column headers
                const headers = [
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

               
                 // Add the headers to the worksheet
                 const headerRow = worksheet.addRow(headers);

                headerRow.eachCell((cell) => {
                cell.alignment = { horizontal: 'center' };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFFF00' } // Change 'FFFF00' to the desired color code
                };
                cell.font = { bold: true };
                
                });

             const selectedColumns = [
                'Description',
                'Name',
                'GradeName',
                'GradeActual',
                'GradePoint',
                'AfposName',
                'AfposActual',
                'AfposPoint',
                'RequiredMp'
             ];

            //   const selectedColumns = [
            //     'UnitTeamCode',
            //     'RankCode',
            //     'RankName',
            //     'ServiceCode',
            //     'SerialNo',
            //     'Name',
            //     'rowcode'
            //  ];
            
       
             // Add the data rows to the worksheet
             this.excelTableData.forEach(data => {
                const row = selectedColumns.map(column => data[column] || '');
                let excelRow = worksheet.addRow(row);
               
                    //     // Check if data.Title is equal to '1'
                        if (data.Title == '1') {
                            // Loop through each cell in the newly added row
                            excelRow.eachCell((cell) => {
                                cell.font = { bold: true };
                                cell.fill = {
                                    type: 'pattern',
                                    pattern: 'solid',
                                    fgColor: { argb: '808080' } // Change 'FFFF00' to the desired color code
                                };
                                cell.alignment = { horizontal: 'center' };
                            });
                        }else if(data.Title == '2'){
                            excelRow.eachCell((cell) => {
                                cell.font = { bold: true };
                            });
                        }
                        selectedColumns.forEach(column => {
                            const columnIndex = selectedColumns.indexOf(column);
                            if (data[column] && columnIndex !== -1) {
                                const cell = excelRow.getCell(columnIndex + 1);
                                if (column !== 'Description' && column !== 'Name') {
                                    cell.alignment = { horizontal: 'center' };
                                }
                            }
                        });

             });
            
            //  // Set the column widths
             worksheet.columns.forEach((column,i) => {
                if(i == 0 || i == 1){
                    column.width = 60;
                }else{
                    column.width = 15;
                }
                 
             });
            worksheet.eachRow((row) => {
                row.eachCell((cell) => {
                    cell.border = {
                        top: { style: 'thin' },
                        left: { style: 'thin' },
                        bottom: { style: 'thin' },
                        right: { style: 'thin' },
                    };
               
                });
            });
                // Generate a buffer from the workbook
                const buffer = await workbook.xlsx.writeBuffer();
    
                // Create a Blob from the buffer
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    
                // Create a download link
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `${this.excelData.details.team}-${this.excelData.details.monthString}-Personnel List.xlsx`;
    
                // Trigger the download
                link.click();
    
                // Clean up the URL object
                window.URL.revokeObjectURL(url);
            },



            async exportReadinnes() {
                let val = this.excelData.rating
                // Create a new workbook
                const workbook = new ExcelJS.Workbook();
                // Add a worksheet
                const worksheet = workbook.addWorksheet('Sheet1');
                // Define the column headers
                const headers = ['RATING','PERCENTAGE','WEIGHT PERCENTAGE'];
                // Add the headers to the worksheet
                const headerRow = worksheet.addRow(headers);

             headerRow.eachCell((cell) => {
                cell.alignment = { horizontal: 'center' };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFFF00' } // Change 'FFFF00' to the desired color code
                };
                cell.font = { bold: true };
               
            });
              
                const row1 = ['PSGR (50%)',val.psgRating,val.psgRating05];
                worksheet.addRow(row1)
                const row2 = ['Personnel Qualification Rating (50%)',val.pqrRating05,val.pqrRatingTotal];
                worksheet.addRow(row2)
                const row3 = ['PRR (40%)',val.gradeRating,val.gradeRating04];
                worksheet.addRow(row3)
                const row4 = ['PSR (60%)',val.afposRating,val.afposRating06];
                worksheet.addRow(row4)
                const row5 = ['TOTAL (PSGR + PQR)','',val.readiness];
                worksheet.addRow(row5)
               
                 // Set the column widths
                worksheet.columns.forEach((column,i) => {
                    if(i == 0){
                        column.width = 60;
                    }else{
                        column.width = 20;
                    }
                    
                });
                worksheet.eachRow((row) => {
                    row.eachCell((cell) => {
                        cell.border = {
                            top: { style: 'thin' },
                            left: { style: 'thin' },
                            bottom: { style: 'thin' },
                            right: { style: 'thin' },
                        };
                        cell.alignment = { horizontal: 'center' };
                
                    });
                });
                // Generate a buffer from the workbook
                const buffer = await workbook.xlsx.writeBuffer();
    
                // Create a Blob from the buffer
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    
                // Create a download link
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `${this.excelData.details.team}-${this.excelData.details.monthString}-Personnel Readiness Report.xlsx`;
    
                // Trigger the download
                link.click();
    
                // Clean up the URL object
                window.URL.revokeObjectURL(url);
            }



            
        },
        computed:{
            excelTableData(){
                let data = []
                let table =  this.excelData.details.tableData
                // let description = ''
                table.forEach((item) => {
                    let office = {
                            Description : item.OfficeName?item.OfficeName:item.BranchName,
                            Name : '',
                            GradeName : '',
                            GradeActual : '',
                            GradePoint : '',
                            AfposName : '',
                            AfposActual : '',
                            AfposPoint : '',
                            RequiredMp : '',
                            Title : item.OfficeName?1:2,
                        }
                    let obj = {
                            Description : item.PositionName,
                            Name : item.Name?`${item.Rank} ${item.Name} ${item.Service}`:'',
                            GradeName : item.GradeName,
                            GradeActual : item.GradeActual,
                            GradePoint : item.GradePoint,
                            AfposName : item.AfposName,
                            AfposActual : item.AfposActual,
                            AfposPoint : item.AfposPoint,
                            RequiredMp : item.RequiredMp,
                            Title: 0
                        }
                   
                    if(item.OfficeName || item.BranchName && !item.PositionName){
                        data.push(office)
                    }else{
                        data.push(obj)
                    }
                   
                    // if(item.PositionName != 'office_unit'){
                    //     data.push(obj)
                    // }

                })
                return data
            }
        }

        
    }
</script>

<style scoped>

</style>