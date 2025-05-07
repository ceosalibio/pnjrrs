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
                let val = this.excelData
                // Create a new workbook
                const workbook = new ExcelJS.Workbook();
                // Add a worksheet
                const worksheet = workbook.addWorksheet('Sheet1');
                // Define the column headers
                const headers = ['MISSION ESSENTIAL TASK','1st Qtr','2nd Qtr','3rd Qtr','4th Qtr','REQUIRED','ACTUAL','PERCENTAGE'];
               
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
                'met',
                'q1',
                'q2',
                'q3',
                'q4',
                'required',
                'actual',
                'percent'
             ];
       
             // Add the data rows to the worksheet
            val.tableData.forEach(data => {
                const row = selectedColumns.map(column => {
                        if (column === 'percent' && data[column]) {
                            return parseFloat(data[column]).toFixed(2)+'%'; // Apply toFixed(2) if column is "Actual"
                        } else {
                            return data[column] || '';
                        }
                    });
                worksheet.addRow(row);
             });

             const row2 = ['','','','','',val.required,val.actual,val.result.metPercentage.toFixed(2)+'%']
             let excelRow2 = worksheet.addRow(row2);
             excelRow2.eachCell((cell)=>{
                cell.font = { bold: true };
             })
            //  // Set the column widths
             worksheet.columns.forEach((column,i) => {
                if(i == 0 || i == 1){
                    column.width = 30;
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
                    cell.alignment = { horizontal: 'center' }
                    cell.alignment = { vertical: 'middle' }
               
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
                link.download = `${this.excelData.team}-${this.excelData.monthString}-Training List.xlsx`;
    
                // Trigger the download
                link.click();
    
                // Clean up the URL object
                window.URL.revokeObjectURL(url);
            },



            async exportReadinnes() {
                let val = this.excelData
                // Create a new workbook
                const workbook = new ExcelJS.Workbook();
                // Add a worksheet
                const worksheet = workbook.addWorksheet('Sheet1');
                // Define the column headers
                const headers = ['PERCENTAGE \n ORAC','PERCENTAGE \n METT','TRR'];
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
              
                const row1 = [val.result.oracPercentage.toFixed(2)+'%',val.result.metPercentage.toFixed(2)+'%',val.result.readiness.toFixed(2)+'%'];
                worksheet.addRow(row1)
                const row2 = [val.result.oracValue.toFixed(2)+'%',val.result.metValue.toFixed(2)+'%'];
                worksheet.addRow(row2)
                worksheet.mergeCells(2,3,3,3)
               
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
                link.download = `${this.excelData.team}-${this.excelData.monthString}-Training Readiness Report.xlsx`;
    
                // Trigger the download
                link.click();
    
                // Clean up the URL object
                window.URL.revokeObjectURL(url);
            }



            
        },
    }
</script>

<style scoped>

</style>