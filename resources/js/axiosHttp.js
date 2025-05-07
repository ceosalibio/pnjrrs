import axios from "axios";
import { getItem } from "./components/details/crytp";

const instance = axios.create({
    baseURL: 'api/',
    timeout: 150000,
});

// Store ongoing request controllers
const controllers = new Map();

// Request Interceptor
instance.interceptors.request.use(
    (config) => {
        if (!window.navigator.onLine) {
            return Promise.reject(new Error("Check Your Internet Connection"));
        }

        const token = getItem("token");
        if (token) {
            console.log(token)
            config.headers.Authorization = `Bearer ${token}`;
            config.headers.Accept = `application/json`;
        }

        // Cancel previous request if the same URL is already being requested
        if (controllers.has(config.url)) {
            controllers.get(config.url).abort(); // Cancel previous request
        }

        // Create a new AbortController and attach it to the request
        const controller = new AbortController();
        config.signal = controller.signal;
        controllers.set(config.url, controller);

        return config;
    },
    (error) => Promise.reject(error)
);

// Response Interceptor
instance.interceptors.response.use(
    (response) => {
        controllers.delete(response.config.url);
        return response;
    },
    async (error) => {
        const originalRequest = error.config;

        // Handle 401 Unauthorized (Logout User)
        // if (error.response?.status === 401) {
        //     localStorage.clear();
        //     window.location.href = "/";
        // }

        // Handle 429 Too Many Requests with Retry (Exponential Backoff)
        if (error.response?.status === 429) {
            console.warn("Rate limit hit, retrying...");
            const retryAfter = error.response.headers["retry-after"] || 2000; // Default to 2s if header not available
            await new Promise((resolve) => setTimeout(resolve, retryAfter));
            return instance(originalRequest); // Retry request
        }

        controllers.delete(originalRequest.url);
        return Promise.reject(error);
    }
);

export default instance;




// import axios from "axios";
// import Connection from "./Connection";
// import { getItem } from "../service/crypt";

// const instance = axios.create({
//     baseURL: Connection.getBaseUrl(),
// });

// instance.defaults.timeout = 150000;

// instance.interceptors.request.use(
//     (config) => {
//         if (window.navigator.onLine) {
//             const user = getItem( 'user' );

//             if (user.token != null) {
//                 config.headers.Authorization = `Bearer ${user.token}`;
//                 config.headers.Accept=`application/json`;
//             }
//             return config;

//         } else {
//             return Promise.reject(new Error("Check Your Internet Connection"));
//         }
//     },
//     (error) => {
//         return Promise.reject(error);
//     }
// );

// instance.interceptors.response.use(
//     response => response, 
//     error => {
//         if (error.response.status === 401) {
//             localStorage.clear();
//             window.location.href = "/";
//         }
//         return Promise.reject(error);
//     }
// );


// export default instance;
