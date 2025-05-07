 function redCon(data){
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
}

function redStyle(val){
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
}
export const red = {
    redCon,
    redStyle
}