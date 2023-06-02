import { get, post } from './https';

export const apiLogin = params => { return post('/login', params) };
export const apiGetTenant = () => { return get('/admin/tenant') };
export const apiGetUsers = (params) => { return get('/admin/getUserDetails', params) };
export const apiAddUsers = (params) => { return post('/admin/user/store', params) };
export const apiEditUsers = (id, params) => { return post('/admin/user/store/' + id, params) };
export const apiDeleteUser = (id) => { return get('/admin/user/delete/' + id) };
export const apiLogout = () => { return get('/admin/logout') };
