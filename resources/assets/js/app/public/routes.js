import { IndexLayout } from "../../components/public/index/";
import { TextPageLayout } from "../../components/public/text_page/";
import { CatalogLayout } from "../../components/public/catalog/";

export const routes = [
    {
        path: '/',
        component: IndexLayout,
        name: 'index',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/text_page/:slug',
        component: TextPageLayout,
        name: 'text_page',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/catalog',
        component: CatalogLayout,
        name: 'catalog',
        meta: {
            requiresAuth: false,
        }
    }
];