import axiosHttp from '../../axiosHttp'
import { list } from './list'
import { data } from './data'
import { decryptBase } from './crytp'


function getCategory(){
    axiosHttp.get('getCategory').then((res)=>{
        list.categoryList = res.data 
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}

function getDepartment(){
    data.department = ''
    data.section = ''
    data.team = ''
    axiosHttp.post('getDepartment',{
        data : data.category
    }).then(res=>{
        list.departmentList = res.data 
        data.office = res.data.find(r=> r.office == 1)
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}

function getSection(){
    data.section = ''
    data.team = ''
    axiosHttp.post('getSection',{
        data : data.department
    }).then(res=>{
        list.sectionList = res.data 
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}

function getTeam(){
    data.team = ''
    axiosHttp.post('getTeam',{
        data : data.section
    }).then(res=>{
        list.teamList = res.data
        unitInformation()

    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}

function unitInformation(unit){
    axiosHttp.post('unitInformation',{
        section : data.section,
        team : data.team,
        unit : unit
    }).then(res=>{
        let val = res.data
        val.forEach(r => {
            data.unitCode = r.code_rel;
            data.office = r.office;
            data.topOffice = r.restrict;
            data.unitRights = r.rights;
            data.hpn = r.hpn;
        });
        
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}

function getRank(){
    axiosHttp.get('getRank').then(res=>{
        list.rankList = decryptBase(res.data)
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}
function getAfpos(){
    axiosHttp.get('getAfpos').then(res=>{
        list.afposList = decryptBase(res.data)
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}

function gradeRequired(){
    axiosHttp.get('gradeRequired').then(res=>{
        list.gradeList = decryptBase(res.data)
    }).catch(err=>{
        console.log(err)
        alert('Error.Please refresh to load');
    })
}


export const unitFilter = {
    getCategory,
    getDepartment,
    getSection,
    getTeam,
    unitInformation,
    getRank,
    getAfpos,
    gradeRequired
}