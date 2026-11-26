<style>
  @media (prefers-reduced-motion: no-preference) {
    /* Smooth scroll & crisp GPU-only transitions */
    html { scroll-behavior: smooth; }

    /* Hero slide Ken Burns & content glide */
    @keyframes soroKenBurns {
      from { transform: scale(1); }
      to { transform: scale(1.055); }
    }
    @keyframes soroFadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to { opacity: 1; transform: translateY(0); }
    }
    section#top [data-slide].is-active img {
      animation: soroKenBurns 6.5s ease-out forwards;
      will-change: transform;
    }
    section#top [data-slide].is-active h1 {
      animation: soroFadeUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
    }
    section#top [data-slide].is-active p {
      animation: soroFadeUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) 0.08s both;
    }
    section#top [data-slide].is-active .soro-hero-btns {
      animation: soroFadeUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.14s both;
    }

    /* Scroll reveal */
    .soro-reveal {
      opacity: 0;
      transform: translateY(18px);
      transition: opacity 0.55s cubic-bezier(0.22, 1, 0.36, 1), transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
      will-change: opacity, transform;
    }
    .soro-reveal.is-visible {
      opacity: 1;
      transform: translateY(0);
      will-change: auto;
    }

    /* Tactile card & button micro-interactions */
    .soro-page-wrap article,
    main article {
      transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.28s ease;
    }
    .soro-page-wrap article:hover,
    main article:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(31, 77, 58, 0.1);
    }
    .soro-page-wrap article img,
    main article img {
      transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .soro-page-wrap article:hover img,
    main article:hover img {
      transform: scale(1.04);
    }
    .soro-page-wrap a[style*="border-radius:999px"],
    .soro-page-wrap button[style*="border-radius:999px"],
    main a[style*="border-radius:999px"],
    main button[style*="border-radius:999px"] {
      transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }
    .soro-page-wrap a[style*="border-radius:999px"]:hover,
    .soro-page-wrap button[style*="border-radius:999px"]:hover,
    main a[style*="border-radius:999px"]:hover,
    main button[style*="border-radius:999px"]:hover {
      transform: translateY(-1px);
    }
    .soro-page-wrap a[style*="border-radius:999px"]:active,
    .soro-page-wrap button[style*="border-radius:999px"]:active,
    main a[style*="border-radius:999px"]:active,
    main button[style*="border-radius:999px"]:active {
      transform: scale(0.98);
    }
  }
</style>
<script>
  (function () {
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    function initReveals() {
      var targets = document.querySelectorAll('.soro-page-wrap section:not(#top), main section:not(#top)');
      if (!targets.length) return;

      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.06, rootMargin: '0px 0px -30px 0px' });

      targets.forEach(function (el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.95) {
          return; // Already in initial viewport; keep visible immediately
        }
        el.classList.add('soro-reveal');
        io.observe(el);
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initReveals);
    } else {
      initReveals();
    }
  })();
</script>
