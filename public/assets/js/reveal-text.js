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
