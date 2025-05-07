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
                Promise.all([this.exportReadinnes()])
            },
            async exportReadinnes() {
                // Create a new workbook
                // console.log(this.readinessData)
                const workbook = new ExcelJS.Workbook();
                // Add a worksheet
                const worksheet = workbook.addWorksheet('Sheet1');
                // Define the column headers
                const headers = [
                                    'Category',
                                    'Required Area',
                                    'Actual Area',
                                    'Quantitative',
                                    'Qualitative',
                                    'Total',
                                    'Percentage'
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
                    'Category',
                    'Required',
                    'Actual',
                    'Quantitative',
                    'Qualitative',
                    'Total',
                    'Percentage',
                ];
                // Add the data rows to the worksheet
                this.readinessData.forEach(data => {
                        const row = selectedColumns.map(column => data[column] || '');
                        let excelRow = worksheet.addRow(row);

                            const requiredIndex = selectedColumns.indexOf('Required');
                            const actualIndex = selectedColumns.indexOf('Actual');
                            const quanIndex = selectedColumns.indexOf('Quantitative');
                            const qualIndex = selectedColumns.indexOf('Qualitative');
                            const totalIndex = selectedColumns.indexOf('Total');
                            const percentIndex = selectedColumns.indexOf('Percentage');

                            if (data.Required && requiredIndex !== -1) {
                                excelRow.getCell(requiredIndex + 1).alignment = { horizontal: 'center' };
                            }

                            if (data.Actual && actualIndex !== -1) {
                                excelRow.getCell(actualIndex + 1).alignment = { horizontal: 'center' };
                            }

                            if (data.Quantitative && quanIndex !== -1) {
                                excelRow.getCell(quanIndex + 1).alignment = { horizontal: 'center' };
                            }

                            if (data.Qualitative && qualIndex !== -1) {
                                excelRow.getCell(qualIndex + 1).alignment = { horizontal: 'center' };
                            }

                            if (data.Total && totalIndex !== -1) {
                                excelRow.getCell(totalIndex + 1).alignment = { horizontal: 'center' };
                            }

                            if (data.Percentage && percentIndex !== -1) {
                                excelRow.getCell(percentIndex + 1).alignment = { horizontal: 'center' };
                            }
                });
                const totalRow = ['', '', '', '', '', 'TOTAL', this.excelData.result.toFixed(2) + '%'];
                const excelTotalRow = worksheet.addRow(totalRow);
                // Center and bold the "TOTAL" cell
                excelTotalRow.getCell(6).alignment = { horizontal: 'center' };
                excelTotalRow.getCell(6).font = { bold: true };

                // Center and bold the percentage cell
                excelTotalRow.getCell(7).alignment = { horizontal: 'center' };
                excelTotalRow.getCell(7).font = { bold: true };
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
        },
        computed:{
            readinessData(){
                let name = ['pa_','sa_','hs_','bp_']
                let data = []
                name.forEach((item,i) => {
                    let obj = {
                        Category :this.excelData.master[`${item}name`],
                        Required :this.excelData.master[`${item}required`],
                        Actual: this.excelData.master[`${item}actual`],
                        Quantitative : this.excelData.master[`${item}quantitativeResult`].toFixed(2) + '%',
                        Qualitative: this.excelData.master[`${item}qualitativeResult`].toFixed(2) + '%',
                        Total:this.excelData.master[`${item}totalRating`].toFixed(2) + '%',
                        Percentage:this.excelData.master[`${item}weight`].toFixed(2) + '%'
                    }
                    data.push(obj)
                });
                return data
            }
        }

        
    }
</script>

<style scoped>

</style>