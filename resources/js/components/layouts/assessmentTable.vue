<template>
    <div class="mt-10">
        <v-divider  />
        <h2>Assessment:</h2>
        <v-card elevation="2" v-if="Object.keys(obj).length > 0">
             <div  v-if="obj.tab != '6'">
                <v-simple-table
                    class="custom-table mt-4"
                    fixed-header
                
                >
                    <tbody v-for="(item, i) in obj.assessment" :key="i">
                        <tr>
                            <td class="question">{{ item.question }}</td>
                        </tr>
                        <tr>
                            <td class="answer">{{ item.assessment }}</td>
                        </tr>
                    </tbody>
                </v-simple-table>
             </div>
             
             <div v-else class="ma-3 mt-4">
                <p class="answer ">{{ obj.recommend }}</p>
             </div>
            
    
    </v-card>
    </div>
</template>

<script>
    export default {
        props:[
            'obj'
        ]
    }
</script>

<style scoped>
    .question{
        font-weight: bold;
        text-align: left !important;
        background-color: lightgray;
    }
    .answer{
        text-align: left !important;
        white-space: pre-wrap;
    }

</style>