import PosComponent from "../../components/admin/pos/PosComponent";

export default [
    {
        path: "/admin/pos",
        component: PosComponent,
        name: "admin.pos",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "pos",
        },
    },
];
