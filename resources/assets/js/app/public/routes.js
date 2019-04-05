import { IndexLayout } from "../../components/public/index/";
import { TextPageLayout } from "../../components/public/text_page/";

export const routes = [
    {
        path: '/',
        component: IndexLayout,
        name: 'index',
        meta: {
            requiresAuth: false,
            name: 'Главная',
            title: 'Главная'
        }
    },
    {
        path: '/text_page/:slug',
        component: TextPageLayout,
        name: 'text_page',
        meta: {
            requiresAuth: false,
            name: '',
            title: ''
        }
    }
];