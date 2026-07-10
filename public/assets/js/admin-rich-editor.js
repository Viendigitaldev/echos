(function () {
    if (typeof Quill === "undefined") {
        return;
    }

    var toolbarOptions = [
        [{ header: [2, 3, false] }],
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["blockquote", "link"],
        ["clean"],
    ];

    document.querySelectorAll("textarea[data-rich-editor]").forEach(function (textarea) {
        var wrapper = document.createElement("div");
        wrapper.className = "rich-editor";

        var tabs = document.createElement("div");
        tabs.className = "rich-editor-tabs";
        tabs.innerHTML =
            '<button type="button" class="rich-editor-tab active" data-mode="visual">Visual</button>' +
            '<button type="button" class="rich-editor-tab" data-mode="html">HTML</button>';

        var canvas = document.createElement("div");
        canvas.className = "rich-editor-canvas";

        textarea.parentNode.insertBefore(wrapper, textarea);
        wrapper.appendChild(tabs);
        wrapper.appendChild(canvas);
        wrapper.appendChild(textarea);
        textarea.classList.add("rich-editor-source");

        var quill = new Quill(canvas, { theme: "snow", modules: { toolbar: toolbarOptions } });
        quill.root.innerHTML = textarea.value;

        // Keep the underlying textarea in sync on every keystroke so the
        // form submits the current content regardless of which tab is open.
        quill.on("text-change", function () {
            textarea.value = quill.root.innerHTML;
        });

        tabs.querySelectorAll(".rich-editor-tab").forEach(function (tab) {
            tab.addEventListener("click", function () {
                var mode = tab.dataset.mode;
                tabs.querySelectorAll(".rich-editor-tab").forEach(function (t) {
                    t.classList.remove("active");
                });
                tab.classList.add("active");

                if (mode === "html") {
                    textarea.value = quill.root.innerHTML;
                    wrapper.classList.add("mode-html");
                } else {
                    quill.root.innerHTML = textarea.value;
                    wrapper.classList.remove("mode-html");
                }
            });
        });
    });
})();
