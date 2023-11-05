"use strict";

const tabButtons = document?.querySelectorAll(".db-tab-btn");
const tabDivs = document?.querySelectorAll(".db-tab-div");

tabButtons?.forEach((btnItem) => {
    btnItem?.addEventListener("click", function() {
        const tabTarget = document?.querySelector(btnItem?.dataset?.tab);

        tabButtons?.forEach(tabBtn => tabBtn?.classList?.remove("active"));
        tabDivs?.forEach(tabDiv => tabDiv?.classList?.remove("active"));

        tabTarget?.classList?.add("active");
        btnItem?.classList?.add("active");
    })
})

const subTabButtons = document?.querySelectorAll(".db-tab-sub-btn");
const subTabDivs = document?.querySelectorAll(".db-tab-sub-div");

subTabButtons?.forEach((btnItem) => {
    btnItem?.addEventListener("click", function() {
        const tabTarget = document?.querySelector(btnItem?.dataset?.tab);

        subTabButtons?.forEach(tabBtn => tabBtn?.classList?.remove("active"));
        subTabDivs?.forEach(tabDiv => tabDiv?.classList?.remove("active"));

        tabTarget?.classList?.add("active");
        btnItem?.classList?.add("active");
    })
})

