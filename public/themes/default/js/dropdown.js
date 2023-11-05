"use strict";

const dropGroup = document?.querySelectorAll(".dropdown-group");
const dropList = document?.querySelectorAll(".dropdown-list");
const dropBtn = document?.querySelectorAll(".dropdown-btn");

dropBtn?.forEach((btnItem) => {
    btnItem?.addEventListener("click", () => {
        const currentGroup = btnItem?.closest(".dropdown-group");
        const currentBtn = currentGroup?.querySelector(".dropdown-btn");
        const currentList = currentGroup?.querySelector(".dropdown-list");
        const currentActive = currentGroup?.className.includes("active");

        if (currentActive) {
            currentGroup?.classList?.remove("active");
            currentList?.classList?.remove("active");
            currentBtn?.classList?.remove("active");
        }
        else {
            dropGroup?.forEach((groupItem) => {
                if (groupItem?.className?.includes("active")) {
                    groupItem?.classList?.remove("active");
                    groupItem?.querySelector(".dropdown-btn")?.classList?.remove("active");
                    groupItem?.querySelector(".dropdown-list")?.classList?.remove("active");
                }
            })
            currentGroup?.classList?.add("active");
            currentList?.classList?.add("active");
            currentBtn?.classList?.add("active");
        }
    })
})

document?.addEventListener("click", (event) => {
    dropGroup?.forEach((item) => {
        if (!item?.contains(event?.target)) {
            item?.classList?.remove("active");
            item?.querySelector(".dropdown-btn")?.classList?.remove("active");
            item?.querySelector(".dropdown-list")?.classList?.remove("active");
        }
    })
})