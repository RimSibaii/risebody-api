// Logo animation re-trigger (optional)
window.addEventListener("load", () => {
    const logo = document.querySelector(".logo");
    logo.classList.remove("logo-animation");
    void logo.offsetWidth; // Force reflow
    logo.classList.add("logo-animation");
  });
  
  window.addEventListener("load", function () {
    const preloader = document.getElementById("preloader");
    if (preloader) {
      setTimeout(() => {
        preloader.classList.add("hidden");
      }, 1200); // 1.2s delay to show logo & spinner
    }
  });
  