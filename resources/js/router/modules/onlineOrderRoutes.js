import OnlineOrderComponent from "../../components/admin/onlineOrders/OnlineOrderComponent";
import OnlineOrderListComponent from "../../components/admin/onlineOrders/OnlineOrderListComponent";
import OnlineOrderShowComponent from "../../components/admin/onlineOrders/OnlineOrderShowComponent";

export default [
    {
        path: '/admin/online-orders',
        component: OnlineOrderComponent,
        name: 'admin.order',
        redirect: {name: 'admin.order.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'online-orders',
            breadcrumb: 'online_orders'
        },
        children: [
            {
                path: '',
                component: OnlineOrderListComponent,
                name: 'admin.order.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'online-orders',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: OnlineOrderShowComponent,
                name: "admin.order.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "online-orders",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
