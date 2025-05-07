import{ data } from '../details/data.js'
import{ list } from '../details/list.js'

const details = []
const rating = {}
let psgTable = [];

function getPsgTable(items) {
    const newPsgTable = list.gradeList.reduce((total, item) => {
      let i = total.findIndex((res) => res.GradeName == item.GradeName);
      if (i == -1) {
        let obj = { ...item };
        obj.required = 0;
        obj.actual = 0;
        items.tableData.forEach((val) => {
          if (val.GradeName == obj.GradeName && val.RequiredMp == 1) {
            obj.required += 1;
          }
          if (val.GradeActual == obj.GradeName) {
            obj.actual += 1;
          }
        });
        total.push(obj);
      } else {
        total.push(item);
      }
      return total;
    }, []).reduce((j, k) => {
      let e = j.findIndex((r) => r.DivisionCode == k.DivisionCode);
      if (e == -1) {
        k.Division = k.DivisionCode == '001' ? 'Officer' : k.DivisionCode == '002' ? 'Enlisted' : 'Civillian';
        k.requiredTotal = k.required;
        k.actualTotal = k.actual;
        j.push(k);
      } else {
        j[e].requiredTotal += k.required;
        j[e].actualTotal += k.actual;
        k.requiredTotal = j[e].requiredTotal;
        k.actualTotal = j[e].actualTotal;
        j.push(k);
      }
      return j;
    }, []);
    return newPsgTable;
  }

export const personnel = {
    details,
    rating,
    psgTable,
    getPsgTable
}