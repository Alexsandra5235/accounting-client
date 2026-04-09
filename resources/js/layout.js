function getMetaContent(name) {
    return document.querySelector(`meta[name="${name}"]`)?.getAttribute("content") || "";
}

const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modal-title");
const modalBody = document.getElementById("modal-body");
const closeBtn = modal?.querySelector(".modal-close");

function getScrollbarWidth() {
    return window.innerWidth - document.documentElement.clientWidth;
}

function openModal(title, bodyHTML) {
    if (!modal || !modalTitle || !modalBody) {
        return;
    }

    modalTitle.textContent = title;
    modalBody.innerHTML = bodyHTML;
    modal.classList.add("active");
    modal.setAttribute("aria-hidden", "false");

    const scrollBarWidth = getScrollbarWidth();
    if (scrollBarWidth > 0) {
        document.body.style.paddingRight = `${scrollBarWidth}px`;
    }
    document.body.style.overflow = "hidden";
}

function closeModal() {
    if (!modal) {
        return;
    }

    modal.classList.remove("active");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    document.body.style.paddingRight = "";
}

if (modal && closeBtn) {
    document.querySelectorAll(".open-modal-btn").forEach((button) => {
        button.addEventListener("click", () => {
            if (button.hasAttribute("data-changes")) {
                const changesJson = button.getAttribute("data-changes");
                let tableHTML = '<div class="overflow-x-auto"><table class="w-full border-collapse border border-gray-200"><thead><tr>' +
                    '<th class="border border-gray-300 px-4 py-2 text-left bg-gray-50">Поле</th>' +
                    '<th class="border border-gray-300 px-4 py-2 text-left bg-gray-50">До</th>' +
                    '<th class="border border-gray-300 px-4 py-2 text-left bg-gray-50">После</th>' +
                    "</tr></thead><tbody>";

                try {
                    const changes = JSON.parse(changesJson);
                    Object.entries(changes).forEach(([field, values]) => {
                        tableHTML += `<tr>
                            <td class="border border-gray-300 px-4 py-2 font-medium">${field}</td>
                            <td class="border border-gray-300 px-4 py-2">${values.before ?? ""}</td>
                            <td class="border border-gray-300 px-4 py-2">${values.after ?? ""}</td>
                        </tr>`;
                    });
                } catch (error) {
                    tableHTML += '<tr><td colspan="3" class="text-red-500 px-4 py-2">Ошибка парсинга данных изменений</td></tr>';
                }
                tableHTML += "</tbody></table></div>";

                openModal("Изменения записи", tableHTML);
                return;
            }

            const userId = button.getAttribute("data-user-id");
            const name = button.getAttribute("data-name") || "—";
            const email = button.getAttribute("data-email") || "—";
            const editUrl = button.getAttribute("data-edit-url");
            const title = `Информация о сотруднике #${userId}`;

            const bodyHTML = `
                <div class="space-y-3">
                    <p><strong class="text-gray-700">ФИО:</strong> ${name}</p>
                    <p><strong class="text-gray-700">Email:</strong> ${email}</p>
                    <div class="pt-3">
                        <a href="${editUrl}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Перейти к редактированию профиля
                        </a>
                    </div>
                </div>
            `;

            openModal(title, bodyHTML);
        });
    });

    closeBtn.addEventListener("click", closeModal);
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && modal.classList.contains("active")) {
            closeModal();
        }
    });
}

window.mkbAutocomplete = function mkbAutocomplete(fetchUrl, nameInput, nameValue, initial) {
    return {
        query: initial,
        suggestions: [],
        highlightedIndex: -1,
        fetchSuggestions() {
            if (this.query.length < 2) {
                this.suggestions = [];
                return;
            }

            const csrfToken = getMetaContent("csrf-token");
            fetch(fetchUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    [nameInput]: this.query,
                }),
            })
                .then((res) => res.json())
                .then((data) => {
                    this.suggestions = data;
                    this.highlightedIndex = -1;
                })
                .catch(() => {
                    this.suggestions = [];
                });
        },
        highlightNext() {
            if (this.highlightedIndex < this.suggestions.length - 1) {
                this.highlightedIndex += 1;
            }
        },
        highlightPrev() {
            if (this.highlightedIndex > 0) {
                this.highlightedIndex -= 1;
            }
        },
        selectHighlighted() {
            if (this.highlightedIndex >= 0) {
                this.selectSuggestion(this.highlightedIndex);
            }
        },
        selectSuggestion(index) {
            this.query = `${this.suggestions[index].code}`;
            const hiddenInput = document.getElementById(nameValue);
            if (hiddenInput) {
                hiddenInput.value = this.suggestions[index].value;
            }
            this.suggestions = [];
        },
    };
};

window.confirmDeletion = function confirmDeletion(patientName) {
    return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"? Это действие невозможно будет отменить.`);
};

if (window.toastr) {
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "300",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "slideDown",
        hideMethod: "slideUp",
        tapToDismiss: false,
        rtl: false,
    };

    document.addEventListener("DOMContentLoaded", () => {
        const successToast = getMetaContent("toast-success");
        const warningToast = getMetaContent("toast-warn");

        if (successToast) {
            toastr.success(successToast, "Успешно", {
                preventDuplicates: true,
            });
        }

        if (warningToast) {
            toastr.warning(warningToast, "Внимание", {
                timeOut: 6000,
                preventDuplicates: true,
            });
        }
    });
}

function adaptNavigationOverflow() {
    const navMenu = document.querySelector(".js-overflow-menu");
    const moreMenu = document.querySelector(".js-more-menu");
    const moreDropdown = document.querySelector(".js-more-dropdown");
    const moreToggle = document.querySelector(".js-more-toggle");

    if (!navMenu || !moreMenu || !moreDropdown || !moreToggle) {
        return;
    }

    if (window.innerWidth <= 1024) {
        moreDropdown.innerHTML = "";
        moreMenu.classList.add("hidden");
        return;
    }

    const navItems = Array.from(navMenu.querySelectorAll(".js-nav-item"));
    navItems.forEach((item) => {
        navMenu.insertBefore(item, moreMenu);
    });
    moreDropdown.innerHTML = "";

    moreMenu.classList.remove("hidden");
    moreMenu.style.visibility = "hidden";
    moreToggle.classList.remove("active");

    const movedItems = [];
    while (navMenu.scrollWidth > navMenu.clientWidth && navItems.length - movedItems.length > 1) {
        const itemToMove = navItems[navItems.length - movedItems.length - 1];
        movedItems.unshift(itemToMove);
        moreDropdown.prepend(itemToMove);
    }

    if (movedItems.length === 0) {
        moreMenu.classList.add("hidden");
    } else {
        const hasActiveMovedItem = movedItems.some((item) => item.classList.contains("active"));
        if (hasActiveMovedItem) {
            moreToggle.classList.add("active");
        }
    }

    moreMenu.style.visibility = "";
}

document.addEventListener("DOMContentLoaded", () => {
    adaptNavigationOverflow();
});

window.addEventListener("resize", () => {
    adaptNavigationOverflow();
});
