<template>
    <div>
        <v-snackbar
            v-model="snackbar.show" 
            :color="snackbar.color"
            :timeout="timeout"
            top
            elevation="5"
            min-width="0"
      
            >
         {{snackbar.message}}
         <v-icon>{{snackbar.icon}}</v-icon> 
       </v-snackbar>
    </div>
</template>

<script>
    export default {
        props:[
            'snackbar'
        ],
        data() {
            return {
                timeout:1500,
            }
        },  
    }
</script>

<style  scoped>

</style>