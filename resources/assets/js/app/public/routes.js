import IndexLayout from "../../components/public/index/Layout";
import TextPageLayout from "../../components/public/text_page/Layout";
import CatalogLayout from "../../components/public/catalog/Layout";
import ProductLayout from "../../components/public/product/Layout";

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
    },
    {
        path: '/product/:slug',
        component: ProductLayout,
        name: 'product',
        meta: {
            requiresAuth: false,
        }
    }
];