let all = {
    getReadiness: function(obj) {

      let personnels = getPersonnel(obj);
      let trainings = getTraining(obj);
      let equipments = getEquipment(obj);
      let maintenances = getMaintenance(obj);
      let facilities = getFacility(obj);
      return { 
            personnels,
            trainings,
            equipments,
            maintenances,
            facilities
         };
    }
  };
//   getting personnel readiness
  function getPersonnel(obj) {

    if (obj.personnels) {
      return obj.personnels.reduce((acc, cur) => {
        let i = acc.findIndex(r=> r.unitcode == cur.unitcode)
        if(i == -1){
          if(cur.result){
              acc.push(cur)
          }else{
            cur.actual = 0
            cur.required = 0
            cur.grade = 0
            cur.afpos = 0
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
            acc.push(cur)
          }
        }else{
          
            if(cur.result){
              
                acc[i].actual += cur.actual? Number(cur.actual) : 0
                acc[i].required += cur.required? Number(cur.required) : 0
                acc[i].grade += cur.grade? Number(cur.grade) : 0
                acc[i].afpos += cur.afpos? Number(cur.afpos) : 0
                
                    acc[i].result.psgRating = (acc[i].actual / acc[i].required) * 100;
                    acc[i].result.gradeRating = (acc[i].grade / acc[i].actual) * 100;
                    acc[i].result.gradeRating04 = acc[i].result.gradeRating * 0.4;
                    acc[i].result.afposRating = (acc[i].afpos / acc[i].actual) * 100;
                    acc[i].result.afposRating06 = acc[i].result.afposRating * 0.6;
                    acc[i].result.psgRating05 = acc[i].result.psgRating * 0.5;
                    acc[i].result.pqrRatingTotal = acc[i].result.gradeRating04 + acc[i].result.afposRating06;
                    acc[i].result.pqrRating05 = acc[i].result.pqrRatingTotal * 0.5;
                    acc[i].result.readiness = acc[i].result.pqrRating05 + acc[i].result.psgRating05;
            }
        }
        return acc
      },[])
      
    } else {
      return [];
    }
  }
//   getting training readiness  
  function getTraining(obj) {
    if (obj) {
      return obj.trainings.reduce((acc, cur) => {
        let i = acc.findIndex(r=> r.unitcode == cur.unitcode)
        if(i == -1){
            cur.count = 1
            cur.orac = cur.result? Number(cur.result.oracValue) : 0;
            cur.met = cur.result? Number(cur.result.metValue) : 0;
            if(!cur.result){
              cur.result = {
                oracValue : 0,
                metValue : 0,
                readiness : 0
              }
            }
            acc.push(cur)
        }else{
            if(cur.result){
                acc[i].count += 1;
                acc[i].orac += cur.result.oracValue? Number(cur.result.oracValue) : 0;
                acc[i].met += cur.result.metValue? Number(cur.result.metValue) : 0;
                acc[i].result.oracValue = acc[i].orac / acc[i].count;
                acc[i].result.metValue = acc[i].met / acc[i].count;
                acc[i].result.readiness = acc[i].result.oracValue + acc[i].result.metValue;
            }
        }
        return acc
      },[])
    } else {
      return [];
    }
  }
//   getting equipment readiness 
  function getEquipment(obj) {
    if (obj) {
        return obj.equipments.reduce((acc, cur) => {
            let i = acc.findIndex(r=> r.unitcode == cur.unitcode)
            // console.log(cur)
            if(i == -1){
                cur.count = 1
                cur.ready = cur.result ? Number(cur.result.readiness) : 0
                if(!cur.result){
                  cur.result = {
                    readiness : 0
                  }
                }
                acc.push(cur)
            }else{
                if(cur.result){
                    acc[i].count += 1;
                    acc[i].ready += cur.result.readiness ? Number(cur.result.readiness) : 0
                    acc[i].result.readiness = acc[i].ready / acc[i].count;
                }
            }
            return acc
        },[])
    }else{
        return []
    }
  }

  //   getting maintenance readiness 
  function getMaintenance(obj) {
    if (obj) {
        return obj.maintenances.reduce((acc, cur) => {
            let i = acc.findIndex(r=> r.unitcode == cur.unitcode)
            // console.log(cur)
            if(i == -1){
                cur.count = 1
                cur.ready = cur.result ? Number(cur.result.readiness) : 0
                if(!cur.result){
                  cur.result = {
                    readiness : 0
                  }
                }
                acc.push(cur)
            }else{
                if(cur.result){
                    acc[i].count += 1;
                    acc[i].ready += cur.result.readiness ? Number(cur.result.readiness) : 0
                    acc[i].result.readiness = acc[i].ready / acc[i].count;
                }
            }
            return acc
        },[])
    }else{
        return []
    }
  }

  //   getting facility readiness 
  function getFacility(obj) {
    if (obj) {
        return obj.facilities.reduce((acc, cur) => {
            let i = acc.findIndex(r=> r.unitcode == cur.unitcode)
            // console.log(cur)
            if(i == -1){
                cur.count = 1
                cur.ready = cur.result ? Number(cur.result.readiness) : 0
                if(!cur.result){
                  cur.result = {
                    readiness : 0
                  }
                }
                acc.push(cur)
            }else{
                if(cur.result){
                    acc[i].count += 1;
                    acc[i].ready += cur.result.readiness ? Number(cur.result.readiness) : 0
                    acc[i].result.readiness = acc[i].ready / acc[i].count;
                }
            }
            return acc
        },[])
    }else{
        return []
    }
  }
  
  export default all;