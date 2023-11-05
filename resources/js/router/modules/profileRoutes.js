import ProfileEditProfileComponent from "../../components/admin/profile/ProfileEditProfileComponent";
import ProfileChangePasswordComponent from "../../components/admin/profile/ProfileChangePasswordComponent";


export default [
    {
        path: "/admin/profile/edit-profile",
        component: ProfileEditProfileComponent,
        name: "admin.profile.editProfile",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "",
            breadcrumb: "edit_profile",
        },
    },
    {
        path: "/admin/profile/change-password",
        component: ProfileChangePasswordComponent,
        name: "admin.profile.changePassword",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "",
            breadcrumb: "change_password",
        },
    }
];
