<template>
   <Appbar  />
</template>

<script>
  import Appbar from '../components/layouts/Appbar.vue';
 export default {
   components:{
      Appbar
   }
 }
</script>
<style>
  html, body, #app {
    width: 100%;
     height: 100%;
     /*overflow: hidden;*/
     margin: 0;
  }

  .main-page{
    display: flex;
    flex-direction: column;
    height: 100vh;
    width: 100vw;
  }

  .main-content{
    flex: 1 0 auto; /* Take the remaining space */
  
   
  }

  .scroll-card{
    overflow: auto;
  }
  .card-title{
    position: sticky; top: 0; z-index: 1;
    background-color: #0275d8;
    color:white;
  }

  

  .footer {
    flex: 0 0 auto; /* Take only the space needed */
  }
  
   .active {
     background-color: rgba(6, 120, 249, 0.853); /* Set the background color to blue */
     color: white !important; /* Set the text color to white */
   }
  
  </style>