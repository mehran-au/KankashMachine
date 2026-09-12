(function () {
  const toggle = document.querySelector("[data-nav-toggle]");
  const nav = document.querySelector("[data-nav]");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      nav.classList.toggle("is-open");
    });
  }

  const slider = document.querySelector("[data-slider]");
  if (!slider) return;
  const slides = Array.from(slider.querySelectorAll(".slide"));
  const dots = Array.from(slider.querySelectorAll("[data-dot]"));
  if (slides.length < 2) return;
  let i = 0;
  function show(n) {
    i = (n + slides.length) % slides.length;
    slides.forEach((s, idx) => s.classList.toggle("is-on", idx === i));
    dots.forEach((d, idx) => d.classList.toggle("is-on", idx === i));
  }
  dots.forEach((d) => d.addEventListener("click", () => show(Number(d.getAttribute("data-dot")))));
  setInterval(() => show(i + 1), 6000);
})();
