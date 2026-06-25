document.addEventListener("DOMContentLoaded", function () {
    const track = document.getElementById("simalexTrack");
    const slideTriggers = document.querySelectorAll("[data-slide]");
    const panels = document.querySelectorAll(".simalex-panel");
    const prevBtn = document.getElementById("simalexPrev");
    const nextBtn = document.getElementById("simalexNext");

    if (!track || panels.length === 0) {
        return;
    }

    let currentIndex = 0;
    let isAnimating = false;

    function isHomePage() {
        return document.body.classList.contains("simalex-home");
    }

    function updateActiveState(index) {
        slideTriggers.forEach((trigger) => {
            trigger.classList.remove("active");
        });

        document
            .querySelectorAll(`[data-slide="${index}"]`)
            .forEach((trigger) => {
                trigger.classList.add("active");
            });
    }

    function playPanelAnimation(index) {
        panels.forEach((panel) => {
            panel.classList.remove("is-entering");
        });

        const activePanel = panels[index];

        if (!activePanel) return;

        void activePanel.offsetWidth;

        activePanel.classList.add("is-entering");
    }

    function goToPanel(index) {
        if (!isHomePage()) return;
        if (index < 0 || index >= panels.length) return;
        if (isAnimating) return;

        isAnimating = true;
        currentIndex = index;

        track.style.transform = `translateX(-${index * 100}vw)`;

        updateActiveState(index);

        panels[index].scrollTop = 0;

        setTimeout(() => {
            playPanelAnimation(index);
        }, 120);

        setTimeout(() => {
            isAnimating = false;
        }, 760);
    }

    slideTriggers.forEach((trigger) => {
        trigger.addEventListener("click", function (event) {
            const index = Number(this.dataset.slide);

            if (Number.isNaN(index)) return;
            if (!isHomePage()) return;

            event.preventDefault();
            goToPanel(index);
        });
    });

    if (prevBtn) {
        prevBtn.addEventListener("click", function () {
            goToPanel(currentIndex - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", function () {
            goToPanel(currentIndex + 1);
        });
    }

    document.addEventListener("keydown", function (event) {
        if (!isHomePage()) return;

        if (event.key === "ArrowRight") {
            goToPanel(currentIndex + 1);
        }

        if (event.key === "ArrowLeft") {
            goToPanel(currentIndex - 1);
        }
    });

    updateActiveState(0);
    playPanelAnimation(0);
});