"use strict";

const modalButtons = document?.querySelectorAll("[data-modal]");
const modalDivs = document?.querySelectorAll(".modal");
const modalClose = document?.querySelectorAll(".modal-close");

modalButtons?.forEach((modalBtn) => {
    modalBtn?.addEventListener("click", function(event) {
        event?.preventDefault();
        const modalTarget = document?.querySelector(modalBtn?.dataset?.modal);

        modalTarget?.classList?.add("active");
        document.body.classList.add("overflow-hidden");
    })
})

modalClose?.forEach(close => close?.addEventListener("click", function(event) {
    event?.target?.closest(".modal")?.classList?.remove("active");
    document.body.classList.remove("overflow-hidden");
}));

