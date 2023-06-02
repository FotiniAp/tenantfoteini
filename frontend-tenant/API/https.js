import axios from 'axios';
axios.defaults.timeout = 50000;
import qs from "qs"
axios.defaults.baseURL = process.env.VUE_APP_VAR1;

axios.interceptors.request.use(
    config => {
        // if (config.url.startsWith("/")) {
        const token = localStorage.getItem("token");
        
        token && (config.headers.Authorization = `Bearer ${token}`);
        config.headers['Content-Type'] = ['application/json , multipart/form-data'];
        // }
        return config;
    },
    error => {
        return Promise.error(error);
    }
);

/**
 @param url @param data @returns {Promise}
 */

export function post(url, data = {}) {
    return new Promise((resolve, reject) => {
        axios.post(url, data, {
            headers: {
                'Content-Type': "application/json",
                'Accept': "application/json",
                'Access-Control-Allow-Origin': '*',
            }
        })
            .then(response => {
                resolve(response.data);
            }, err => {
                reject(err)
            })
    })
}

/**
 * 封装get方法
 * @param url
 * @param data
 * @returns {Promise}
 */


export function get(url, params = {}) {
    return new Promise((resolve, reject) => {
        axios.get(url, {
            params, paramsSerializer(params) { return qs.stringify(params, { arrayFormat: 'repeat' }) }
        }).then(response => {
            resolve(response.data);
        }, err => {
            reject(err)
        })
    })
}

export function baseURLFun() {
    return import.meta.env.VITE_MY_FILE_PATH
}