document.addEventListener("DOMContentLoaded", () => {
    const tabButtons = document.querySelectorAll(".blog-tab");
    const cards = document.querySelectorAll(".blog-post-card");
    const searchInput = document.getElementById("blog-search");
    const sortSelect = document.getElementById("blog-sort");
    const grid = document.getElementById("blog-grid");
    const emptyState = document.getElementById("blog-empty-state");

    // Mobile category bottom sheet — same filtering logic as the desktop
    // tabs below, just a different picker UI (distyl.ai/blog's mobile pattern).
    const sheetTrigger = document.getElementById("blog-category-trigger");
    const sheetOverlay = document.getElementById("blog-category-sheet-overlay");
    const sheet = document.getElementById("blog-category-sheet");
    const sheetClose = document.getElementById("blog-category-sheet-close");
    const sheetItems = document.querySelectorAll(".blog-category-sheet-item");

    // GSAP ScrollSmoother (main.js) applies a CSS transform to #smooth-content
    // to drive the smooth-scroll effect, which makes it the containing block
    // for any position:fixed descendant — so the sheet/overlay would render
    // at some offset down the (very tall) transformed content instead of
    // pinned to the actual viewport. Moving them to be direct children of
    // <body> (a sibling of #smooth-wrapper, like the sticky header already
    // is) sidesteps that entirely.
    if (sheetOverlay && sheet) {
        document.body.appendChild(sheetOverlay);
        document.body.appendChild(sheet);
    }

    if (!tabButtons.length || !cards.length) {
        return;
    }

    let activeFilters = ["all"];

    function syncTabButtons() {
        tabButtons.forEach((btn) => {
            btn.classList.toggle("active", activeFilters.includes(btn.dataset.filter));
        });
        sheetItems.forEach((item) => {
            item.classList.toggle("active", activeFilters.includes(item.dataset.filter));
        });
    }

    function openSheet() {
        if (sheetOverlay && sheet) {
            sheetOverlay.classList.add("open");
            sheet.classList.add("open");
        }
    }

    function closeSheet() {
        if (sheetOverlay && sheet) {
            sheetOverlay.classList.remove("open");
            sheet.classList.remove("open");
        }
    }

    function applyFilters() {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : "";
        let visibleCount = 0;

        cards.forEach((card) => {
            const category = card.dataset.category || "";
            const title = card.dataset.title || "";

            const matchesCategory = activeFilters.includes("all") || activeFilters.includes(category);
            const matchesSearch = query === "" || title.includes(query);

            if (matchesCategory && matchesSearch) {
                card.style.display = "";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    function toggleFilter(filter) {
        if (filter === "all") {
            activeFilters = ["all"];
        } else {
            activeFilters = activeFilters.filter((item) => item !== "all");

            if (activeFilters.includes(filter)) {
                activeFilters = activeFilters.filter((item) => item !== filter);
            } else {
                activeFilters.push(filter);
            }

            if (activeFilters.length === 0) {
                activeFilters = ["all"];
            }
        }

        syncTabButtons();
        applyFilters();
    }

    function setSingleFilter(filter) {
        activeFilters = [filter];
        syncTabButtons();
        applyFilters();
    }

    tabButtons.forEach((button) => {
        button.addEventListener("click", () => toggleFilter(button.dataset.filter));
    });

    if (sheetTrigger) {
        sheetTrigger.addEventListener("click", openSheet);
    }
    if (sheetClose) {
        sheetClose.addEventListener("click", closeSheet);
    }
    if (sheetOverlay) {
        sheetOverlay.addEventListener("click", closeSheet);
    }
    sheetItems.forEach((item) => {
        item.addEventListener("click", () => {
            setSingleFilter(item.dataset.filter);
            closeSheet();
        });
    });

    // Clicking a post card's category tag jumps straight to that single filter.
    document.querySelectorAll(".blog-card-category").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            setSingleFilter(link.dataset.filter);
        });
    });

    if (searchInput) {
        searchInput.addEventListener("input", applyFilters);
    }

    if (sortSelect && grid) {
        sortSelect.addEventListener("change", () => {
            const sortedCards = Array.from(cards).sort((a, b) => {
                if (sortSelect.value === "az") {
                    return a.dataset.title.localeCompare(b.dataset.title);
                }
                const dateA = new Date(a.dataset.date).getTime();
                const dateB = new Date(b.dataset.date).getTime();
                return sortSelect.value === "oldest" ? dateA - dateB : dateB - dateA;
            });

            sortedCards.forEach((card) => grid.appendChild(card));
        });
    }
});
