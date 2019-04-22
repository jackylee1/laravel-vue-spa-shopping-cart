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
    let user = localStorage.getItem('user');
    let jsonParse = true;

    if (user === null) {
        user = window.$cookies.get('user');
        jsonParse = false;
    }

    if (!user) {
        return null;
    }

    return (jsonParse) ? JSON.parse(user) : user;
}