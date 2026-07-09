<footer class="footer">

    <div class="footer-top">

        <div class="line"></div>

        <div class="brand">
            <div class="certifications">
                <div class="cert-circle">SOC2</div>
                
            </div>
        </div>

        <div class="line"></div>

    </div>

    <div class="footer-content">

        <!-- Left Links -->
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <!-- Center Social -->
        <div class="footer-center">

            <div class="socials">
                <a href="#">𝕏</a>
                <a href="#">in</a>
               
            </div>

        </div>

        <!-- Right Links -->
        <div class="footer-links right">
            <a href="#">About   </a>
            <a href="#">Applications</a>
            <a href="#">Perspectives</a>
        </div>

    </div>

    <!-- BIG BRAND -->
    <div class="footer-logo">
        echos
    </div>

    <div class="footer-bottom">
       © 2026 <a href="#">Echos</a>. All rights reserved. Design & Devloped By <a href="#">Vien Digital</a>.
    </div>

</footer>

<!-- APP MODAL -->
<div class="app-modal">

    <div class="app-modal-overlay"></div>

    <div class="app-modal-content">

        <button class="app-modal-close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <img id="modalImage" src="" alt="">

        <div class="app-modal-body">

            <h2 id="modalTitle"></h2>

            <p id="modalDescription"></p>

        </div>

    </div>

</div>
        <!--<< All JS Plugins >>-->
        <script src="assets/js/jquery-3.7.1.min.js"></script>
        <!--<< Viewport Js >>-->
        <script src="assets/js/viewport.jquery.js"></script>
        <!--<< Bootstrap Js >>-->
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!--<< Gsap Min Js >>-->
        <script src="assets/js/gsap.min.js"></script>
        <!--<< ScrollTrigger Min Js >>-->
        <script src="assets/js/ScrollTrigger.min.js"></script>
        <!--<< ScrollSmoother Min Js >>-->
        <script src="assets/js/ScrollSmoother.min.js"></script>
        <!--<< ScrollToPlugin Min Js >>-->
        <script src="assets/js/ScrollToPlugin.min.js"></script>
        <!--<< SplitText Min Js >>-->
        <script src="assets/js/SplitText.min.js"></script>
        <!--<< TextPlugin Min Js >>-->
        <script src="assets/js/TextPlugin.js"></script>
        <!--<< Chroma Min Js >>-->
        <script src="assets/js/chroma.min.js"></script>
        <!--<< Three Js >>-->
        <script src="assets/js/three.js"></script>
        <!--<< Webgl Min Js >>-->
        <script src="assets/js/webgl.js"></script>
        <!--<< nice-selec Js >>-->
        <script src="assets/js/jquery.nice-select.min.js"></script>
        <!--<< Waypoints Js >>-->
        <script src="assets/js/jquery.waypoints.js"></script>
        <!--<< Counterup Js >>-->
        <script src="assets/js/jquery.counterup.min.js"></script>
        <!--<< Swiper Slider Js >>-->
        <script src="assets/js/swiper-bundle.min.js"></script>
        <!--<< MeanMenu Js >>-->
        <script src="assets/js/jquery.meanmenu.min.js"></script>
        <!--<< Parallaxie Js >>-->
        <script src="assets/js/parallaxie.js"></script>
        <!--<< Magnific Popup Js >>-->
        <script src="assets/js/jquery.magnific-popup.min.js"></script>
        <!--<< Wow Animation Js >>-->
        <script src="assets/js/wow.min.js"></script>
        <!--<< Main.js >>-->
        <script src="assets/js/main.js"></script>
        <script type="module" src="assets/js/distortion-img.js"></script>


<!-- NOTE: GSAP + ScrollTrigger are already loaded above (assets/js/*) and wired to
     ScrollSmoother in main.js. Do NOT reload them here — a second copy overwrites the
     global instances and breaks every scroll animation, including the Perspectives pin. -->
<script src="https://unpkg.com/split-type"></script>

<script>
gsap.registerPlugin(ScrollTrigger);

document.addEventListener("DOMContentLoaded", () => {

    const split = new SplitType(".reveal-text", {
        types: "words"
    });

    gsap.set(".reveal-text .word", {
        opacity: 0.15
    });

    gsap.to(".reveal-text .word", {
        opacity: 1,
        stagger: 0.30,
        ease: "none",

        scrollTrigger: {
            trigger: ".reveal-text",
            start: "top 100%",
            end: "bottom 60%",
            scrub: 2
        }
    });

});
</script>


<script>
// Step 1: Modal ko body ka direct child banao (transform conflict fix)
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.querySelector(".app-modal");
    if (modal) {
        document.body.appendChild(modal);
    }
});

// Step 2: Popup open/close logic
document.addEventListener("click", function (e) {
    const card = e.target.closest(".app-popup-trigger");
    if (card) {
        const modal = document.querySelector(".app-modal");
        document.getElementById("modalTitle").textContent = card.dataset.title || "";
        document.getElementById("modalDescription").textContent = card.dataset.description || "";
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

// Step 3: ESC key se bhi modal band ho
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        document.querySelector(".app-modal").classList.remove("active");
        document.body.style.overflow = "";
    }
});
</script>


<script>
const tabButtons = document.querySelectorAll('.tab-btn');
const cards = document.querySelectorAll('.production-card');

let activeFilters = ['all'];

tabButtons.forEach(button => {

    button.addEventListener('click', function(){

        const filter = this.dataset.filter;

        if(filter === 'all'){

            activeFilters = ['all'];

            tabButtons.forEach(btn =>
                btn.classList.remove('active')
            );

            this.classList.add('active');
        }
        else{

            document.querySelector('[data-filter="all"]')
                .classList.remove('active');

            activeFilters =
                activeFilters.filter(item => item !== 'all');

            this.classList.toggle('active');

            if(this.classList.contains('active')){

                activeFilters.push(filter);

            }else{

                activeFilters =
                    activeFilters.filter(item => item !== filter);
            }

            if(activeFilters.length === 0){

                activeFilters = ['all'];

                document.querySelector('[data-filter="all"]')
                    .classList.add('active');
            }
        }

        cards.forEach(card => {

            if(activeFilters.includes('all')){

                card.style.display = 'block';

            }else{

                const category = card.dataset.category;

                if(activeFilters.includes(category)){

                    card.style.display = 'block';

                }else{

                    card.style.display = 'none';
                }
            }

        });

    });

});
</script>

<script>
$(document).ready(function(){

    $(".mobile-menu-main .nav-item > .nav-link").click(function(e){

        const submenu = $(this).next(".sub-menu");

        if(submenu.length){

            e.preventDefault();

            $(".mobile-menu-main .sub-menu").not(submenu).slideUp(300);
            $(".mobile-menu-main .nav-item").not($(this).parent()).removeClass("active");

            submenu.stop(true,true).slideToggle(300);
            $(this).parent().toggleClass("active");

        }

    });

});
</script>

    </body>

</html>