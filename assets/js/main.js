const body = document.body;
const navToggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');
const themeToggle = document.querySelector('[data-theme-toggle]');
const filterButtons = document.querySelectorAll('[data-filter]');
const projectCards = document.querySelectorAll('[data-project-grid] .project-card');
const yearNode = document.querySelector('[data-year]');
const navLinks = document.querySelectorAll('.site-nav a');
const revealItems = document.querySelectorAll('[data-reveal]');

if (yearNode) {
    yearNode.textContent = new Date().getFullYear();
}

if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });

    navLinks.forEach((link) => {
        link.addEventListener('click', () => {
            nav.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
        });
    });
}

const storedTheme = localStorage.getItem('portfolio-theme');
if (storedTheme === 'light') {
    body.classList.add('light');
}

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        body.classList.toggle('light');
        localStorage.setItem('portfolio-theme', body.classList.contains('light') ? 'light' : 'dark');
    });
}

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const filter = button.dataset.filter;

        filterButtons.forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');

        projectCards.forEach((card) => {
            const matches = filter === 'all' || card.dataset.category === filter;
            card.style.display = matches ? 'grid' : 'none';
        });
    });
});

const sectionObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                sectionObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.16 }
);

revealItems.forEach((item) => sectionObserver.observe(item));

const headingObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            const id = entry.target.getAttribute('id');
            if (!id || !entry.isIntersecting) {
                return;
            }

            navLinks.forEach((link) => {
                const active = link.getAttribute('href') === `#${id}`;
                link.classList.toggle('is-active', active);
            });
        });
    },
    { rootMargin: '-35% 0px -55% 0px' }
);

['about', 'projects', 'skills', 'journey', 'contact'].forEach((id) => {
    const section = document.getElementById(id);
    if (section) {
        headingObserver.observe(section);
    }
});
