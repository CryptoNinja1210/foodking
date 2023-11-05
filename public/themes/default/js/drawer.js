"use strict";

const drawerDivs = document?.querySelectorAll(".drawer");
const drawerSets = document?.querySelectorAll("[data-drawer]");

drawerSets?.forEach((drawerSet) => {
    drawerSet?.addEventListener("click", function() {
        const targetElm = document?.querySelector(drawerSet?.dataset?.drawer);

        drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
        drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));

        targetElm?.classList?.add("active");
        drawerSet?.classList?.add("active");
        document.body.style.overflowY = "hidden"
        document?.querySelector(".backdrop")?.classList?.add("active");
    })
})

document?.querySelectorAll(".close-drawer")?.forEach((closeBtn) => {
    closeBtn?.addEventListener("click", function() {
        drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
        drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));
        document?.querySelector(".backdrop")?.classList?.remove("active");
        document.body.style.overflowY = "auto"
    })
})
