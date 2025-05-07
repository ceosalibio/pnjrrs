import axiosHttp from '../../axiosHttp'
import { list } from './list'
import { data } from './data'
import { decryptBase, setItem, removeItem, getItem } from './crytp'

function deleteItem(val){
    axiosHttp.post('deleteItem',{
        data : val
    }).then((res)=>{
        if(res.status == 200){
            val.rel()
        }
    }).catch(err=>{
        console.log(err)
    }).finally(()=>{
        // data.overlay = false
    })
}


export const crud = {
    deleteItem
}

