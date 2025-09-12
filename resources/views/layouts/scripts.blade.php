<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    // Show skeleton loading on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Show content after loading
        setTimeout(function() {
            document.querySelectorAll('.skeleton').forEach(function(el) {
                el.classList.remove('skeleton');
            });
            document.querySelectorAll('.skeleton-image').forEach(function(el) {
                el.classList.remove('skeleton-image');
            });
            document.querySelectorAll('.skeleton-text').forEach(function(el) {
                el.classList.remove('skeleton-text');
            });
            document.querySelectorAll('.content-loading').forEach(function(el) {
                el.classList.remove('opacity-0');
                el.classList.add('opacity-100');
            });
        }, 1000);
    });
    
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
    
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Simple hover-intent for desktop dropdown (fallback for keyboard focus)
    const newsDropdownBtn = document.getElementById('newsDropdownBtn');
    const newsDropdownMenu = document.getElementById('newsDropdownMenu');
    if (newsDropdownBtn && newsDropdownMenu) {
        newsDropdownBtn.addEventListener('focus', () => newsDropdownMenu.classList.remove('invisible', 'opacity-0'));
        newsDropdownBtn.addEventListener('blur', () => newsDropdownMenu.classList.add('invisible', 'opacity-0'));
    }

    // Mobile news submenu toggle
    const mobileNewsToggle = document.getElementById('mobile-news-toggle');
    const mobileNewsSubmenu = document.getElementById('mobile-news-submenu');
    if (mobileNewsToggle && mobileNewsSubmenu) {
        mobileNewsToggle.addEventListener('click', () => {
            mobileNewsSubmenu.classList.toggle('hidden');
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Initialize Swiper if elements exist
    if (document.querySelector('.swiper-container')) {
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            }
        });
    }
    
    // Card shine effect on mousemove with 3D tilt
    const cards = document.querySelectorAll('.card-shine');
    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const angleX = (y - centerY) / 25;
            const angleY = (centerX - x) / 25;
            
            // Apply 3D tilt effect
            card.style.transform = `perspective(1000px) rotateX(${-angleX}deg) rotateY(${angleY}deg) scale3d(1.02, 1.02, 1.02)`;
            
            const shineElement = card.querySelector('.card-shine-effect');
            if (shineElement) {
                shineElement.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 80%)`;
            }
        });
        
        card.addEventListener('mouseleave', function() {
            // Reset transform on mouse leave
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
            
            const shineElement = card.querySelector('.card-shine-effect');
            if (shineElement) {
                shineElement.style.background = 'none';
            }
        });
    });
    
    // Parallax effect for hero section
    const parallaxContainer = document.getElementById('parallax-container');
    if (parallaxContainer) {
        const parallaxBg = document.querySelector('.parallax-bg');
        const parallaxElements = document.querySelectorAll('.parallax-element');
        
        window.addEventListener('mousemove', function(e) {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            if (parallaxBg) {
                const speedX = parallaxBg.getAttribute('data-speed') || 0.05;
                const x = mouseX * 100 * speedX;
                const y = mouseY * 100 * speedX;
                parallaxBg.style.transform = `translate(${-x}px, ${-y}px)`;
            }
            
            parallaxElements.forEach(element => {
                const speed = element.getAttribute('data-speed') || 0.1;
                const x = mouseX * 100 * speed;
                const y = mouseY * 100 * speed;
                element.style.transform = `translate(${-x}px, ${-y}px)`;
            });
        });
    }
</script>

@stack('scripts')