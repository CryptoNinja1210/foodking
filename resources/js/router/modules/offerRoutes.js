import OfferComponent from "../../components/admin/offers/OfferComponent";
import OfferListComponent from "../../components/admin/offers/OfferListComponent";
import OfferShowComponent from "../../components/admin/offers/OfferShowComponent";

export default [
    {
        path: '/admin/offers',
        component: OfferComponent,
        name: 'admin.offers',
        redirect: {name: 'admin.offers'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'offers',
            breadcrumb: 'offers'
        },
        children: [
            {
                path: '',
                component: OfferListComponent,
                name: 'admin.offers',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'offers',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: OfferShowComponent,
                name: "admin.offer.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "offers",
                    breadcrumb: "view",
                },
            },
        ]
    }
]
