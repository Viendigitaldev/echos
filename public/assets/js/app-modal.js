// Step 1: Move modal to be a direct child of body (fixes transform conflicts)
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.querySelector(".app-modal");
    if (modal) {
        document.body.appendChild(modal);
    }
});

// Highlights a "Problem" / "Impact" label that sits alone on its own line
// (matches the structure app content is written in) so it stands out from
// the rest of the description. Escapes the raw text first (via textContent
// round-trip) so nothing else in the description is ever treated as markup.
function renderModalDescription(text) {
    const escaper = document.createElement("div");
    escaper.textContent = text;
    return escaper.innerHTML.replace(/^(Problem|Impact)$/gm, '<strong class="modal-label">$1</strong>');
}

// Step 2: Popup open/close logic
document.addEventListener("click", function (e) {
    const card = e.target.closest(".app-popup-trigger");
    if (card) {
        const modal = document.querySelector(".app-modal");
        document.getElementById("modalTitle").textContent = card.dataset.title || "";
        document.getElementById("modalDescription").innerHTML = renderModalDescription(card.dataset.description || "");
        document.getElementById("modalImage").src = card.dataset.image || "";
        modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    if (e.target.closest(".app-modal-close")) {
        document.querySelector(".app-modal").classList.remove("active");
        document.body.style.overflow = "";
    }

    if (e.target.classList.contains("app-modal-overlay")) {
        document.querySelector(".app-modal").classList.remove("active");
        document.body.style.overflow = "";
    }
});

// Step 3: ESC key also closes the modal
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        document.querySelector(".app-modal").classList.remove("active");
        document.body.style.overflow = "";
    }
});
