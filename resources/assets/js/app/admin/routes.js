import { Home, LayoutRouterView, Login } from '../../components/admin'
import { UsersList, UsersWorkWithModel } from '../../components/admin/Users';
import { TypesList, TypesWorkWithModel } from '../../components/admin/Types';
import { ProductsList, ProductsWorkWithModel } from '../../components/admin/Products';
import { FiltersList } from '../../components/admin/Filters';
import { PromotionalCodesList, PromotionalCodesWorkWithModel } from '../../components/admin/PromotionalCodes';
import { UserGroupsList, UserGroupWorkWithModel } from '../../components/admin/UserGroups';
import { FileManagerMain } from "../../components/admin/FileManager";
import { SlidersList, SlidersWorkWithModel } from "../../components/admin/Sliders";
import { TextBlockTitlesList, TextBlockTitlesWorkWithModel } from "../../components/admin/TextBlockTitles";
import { TextBlockDataList, TextBlockDataWorkWithModel } from "../../components/admin/TextBlockData";
import { OrderStatusesList, OrderStatusesWorkWithModel } from "../../components/admin/OrderStatuses";
import { OrderPaymentMethodsList, OrderPaymentMethodsWorkWithModel } from "../../components/admin/OrderPaymentMethods";

export const routes = [
    {
        path: '/admin',
        component: Home,
        name: 'main',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Главная',
            title: 'Главная'
        }
    },
    {
        path: '/admin/orders',
        name: 'orders',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Заказы',
            title: 'Заказы'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'order-statuses-list',
                path: 'order_statuses',
                component: OrderStatusesList,
                meta: {
                    hidden: false,
                    name: 'Список статусов',
                    title: 'Список статусов'
                }
            },
            {
                name: 'order-statuses-create',
                path: 'order_statuses/create',
                component: OrderStatusesWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить статус заказа',
                    title: 'Добавить статус заказа'
                }
            },
            {
                name: 'order-statuses-update',
                path: 'order_statuses/:id',
                component: OrderStatusesWorkWithModel,
                meta: {
                    hidden: true,
                    name: 'Обновление статусу заказа',
                    title: 'Обновление статусу заказа'
                }
            },
            {
                name: 'order-payment-methods-list',
                path: 'order_payment_methods',
                component: OrderPaymentMethodsList,
                meta: {
                    hidden: false,
                    name: 'Список статусов оплаты',
                    title: 'Список статусов оплаты'
                }
            },
            {
                name: 'order-payment-methods-create',
                path: 'order_payment_methods/create',
                component: OrderPaymentMethodsWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить статус оплаты',
                    title: 'Добавить статус оплаты'
                }
            },
            {
                name: 'order-payment-methods-update',
                path: 'order_payment_methods/:id',
                component: OrderPaymentMethodsWorkWithModel,
                meta: {
                    hidden: true,
                    name: 'Обновление статуса оплаты',
                    title: 'Обновление статуса оплаты'
                }
            },
        ]
    },
    {
        path: '/admin/products',
        name: 'products',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Продукция',
            title: 'Список Продукции'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'products-list',
                path: '',
                component: ProductsList,
                meta: {
                    hidden: false,
                    name: 'Просмотреть',
                    title: 'Список Продукции'
                }
            },
            {
                name: 'products-create',
                path: 'create',
                component: ProductsWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить',
                    title: 'Создать Продукт'
                }
            },
            {
                name: 'products-update',
                path: ':id',
                component: ProductsWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных Продукта'
                }
            }
        ]
    },
    {
        path: '/admin/filters',
        name: 'filters',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Фильтра',
            title: 'Список Фильтров'
        },
        component: FiltersList,
    },
    {
        path: '/admin/types',
        name: 'types',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Типы товаров',
            title: 'Список Типов товара'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'types-list',
                path: '',
                component: TypesList,
                meta: {
                    hidden: false,
                    name: 'Просмотреть',
                    title: 'Список Типов товара'
                }
            },
            {
                name: 'types-create',
                path: 'create',
                component: TypesWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить',
                    title: 'Создать Тип товара'
                }
            },
            {
                name: 'types-update',
                path: ':id',
                component: TypesWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных о Типе товаров'
                }
            }
        ]
    },
    {
        path: '/admin/sliders',
        name: 'sliders',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Слайдер',
            title: 'Список Слайдов'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'sliders-list',
                path: '',
                component: SlidersList,
                meta: {
                    hidden: false,
                    name: 'Просмотреть',
                    title: 'Список Слайдов'
                }
            },
            {
                name: 'sliders-create',
                path: 'create',
                component: SlidersWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить',
                    title: 'Создать Слайд'
                }
            },
            {
                name: 'sliders-update',
                path: ':id',
                component: SlidersWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных Слайда'
                }
            }
        ]
    },
    {
        path: '/admin/file-manager',
        name: 'file-manager',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Файловый менеджер',
            title: 'Управление файлами'
        },
        component: FileManagerMain,
    },
    {
        path: '/admin/users',
        name: 'users',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Пользователи',
            title: 'Список Пользователей'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'users-list',
                path: '',
                component: UsersList,
                meta: {
                    hidden: false,
                    name: 'Просмотреть',
                    title: 'Список Пользователей'
                }
            },
            {
                name: 'users-create',
                path: 'create',
                component: UsersWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить',
                    title: 'Создать Пользователя'
                }
            },
            {
                name: 'users-update',
                path: ':id',
                component: UsersWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных о Пользователе'
                }
            }
        ]
    },
    {
        path: '/admin/user_groups',
        name: 'user-groups',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Группы пользователей',
            title: 'Список Групп пользователей'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'user-groups-list',
                path: '',
                component: UserGroupsList,
                meta: {
                    hidden: false,
                    name: 'Просмотреть',
                    title: 'Список Групп пользователей'
                }
            },
            {
                name: 'user-groups-create',
                path: 'create',
                component: UserGroupWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить',
                    title: 'Создание Группы пользователей'
                }
            },
            {
                name: 'user-groups-update',
                path: ':id',
                component: UserGroupWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных о Группе пользователей'
                }
            }
        ]
    },
    {
        path: '/admin/promotional_codes',
        name: 'promotional-codes',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Промокоды',
            title: 'Список Промокодов'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'promotional-codes-list',
                path: '',
                component: PromotionalCodesList,
                meta: {
                    hidden: false,
                    name: 'Просмотреть',
                    title: 'Список Промокодов'
                }
            },
            {
                name: 'promotional-codes-create',
                path: 'create',
                component: PromotionalCodesWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить',
                    title: 'Создать Промокод'
                }
            },
            {
                name: 'promotional-codes-update',
                path: ':id',
                component: PromotionalCodesWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных Промокода'
                }
            }
        ]
    },
    {
        path: '/admin/text_block',
        name: 'text-block',
        meta: {
            requiresAuth: true,
            hidden: false,
            name: 'Текстовые блоки',
            title: 'Текстовые блоки'
        },
        component: LayoutRouterView,
        children: [
            {
                name: 'text-block-titles-list',
                path: 'text_block_titles',
                component: TextBlockTitlesList,
                meta: {
                    hidden: false,
                    name: 'Список заголовков',
                    title: 'Список заголовков'
                }
            },
            {
                name: 'text-block-titles-create',
                path: 'text_block_titles/create',
                component: TextBlockTitlesWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить заголовок',
                    title: 'Создать заголовок'
                }
            },
            {
                name: 'text-block-titles-update',
                path: 'text_block_titles/:id',
                component: TextBlockTitlesWorkWithModel,
                meta: {
                    hidden: true,
                    title: 'Обновление данных Заголовка'
                }
            },
            {
                name: 'text-block-data-list',
                path: 'text_block_data',
                component: TextBlockDataList,
                meta: {
                    hidden: false,
                    name: 'Данные',
                    title: 'Список данных'
                }
            },
            {
                name: 'text-block-data-create',
                path: 'text_block_data/create',
                component: TextBlockDataWorkWithModel,
                meta: {
                    hidden: false,
                    name: 'Добавить данные',
                    title: 'Добавить данные'
                }
            },
            {
                name: 'text-block-data-update',
                path: 'text_block_data/:id',
                component: TextBlockDataWorkWithModel,
                meta: {
                    hidden: true,
                    name: 'Обновление данных',
                    title: 'Обновление данных'
                }
            }
        ]
    },
    {
        title: 'Войти',
        path: '/admin/login',
        component: Login,
        meta: {
            hidden: true,
            name: 'Войти',
            title: ''
        }
    },
];
