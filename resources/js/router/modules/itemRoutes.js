import ItemComponent from "../../components/admin/items/ItemComponent";
import ItemListComponent from "../../components/admin/items/ItemListComponent";
import ItemShowComponent from "../../components/admin/items/ItemShowComponent";

export default [
    {
        path: '/admin/items',
        component: ItemComponent,
        name: 'admin.items',
        redirect: {name: 'admin.items'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'items',
            breadcrumb: 'items'
        },
        children: [
            {
                path: '',
                component: ItemListComponent,
                name: 'admin.items',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'items',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: ItemShowComponent,
                name: "admin.item.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "items",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
