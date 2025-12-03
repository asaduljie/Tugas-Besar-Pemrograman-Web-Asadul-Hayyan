document.addEventListener('DOMContentLoaded', () => {
  const nav = document.querySelector('.nav');
  window.addEventListener('scroll', () => {
    if (!nav) return;
    nav.style.boxShadow = window.scrollY > 10 ? '0 8px 20px rgba(11,37,69,0.08)' : 'none';
  });
});
