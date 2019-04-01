import { IndexLayout } from "../../components/public/index/";

export const routes = [
    {
        path: '*',
        component: IndexLayout,
        name: 'index',
        meta: {
            requiresAuth: false,
            name: 'Главная',
            title: 'Главная'
        }
    }
];