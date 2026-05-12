// Scroll reveal for waste items
const wasteItems = document.querySelectorAll('.waste-item');
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.style.opacity = 1;
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, { threshold: 0.2 });

wasteItems.forEach(item => {
  item.style.opacity = 0;
  item.style.transform = 'translateY(30px)';
  item.style.transition = '0.6s ease';
  observer.observe(item);
});


