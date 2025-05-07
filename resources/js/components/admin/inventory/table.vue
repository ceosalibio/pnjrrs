<template>
    <div>
        <v-card height="60vh">
            <v-simple-table 
                fixed-header
            >
                <thead>
                    <tr>
                        <th v-for="(item,i) in headers" :key="i">{{item}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in table" :key="i">
                        <td>{{ tab != '7'?item.des:item.cat}}</td>
                        <td>{{item.req}}</td>
                        <td>{{item.act}}</td>
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card>
        
    </div>
</template>

<script>
    export default {
        props:[
            'table',
            'tab'
        ],
        data:() =>({
            headers:['ITEM','REQUIRED','ACTUAL'],
        })
    }
</script>

<style  scoped>

</style>