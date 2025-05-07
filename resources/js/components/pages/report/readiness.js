let total = {
    getReadiness: function(obj) {
  
      let personnel = getPersonnel(obj);
      let training = getTraining(obj);
      let equipment = getEquipment(obj);
      let maintenance = getMaintenance(obj);
      let facility = getFacility(obj);
      return { 
            personnel,
            training,
            equipment,
            maintenance,
            facility
         };
    }
  };
//   getting personnel readiness
  function getPersonnel(obj) {
    if (obj.tableData) {
      return obj.tableData.personnels.reduce((acc, cur) => {
        acc.actual += cur.actual? Number(cur.actual) : 0;
        acc.required += cur.required? Number(cur.required) : 0;
        acc.grade += cur.grade? Number(cur.grade) : 0;
        acc.afpos += cur.afpos? Number(cur.afpos) : 0;
        if (cur.result && acc.actual && acc.required) {
          acc.psgRating = (acc.actual / acc.required) * 100;
          acc.gradeRating = (acc.grade / acc.actual) * 100;
          acc.gradeRating04 = acc.gradeRating * 0.4;
          acc.afposRating = (acc.afpos / acc.actual) * 100;
          acc.afposRating06 = acc.afposRating * 0.6;
          acc.psgRating05 = acc.psgRating * 0.5;
          acc.pqrRatingTotal = acc.gradeRating04 + acc.afposRating06;
          acc.pqrRating05 = acc.pqrRatingTotal * 0.5;
          acc.readiness = acc.pqrRating05 + acc.psgRating05;
        }else{
          cur.result = {
            psgRating : 0,
            gradeRating : 0,
            gradeRating04 : 0,
            afposRating : 0,
            afposRating06 : 0,
            psgRating05 : 0,
            pqrRatingTotal : 0,
            pqrRating05 : 0,
            readiness : 0
          }
        }
        return acc;
      }, {
        name: 'TOTAL',
        actual: 0,
        required: 0,
        grade: 0,
        afpos: 0,
        psgRating: 0,
        gradeRating: 0,
        gradeRating04: 0,
        afposRating: 0,
        afposRating06: 0,
        psgRating05: 0,
        pqrRatingTotal: 0,
        pqrRating05: 0,
        readiness: 0
      });
    } else {
      return {};
    }
  }
//   getting training readiness  
  function getTraining(obj) {
    if (obj.tableData) {
      let count = 0;
      let orac = 0;
      let met = 0;
      return obj.tableData.trainings.reduce((acc, cur) => {
        if (cur.result && cur.result.readiness) {
          count += 1;
          orac += cur.result.oracValue? Number(cur.result.oracValue) : 0;
          met += cur.result.metValue? Number(cur.result.metValue) : 0;
          acc.oracValue = orac / count;
          acc.metValue = met / count;
          acc.readiness = acc.oracValue + acc.metValue;
        }else{
          cur.result = {
            oracValue : 0,
            metValue : 0,
            readiness : 0
          }
        }
        return acc;
      }, {
        name: 'TOTAL',
        oracValue: 0,
        metValue: 0,
        readiness: 0
      });
    } else {
      return {};
    }
  }
//   getting equipment readiness 
  function getEquipment(obj) {
    if (obj.tableData) {
        let count = 0;
        let ready = 0;
        return obj.tableData.equipments.reduce((acc, cur) => {
            if(cur.result && cur.result.readiness){
                count += 1;
                ready += cur.result.readiness ? Number(cur.result.readiness) : 0
                acc.readiness = ready /count
            }else{
              cur.result = {
                readiness : 0
              }
            }
            return acc
        }, { 
                name: 'TOTAL',
                readiness: 0
            }
        )
    }else{
        return {}
    }
  }

  //   getting maintenance readiness 
  function getMaintenance(obj) {
    if (obj.tableData) {
        let count = 0;
        let ready = 0;
        return obj.tableData.maintenances.reduce((acc, cur) => {
            if(cur.result && cur.result.readiness){
                count += 1;
                ready += cur.result.readiness ? Number(cur.result.readiness) : 0
                acc.readiness = ready /count
            }else{
              cur.result = {
                readiness : 0
              }
            }
            return acc
        }, { 
                name: 'TOTAL',
                readiness: 0
            }
        )
    }else{
        return {}
    }
  }

  //   getting facility readiness 
  function getFacility(obj) {
    if (obj.tableData) {
        let count = 0;
        let ready = 0;
        return obj.tableData.facilities.reduce((acc, cur) => {
            if(cur.result && cur.result.readiness){
                count += 1;
                ready += cur.result.readiness ? Number(cur.result.readiness) : 0
                acc.readiness = ready /count
            }else{
              cur.result = {
                readiness : 0
              }
            }
            return acc
        }, { 
                name: 'TOTAL',
                readiness: 0
            }
        )
    }else{
        return {}
    }
  }
  
  export default total;