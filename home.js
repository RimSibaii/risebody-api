let slides = document.querySelectorAll('.slide');
let current = 0;

function showSlide(index) {
  slides.forEach(slide => slide.classList.remove('active'));
  slides[index].classList.add('active');
}

function nextSlide() {
  current = (current + 1) % slides.length;
  showSlide(current);
}

// Auto-slide every 5 seconds
setInterval(nextSlide, 5000);

// Manual click-to-next
document.querySelector('.slider').addEventListener('click', nextSlide);

window.addEventListener("load", function () {
  const preloader = document.getElementById("preloader");
  if (preloader) {
    setTimeout(() => {
      preloader.classList.add("hidden");
    }, 1200); // 1.2s delay to show logo & spinner
  }
});



