// DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
    // change color navbar on scroll
    const stickyNavbar = () => {
        const navbar = document.getElementById("stickyNavbar");
        // console.log(window.scrollY);
        if (window.scrollY > 50) {
            navbar.classList.add("shadow");
        } else {
            navbar.classList.add("shadow");
        }
    };
    window.addEventListener("scroll", stickyNavbar);

    // hide unhide button back to top
    const btnBackToTop = () => {
        const btn = document.getElementById("btnBackToTop");
        if (window.scrollY > 180) {
            btn.classList.remove("hidden");
            btn.classList.add("inline-flex");
        } else {
            btn.classList.remove("inline-flex");
            btn.classList.add("hidden");
        }
    };
    window.addEventListener("scroll", btnBackToTop);

    // click button back to top
    window.addEventListener("click", (event) => {
        if (event.target.id === "btnBackToTop") {
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
    });
});
