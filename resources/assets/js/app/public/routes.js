import IndexLayout from "../../components/public/index/Layout";
import TextPageLayout from "../../components/public/text_page/Layout";
import CatalogLayout from "../../components/public/catalog/Layout";
import ProductLayout from "../../components/public/product/Layout";
import InformationLayout from "../../components/public/user/Information";
import FavoriteLayout from "../../components/public/user/Favorite";
import ListOrdersLayout from "../../components/public/user/ListOrders";
import ViewOrderLayout from "../../components/public/user/ViewOrder";
import CartLayout from "../../components/public/cart/Layout";
import CheckoutLayout from "../../components/public/cart/Checkout";
import ConfirmLayout from "../../components/public/cart/Confirm";
import LoginAndRegistrationLayout from "../../components/public/user/LoginAndRegistration";
import NotFound from "../../components/public/text_page/NotFound";

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
    },
    {
        path: '/user',
        component: InformationLayout,
        name: 'user_information',
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/user/favorite',
        component: FavoriteLayout,
        name: 'user_favorite'
    },
    {
        path: '/user/orders',
        component: ListOrdersLayout,
        name: 'list_orders',
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/user/order/:id',
        component: ViewOrderLayout,
        name: 'view_order',
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/cart',
        component: CartLayout,
        name: 'cart',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/checkout',
        component: CheckoutLayout,
        name: 'checkout',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/conform',
        component: ConfirmLayout,
        name: 'confirm',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/login',
        component: LoginAndRegistrationLayout,
        name: 'login',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/registration',
        component: LoginAndRegistrationLayout,
        name: 'registration',
        meta: {
            requiresAuth: false,
        }
    },
    {
        path: '/404',
        name: '404',
        component: NotFound,
    }, {
        path: '*',
        redirect: '/404'
    }
];