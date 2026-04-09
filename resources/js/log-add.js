document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("patientForm");
    const floatingActions = document.getElementById("floatingActions");
    const actionsAnchor = document.getElementById("actionsCardAnchor");

    if (!form || !floatingActions || !actionsAnchor) {
        return;
    }

    const tooltipPlaceholders = {
        snils: "Формат: XXX-XXX-XXX XX",
        polis: "Формат: XXXX XXXXXXXXXXXX",
        phone_agent: "Формат: +7 (XXX) XXX-XX-XX",
        passport: "Формат: XXXX XXXXXX",
    };

    Object.entries(tooltipPlaceholders).forEach(([id, placeholder]) => {
        const input = document.getElementById(id);
        if (input) {
            input.placeholder = placeholder;
        }
    });

    const observer = new IntersectionObserver(
        ([entry]) => {
            floatingActions.classList.toggle("hidden", entry.isIntersecting);
        },
        {
            threshold: 0.05,
        }
    );

    observer.observe(actionsAnchor);
});
