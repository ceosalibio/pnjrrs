import Vue from 'vue'
import VueRouter from 'vue-router'
import {getItem} from '../js/components/details/crytp'

Vue.use(VueRouter)
// import Home from './components/misc/uploading.units'
// import Hello from './components/Hello'
// import Test from './components/Test'
import adduser from './components/admin/user/adduser'
import Login from './components/pages/Login'
import HomeView from './components/pages/Home'
import personnelAdmin from './components/admin/personnel/personnel'
import trainingAdmin from './components/admin/training/training'
import equipmentAdmin from './components/admin/equipment/equipment'
import facilityAdmin from './components/admin/facility/facility'
import inventory from './components/admin/inventory/inventory'
import checkReport from './components/admin/report/report'
import personnel from './components/pages/personnel/table'
import training from './components/pages/training/table'
import equipment from './components/pages/equipment/table'
import maintenance from './components/pages/maintenance/table'
import facility from './components/pages/facility/table'
import report from './components/pages/report/table'

Vue.use(VueRouter)

  const  routes = [
        {
            path: '/adduser',
            name: 'adduser',
            component : adduser
        },
        {
            path: '/login',
            name: 'login',
            component : Login
        },
        {
            path: '/home',
            name: 'home',
            component: HomeView
        },
        {
            path: '/personnel',
            name: 'personnel',
            component: personnel
        },
        {
            path: '/training',
            name: 'training',
            component: training
        },
        {
            path: '/equipment',
            name: 'equipment',
            component: equipment
        },
        {
            path: '/maintenance',
            name: 'maintenance',
            component: maintenance
        },
        {
            path: '/facility',
            name: 'facility',
            component: facility
        },
        {
            path: '/personnelAdmin',
            name: 'personnelAdmin',
            component: personnelAdmin
        },
        {
            path: '/trainingAdmin',
            name: 'trainingAdmin',
            component: trainingAdmin
        },
        {
            path: '/equipmentAdmin',
            name: 'equipmentAdmin',
            component: equipmentAdmin
        },
        {
            path: '/facilityAdmin',
            name: 'facilityAdmin',
            component: facilityAdmin
        },
        {
            path: '/inventory',
            name: 'inventory',
            component: inventory
        },
        {
            path: '/checkReport',
            name: 'checkReport',
            component: checkReport
        },
        {
            path: '/report',
            name: 'report',
            component: report
        },
    ]
    const router = new VueRouter({
        mode: 'history',
        base: '/pnjrrs2/',
        routes
    })

    router.beforeEach((to, from,next)=>{

        const user = getItem('user')
        if(to.path !== '/login'){
            if(user && user.name){
                if(to.path ==='/home'){
                    next();
                }else if(to.path  ==='/personnel'){
                    next();
                }else if(to.path ==='/training'){
                    next();
                }else if(to.path ==='/equipment'){
                    next();
                }else if(to.path ==='/maintenance'){
                    next();
                }else if(to.path ==='/facility'){
                    next();
                }else if(to.path ==='/personnelAdmin'){
                    next();
                }else if(to.path ==='/trainingAdmin'){
                    next();
                }else if(to.path ==='/equipmentAdmin'){
                    next();
                }else if(to.path ==='/facilityAdmin'){
                    next();
                }else if(to.path ==='/inventory'){
                    next();
                }else if(to.path ==='/checkReport'){
                    next();
                }else if(to.path ==='/addUser'){
                    next();
                }else if(to.path ==='/report'){
                    next();
                }else if(to.path ==='/master'){
                    if(user.username == 'ceo'){
                        next();
                    }else{
                        next('/home'); 
                    }
                    
                }else{
                    next('/home');
                }
            }else{
                next('/login');
            }
    
        }else{
            if(user && user.username){
                next('home')
            }else{
                next();
            }
        }
    })

    export default router
