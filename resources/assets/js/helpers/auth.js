import * as ApiAuth from '../app/api/Auth'
import { setAuthorization } from './general';

export function login(credentials) {
    return new Promise((res, rej) => {
        ApiAuth.login(credentials).then((response) => {
            setAuthorization(response.data.access_token);
            res(response.data);
        }).catch((err) => {
            rej("Wrong email or password");
        })
    })
}

export function getLocalUser() {
    const userStr = localStorage.getItem('user');

    if (!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}