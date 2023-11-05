import VueSimpleAlert from "vue3-simple-alert";
import store from "../store";
import statusEnum from "../enums/modules/statusEnum";
import orderStatusEnum from "../enums/modules/orderStatusEnum";
import askEnum from "../enums/modules/askEnum";
import taxTypeEnum from "../enums/modules/taxTypeEnum";
import currencyPositionEnum from "../enums/modules/currencyPositionEnum";

export default {
    sideDrawerShow: function (id = 'sideDrawer') {
        const drawerDivs = document?.querySelectorAll(".drawer");
        const drawerSets = document?.querySelectorAll("[data-drawer]");
        drawerSets?.forEach((drawerSet) => {
            const targetElm = document?.querySelector(drawerSet?.dataset?.drawer);
            drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
            drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));
            targetElm?.classList?.add("active");
            drawerSet?.classList?.add("active");
            document.body.style.overflowY = "hidden";
            document?.querySelector(".backdrop")?.classList?.add("active");
        });
    },
    sideDrawerHide: function (id = 'sideDrawer') {
        const drawerDivs = document?.querySelectorAll(".drawer");
        const drawerSets = document?.querySelectorAll("[data-drawer]");
        document?.querySelectorAll("#sidebar")?.forEach((closeBtn) => {
            drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
            drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));
            document?.querySelector(".backdrop")?.classList?.remove("active");
            document.body.style.overflowY = "auto"
        });
    },

    modalShow: function (id = '.modal') {
        let modalButton = document?.querySelectorAll("[data-modal]");
        modalButton?.forEach((modalBtn) => {
            const modalTarget = document?.querySelector(id);
            modalTarget?.classList?.add("active");
            document.body.style.overflowY = "hidden";
        });
    },

    modalHide: function (id = ".modal") {
        let modalDivs = document?.querySelectorAll(id);
        document.body.style.overflowY = "auto";
        modalDivs?.forEach((modalDiv) => modalDiv?.classList?.remove("active"));
    },

    phoneNumber: function (e) {
        let char = String.fromCharCode(e.keyCode);
        if (/^[+]?[0-9]*$/.test(char)) return true;
        else e.preventDefault();
    },

    onlyNumber: function (e) {
        let res = (e.charCode !== 8 && e.charCode === 0 || (e.charCode >= 48 && e.charCode <= 57));
        if (res)
            return true;
        else
            e.preventDefault();
    },

    floatNumber: function (e) {
        let char = String.fromCharCode(e.keyCode);
        if (/^[.]?[0-9]*$/.test(char)) return true;
        else e.preventDefault();
    },

    currencyFormat(amount, decimal, currency, position) {
        if (position === currencyPositionEnum.LEFT) {
            return currency + parseFloat(amount).toFixed(decimal);
        } else {
            return parseFloat(amount).toFixed(decimal) + currency;
        }
    },

    destroyConfirmation: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to recover the deleted record!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Delete it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    acceptOrder: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to cancel the order!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Accept it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    cancelOrder: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to accept the order!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Cancel it!",
                cancelButtonText: "No, Cancel",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },

    distance: function (lat1, lng1, lat2, lng2) {
        let radiationLat1 = Math.PI * lat1 / 180
        let radiationLat2 = Math.PI * lat2 / 180
        let theta = lng1 - lng2;
        let radiationTheta = Math.PI * theta / 180
        let distance = Math.sin(radiationLat1) * Math.sin(radiationLat2) + Math.cos(radiationLat1) * Math.cos(radiationLat2) * Math.cos(radiationTheta);
        distance = Math.acos(distance)
        distance = distance * 180 / Math.PI
        distance = distance * 60 * 1.1515
        distance = distance * 1.609344
        return distance;
    },

    recursiveRouter: function (routes, permission) {
        let i, j;
        for (i = 0; i < routes.length; i++) {
            for (j = 0; j < permission.length; j++) {
                if (typeof routes[i].meta !== "undefined" && routes[i].meta) {
                    if (typeof routes[i].meta.permissionUrl !== "undefined" && routes[i].meta.permissionUrl) {
                        if (routes[i].meta.permissionUrl === permission[j].url) {
                            routes[i].meta.access = permission[j].access;
                            routes[i].meta.title = permission[j].title;
                        }

                        if (typeof routes[i].children !== "undefined" && routes[i].children) {
                            this.recursiveRouter(routes[i].children, permission);
                        }
                    }
                }
            }
        }
    },

    textShortener: function (text, number = 30) {
        if (text) {
            if (!(text.length < number)) {
                return text.substring(0, number) + "..";
            }
        }
        return text;
    },
    statusClass: function (status) {
        if (status === statusEnum.ACTIVE) {
            return "db-table-badge text-green-600 bg-green-100";
        } else {
            return "db-table-badge text-red-600 bg-red-100";
        }
    },

    orderStatusClass: function (status) {
         if(status == orderStatusEnum.ACCEPT || status == orderStatusEnum.PROCESSING){
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#2AC769] bg-[#CBFFE0]";
        }
        else if(status == orderStatusEnum.PENDING){
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#F6A609] bg-[#FFEEC6]";
        }
        else if(status == orderStatusEnum.OUT_FOR_DELIVERY){
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#008BBA] bg-[#BDEFFF]";
        }
        else if(status == orderStatusEnum.DELIVERED){
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-primary bg-[#FFD7E7]";
        }
        else {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#FB4E4E] bg-[#FFDADA]";
        }
    },

    askClass: function (ask) {
        if (ask === askEnum.YES) {
            return "db-table-badge text-green-600 bg-green-100";
        } else {
            return "db-table-badge text-red-600 bg-red-100";
        }
    },

    taxTypeClass: function (type) {
        if (type === taxTypeEnum.FIXED) {
            return "db-table-badge text-blue-500 bg-blue-100";
        } else {
            return "db-table-badge text-orange-500 bg-orange-100";
        }
    },

    requestHandler: function (requests) {
        let i = 1;
        let what = "?";
        let response = "";

        for (let request in requests) {
            if (requests[request] !== "" && requests[request] !== null) {
                if (i !== 1) {
                    response += "&";
                }
                response += request + "=" + requests[request];
            }
            i++;
        }

        if (response) {
            response = what + response;
        }

        return response;
    },

    responsiveLoad: function() {
        let mainHeader = document?.querySelector(".db-header");
        let subHeader = document?.querySelector(".sub-header");
        let mainHeight = mainHeader?.scrollHeight;

        if (subHeader) {
            subHeader.style.top = `${mainHeight}px`;
        }
    },


    permissionChecker: function (permissionName) {
        let i, permissions = store.getters.authPermission;
        for (i = 0; i < permissions.length; i++) {
            if (typeof permissions[i].name !== "undefined" && permissions[i].name) {
                if (permissions[i].name === permissionName) {
                    return permissions[i].access;
                }
            }
        }
    },

    formDataShow: function (formData) {
        for (let pair of formData.entries()) {
            console.log(pair[0] + " : " + pair[1]);
        }
    },
};
