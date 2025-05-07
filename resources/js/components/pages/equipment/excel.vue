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
                const headers = ['Description','Required','Actual'];
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
                'description',
                'required',
                'actual'
             ];
             // Add the data rows to the worksheet
             val.tableData.forEach(data => {
                     const row = selectedColumns.map(column => data[column] || '');
                     let excelRow = worksheet.addRow(row);

                        // Check if data.Title is equal to '1'
                        if (data.Title) {
                            // Loop through each cell in the newly added row
                            excelRow.eachCell((cell) => {
                                cell.font = { bold: true };
                            });
                        }
                        // Find the index of the "Required" and "Actual" columns
                        const requiredIndex = selectedColumns.indexOf('required');
                        const actualIndex = selectedColumns.indexOf('actual');

                        if (data.required && requiredIndex !== -1) {
                            // Set the horizontal alignment to center for the "Required" column
                            excelRow.getCell(requiredIndex + 1).alignment = { horizontal: 'center' };
                        }

                        if (data.actual && actualIndex !== -1) {
                            // Set the horizontal alignment to center for the "Actual" column
                            excelRow.getCell(actualIndex + 1).alignment = { horizontal: 'center' };
                        }
             });
            //  // Set the column widths
             worksheet.columns.forEach((column,i) => {
                if(i == 0){
                    column.width = 70;
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
                link.download = `${this.excelData.team}-${this.excelData.monthString}-Equipment List.xlsx`;
    
                // Trigger the download
                link.click();
    
                // Clean up the URL object
                window.URL.revokeObjectURL(url);
            },



            async exportReadinnes() {
                // Create a new workbook
                // console.log(this.readinessData)
                const workbook = new ExcelJS.Workbook();
                // Add a worksheet
                const worksheet = workbook.addWorksheet('Sheet1');
                // Define the column headers
                const headers = ['','','TE','ACTUAL','%'];
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
                    'Category',
                    'Sub',
                    'Required',
                    'Actual',
                    'Percent'
                ];
                // Add the data rows to the worksheet
                this.readinessData.forEach(data => {
                        const row = selectedColumns.map(column => data[column] || '');
                        let excelRow = worksheet.addRow(row);

                            // Check if data.Title is equal to '1'
                            // if (data.Title) {
                            //     // Loop through each cell in the newly added row
                            //     excelRow.eachCell((cell) => {
                            //         cell.font = { bold: true };
                            //     });
                            // }
                            // Find the index of the "Required" and "Actual" columns
                            const requiredIndex = selectedColumns.indexOf('Required');
                            const actualIndex = selectedColumns.indexOf('Actual');
                            const percentIndex = selectedColumns.indexOf('Percent');

                            if (data.Required && requiredIndex !== -1) {
                                // Set the horizontal alignment to center for the "Required" column
                                excelRow.getCell(requiredIndex + 1).alignment = { horizontal: 'center' };
                            }

                            if (data.Actual && actualIndex !== -1) {
                                // Set the horizontal alignment to center for the "Actual" column
                                excelRow.getCell(actualIndex + 1).alignment = { horizontal: 'center' };
                            }
                            if (data.Percent && percentIndex !== -1) {
                                // Set the horizontal alignment to center for the "Actual" column
                                excelRow.getCell(percentIndex + 1).alignment = { horizontal: 'center' };
                            }
                });
                //  // Set the column widths
                worksheet.columns.forEach((column,i) => {
                    if(i == 0 || i == 1){
                        column.width = 40;
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
                link.download = `${this.excelData.team}-${this.excelData.monthString}-Equipment Readiness Report.xlsx`;
    
                // Trigger the download
                link.click();
    
                // Clean up the URL object
                window.URL.revokeObjectURL(url);
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
                            data = this.excelData.result.avePrimary.toFixed(2) + '%'
                            break;
                        case 'Total2':
                            data = this.excelData.result.aveSecondary.toFixed(2) + '%'
                            break;
                        case 'MEE':
                            data = this.excelData.result.mee.toFixed(2) + '%'
                            break;
                        case 'SEE':
                            data = this.excelData.result.see.toFixed(2) + '%'
                            break;
                    
                        default:
                            break;
                    }
                }
                return data
            }
        },
        computed:{
            readinessData(){
                let data = []
                this.excelData.master.forEach((item,i) => {
                    
                
                    let obj = {
                        Title :null,
                        Category :null,
                        Sub: null,
                        Required : null,
                        Actual: null,
                        Percent:null,
                        Total:null,
                        Primary:null,
                        Secondary:null,
                    }
                   
                    if(['MOBILITY','FIREPOWER'].includes(item.category) && item.category == item.sub){
                        obj.Title = 1
                        obj.Category = item.category
                        obj.Required = ''
                        obj.Actual = ''
                        obj.Percent = ''
                    }
                    if(!item.title){
                        obj.Category = item.category
                        obj.Sub = this.sub(item)
                        obj.Required = item.required
                        obj.Actual = item.actual
                        obj.Percent = this.percent(item)
                    }

                    data.push(obj)

                    if(!this.excelData.master[i +1]){
                        let nextLine = {
                            Title : '',
                            Category : '',
                            Sub : 'MEE +SEE',
                            Required : '',
                            Actual : '',
                            Percent : Number(this.excelData.result.readiness.toFixed(2)) + '%'
                        }
                        data.push(nextLine)
                    }

                });
                return data
            }
        }

        
    }
</script>

<style scoped>

</style>