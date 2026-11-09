<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AYUNIYA is your personal space to understand your feelings, capture your moments, achieve your goals, and discover your aura.">
    <title>AYUNIYA — Your Space, Your Story, Your Aura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple: #9677c5;
            --deep-purple: #70539c;
            --ink: #39304b;
            --muted: #777181;
            --lavender: #eae0fa;
            --background: #fbf9ff;
            --pink: #f8e6f1;
            --white: #fff;
            --line: #eee8f8;
            --shadow: 0 22px 60px rgba(112, 83, 156, .13);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--ink);
            background: var(--background);
            font-family: "DM Sans", sans-serif;
            line-height: 1.6;
        }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1120px, calc(100% - 48px)); margin: 0 auto; }

        .site-header {
            position: sticky;
            z-index: 20;
            top: 0;
            border-bottom: 1px solid rgba(238, 232, 248, .7);
            background: rgba(251, 249, 255, .86);
            backdrop-filter: blur(16px);
        }
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 78px;
        }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-weight: 700; letter-spacing: .12em; }
        .brand-mark {
            display: grid;
            width: 35px;
            height: 35px;
            place-items: center;
            border-radius: 12px;
            color: var(--white);
            background: var(--purple);
            font-family: "Playfair Display", serif;
            font-size: 19px;
            letter-spacing: 0;
        }
        .nav-links { display: flex; align-items: center; gap: 34px; color: var(--muted); font-size: 14px; }
        .nav-links a { transition: color .2s ease; }
        .nav-links a:hover { color: var(--deep-purple); }
        .nav-actions { display: flex; align-items: center; gap: 22px; font-size: 14px; }
        .login-link { color: var(--deep-purple); font-weight: 600; }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 48px;
            padding: 0 22px;
            border: 0;
            border-radius: 14px;
            color: var(--white);
            background: var(--deep-purple);
            box-shadow: 0 9px 20px rgba(112, 83, 156, .18);
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .button:hover { background: #62468e; box-shadow: 0 13px 25px rgba(112, 83, 156, .25); transform: translateY(-2px); }
        .button-light { color: var(--deep-purple); background: var(--white); box-shadow: 0 9px 24px rgba(112, 83, 156, .09); }
        .button-light:hover { color: var(--white); background: var(--deep-purple); }
        .menu-button { display: none; border: 0; color: var(--deep-purple); background: transparent; font-size: 25px; cursor: pointer; }

        .hero { overflow: hidden; padding: 92px 0 110px; background: radial-gradient(circle at 79% 31%, #f5eafa 0, transparent 30%), linear-gradient(135deg, #fbf9ff 0%, #f5effd 100%); }
        .hero-grid { display: grid; grid-template-columns: 1.05fr .95fr; align-items: center; gap: 70px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; margin: 0 0 20px; color: var(--deep-purple); font-size: 12px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; }
        .eyebrow::before { width: 30px; height: 1px; background: var(--purple); content: ""; }
        h1, h2 { margin: 0; font-family: "Playfair Display", serif; line-height: 1.12; }
        h1 { max-width: 610px; font-size: clamp(48px, 6vw, 78px); font-weight: 700; letter-spacing: -.035em; }
        h1 span { color: var(--purple); }
        .hero-copy { max-width: 510px; margin: 24px 0 31px; color: var(--muted); font-size: 17px; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 13px; }
        .hero-note { display: flex; align-items: center; gap: 11px; margin-top: 30px; color: var(--muted); font-size: 12px; }
        .avatar-stack { display: flex; }
        .avatar { display: grid; width: 28px; height: 28px; margin-right: -7px; place-items: center; border: 2px solid var(--background); border-radius: 50%; color: var(--deep-purple); background: var(--pink); font-size: 11px; font-weight: 700; }
        .avatar:nth-child(2) { color: #966c54; background: #f7dfd1; }
        .avatar:nth-child(3) { color: #607d67; background: #dcece0; }

        .hero-art { position: relative; min-height: 450px; }
        .art-orbit { position: absolute; width: 410px; height: 410px; top: 22px; right: 15px; border: 1px solid rgba(150, 119, 197, .2); border-radius: 50%; }
        .art-orbit::before, .art-orbit::after { position: absolute; border-radius: 50%; background: var(--white); content: ""; }
        .art-orbit::before { width: 11px; height: 11px; top: 70px; left: 9px; background: #e3cce7; }
        .art-orbit::after { width: 8px; height: 8px; right: 24px; bottom: 76px; background: #c9b6e4; }
        .art-card { position: absolute; top: 62px; right: 47px; width: 330px; padding: 27px; border: 1px solid rgba(255,255,255,.8); border-radius: 28px; background: rgba(255,255,255,.8); box-shadow: var(--shadow); backdrop-filter: blur(12px); transform: rotate(4deg); }
        .art-card small { color: var(--muted); font-size: 12px; }
        .art-card h3 { margin: 6px 0 22px; font-family: "Playfair Display", serif; font-size: 25px; }
        .mood-row { display: flex; justify-content: space-between; padding: 15px 0; border-top: 1px solid var(--line); font-size: 13px; }
        .mood-row b { color: var(--deep-purple); }
        .art-flower { position: absolute; right: 0; bottom: 34px; width: 170px; height: 170px; border-radius: 48% 52% 55% 45%; background: linear-gradient(140deg, #cbb7e9, #886ab7); opacity: .8; transform: rotate(30deg); }
        .art-flower::before, .art-flower::after { position: absolute; width: 85px; height: 130px; border-radius: 50%; background: #ac90d2; content: ""; }
        .art-flower::before { top: -50px; left: 16px; transform: rotate(-35deg); }
        .art-flower::after { right: -40px; bottom: 27px; transform: rotate(60deg); }
        .art-sticker { position: absolute; bottom: 69px; left: 11px; display: grid; width: 82px; height: 82px; place-items: center; border-radius: 50%; color: var(--deep-purple); background: #fff5d8; box-shadow: 0 14px 30px rgba(112,83,156,.12); font-size: 37px; transform: rotate(-12deg); }

        section { padding: 105px 0; }
        .section-heading { max-width: 580px; margin-bottom: 48px; }
        .section-heading.center { margin-right: auto; margin-left: auto; text-align: center; }
        .section-heading h2 { font-size: clamp(34px, 4vw, 49px); }
        .section-heading p { margin: 16px 0 0; color: var(--muted); }
        .features { background: var(--white); }
        .feature-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .feature-card { padding: 27px 23px; border: 1px solid var(--line); border-radius: 20px; background: var(--white); transition: transform .25s ease, box-shadow .25s ease; }
        .feature-card:hover { box-shadow: var(--shadow); transform: translateY(-7px); }
        .feature-icon { display: grid; width: 49px; height: 49px; margin-bottom: 25px; place-items: center; border-radius: 15px; color: var(--deep-purple); background: var(--lavender); font-size: 22px; }
        .feature-card:nth-child(2) .feature-icon { background: var(--pink); }
        .feature-card:nth-child(3) .feature-icon { background: #e4f0e5; }
        .feature-card:nth-child(4) .feature-icon { background: #fff1d2; }
        .feature-card h3 { margin: 0 0 9px; font-size: 17px; }
        .feature-card p { margin: 0; color: var(--muted); font-size: 13px; }

        .about { background: var(--background); }
        .about-grid { display: grid; grid-template-columns: .9fr 1.1fr; align-items: center; gap: 90px; }
        .about-visual { position: relative; min-height: 360px; }
        .about-panel { position: absolute; inset: 20px 30px 20px 0; padding: 30px; border-radius: 24px; background: var(--lavender); }
        .about-panel::after { position: absolute; right: -19px; bottom: -19px; width: 120px; height: 120px; border-radius: 50%; background: var(--pink); content: ""; }
        .quote { position: relative; z-index: 1; display: flex; height: 100%; flex-direction: column; justify-content: flex-end; }
        .quote-mark { color: var(--purple); font-family: serif; font-size: 80px; line-height: .8; }
        .quote p { max-width: 270px; margin: 18px 0 0; color: var(--deep-purple); font-family: "Playfair Display", serif; font-size: 23px; }
        .about-copy p { margin: 20px 0 27px; color: var(--muted); }
        .about-list { display: grid; gap: 12px; margin: 0 0 30px; padding: 0; color: var(--ink); list-style: none; font-size: 14px; }
        .about-list li::before { display: inline-grid; width: 21px; height: 21px; margin-right: 10px; place-items: center; border-radius: 50%; color: var(--white); background: var(--purple); content: "✓"; font-size: 12px; }

        .cta { padding: 82px 0; background: var(--deep-purple); color: var(--white); }
        .cta-inner { display: flex; align-items: center; justify-content: space-between; gap: 30px; }
        .cta h2 { max-width: 620px; font-size: clamp(33px, 4vw, 50px); }
        .cta p { max-width: 480px; margin: 13px 0 0; color: #e5dcf2; }
        .cta .button { color: var(--deep-purple); background: var(--white); }

        footer { padding: 32px 0; background: #302541; color: #d6cce5; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .footer-brand { color: var(--white); }
        .footer-copy { margin: 0; font-size: 12px; }
        .footer-tagline { margin: 0; font-size: 12px; }
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 850px) {
            .nav-links, .nav-actions { display: none; }
            .menu-button { display: block; }
            .nav.open { position: relative; }
            .nav.open .nav-links { position: absolute; top: 68px; right: 0; left: 0; display: flex; flex-direction: column; align-items: stretch; gap: 0; padding: 12px; border: 1px solid var(--line); border-radius: 17px; background: var(--white); box-shadow: var(--shadow); }
            .nav.open .nav-links a { padding: 11px 13px; }
            .nav.open .nav-actions { position: absolute; top: 228px; right: 0; left: 0; display: flex; justify-content: center; padding: 0 12px; }
            .hero-grid, .about-grid { grid-template-columns: 1fr; gap: 30px; }
            .hero { padding-top: 65px; }
            .hero-art { min-height: 390px; }
            .feature-grid { grid-template-columns: repeat(2, 1fr); }
            .about-visual { min-height: 300px; }
            .cta-inner { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 520px) {
            .container { width: min(100% - 34px, 1120px); }
            .hero-art { min-height: 330px; transform: scale(.85); transform-origin: top center; margin-bottom: -48px; }
            .art-card { right: 22px; }
            .feature-grid { grid-template-columns: 1fr; }
            section { padding: 76px 0; }
            .footer-inner { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <nav class="nav container" id="main-nav">
            <a class="brand" href="#" aria-label="AYUNIYA home"><span class="brand-mark">A</span> AYUNIYA</a>
            <div class="nav-links" id="nav-links">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#features">Features</a>
            </div>
            <div class="nav-actions">
                <a class="login-link" href="login.php">Log in</a>
                <a class="button" href="register.php">Get started <span aria-hidden="true">→</span></a>
            </div>
            <button class="menu-button" id="menu-button" type="button" aria-label="Open navigation menu" aria-expanded="false">☰</button>
        </nav>
    </header>

    <main>
        <section class="hero" id="home">
            <div class="hero-grid container">
                <div class="reveal">
                    <p class="eyebrow">A softer way to grow</p>
                    <h1>Your space,<br><span>your story.</span></h1>
                    <p class="hero-copy">A little space to understand your feelings, capture your moments, achieve your goals, and discover your aura.</p>
                    <div class="hero-actions">
                        <a class="button" href="register.php">Start your journey <span aria-hidden="true">→</span></a>
                        <a class="button button-light" href="#features">Explore features</a>
                    </div>
                    <div class="hero-note">
                        <div class="avatar-stack" aria-hidden="true"><span class="avatar">A</span><span class="avatar">H</span><span class="avatar">N</span></div>
                        <span>A calm corner for your everyday moments.</span>
                    </div>
                </div>
                <div class="hero-art reveal" aria-label="Decorative personal wellness dashboard illustration">
                    <div class="art-orbit"></div>
                    <div class="art-card">
                        <small>Today, take a moment to ask</small>
                        <h3>How are you feeling?</h3>
                        <div class="mood-row"><span>My current mood</span><b>Calm ☺</b></div>
                        <div class="mood-row"><span>Today's intention</span><b>Be present</b></div>
                        <div class="mood-row"><span>Little reminder</span><b>Go gently ✦</b></div>
                    </div>
                    <div class="art-flower" aria-hidden="true"></div>
                    <div class="art-sticker" aria-hidden="true">☼</div>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="container">
                <div class="section-heading center reveal">
                    <p class="eyebrow">Everything in one space</p>
                    <h2>Small rituals, meaningful change.</h2>
                    <p>Tools that help you pause, reflect, and move forward at your own pace.</p>
                </div>
                <div class="feature-grid">
                    <article class="feature-card reveal"><div class="feature-icon">☻</div><h3>Understand your feelings</h3><p>Check in with your mood and get to know what you need today.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon">✎</div><h3>Express your thoughts</h3><p>Keep your memories and honest thoughts in a private daily journal.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon">✦</div><h3>Grow at your own pace</h3><p>Turn your intentions into small goals and celebrate every step.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon">♫</div><h3>Discover your soundtrack</h3><p>Find a personal vibe and music to accompany every kind of day.</p></article>
                </div>
            </div>
        </section>

        <section class="about" id="about">
            <div class="about-grid container">
                <div class="about-visual reveal">
                    <div class="about-panel"><div class="quote"><span class="quote-mark">“</span><p>You deserve a space that feels like you.</p></div></div>
                </div>
                <div class="about-copy reveal">
                    <p class="eyebrow">Meet your personal universe</p>
                    <h2>Make room for the moments that matter.</h2>
                    <p>AYUNIYA is a gentle digital space for your inner world. Whether you are checking in with yourself, writing down a thought, or taking a step toward a goal, you can keep it all close and simple.</p>
                    <ul class="about-list">
                        <li>A private space that is truly yours</li>
                        <li>Simple tools for everyday reflection</li>
                        <li>A little encouragement, whenever you need it</li>
                    </ul>
                    <a class="button" href="register.php">Create your space <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="cta-inner container">
                <div><h2>Start where you are.<br>Grow from there.</h2><p>Your story is always worth making space for.</p></div>
                <a class="button" href="register.php">Get started <span aria-hidden="true">→</span></a>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-inner container">
            <a class="brand footer-brand" href="#"><span class="brand-mark">A</span> AYUNIYA</a>
            <p class="footer-tagline">Your space, your story, your aura.</p>
            <p class="footer-copy">&copy; <?php echo date('Y'); ?> AYUNIYA. Made with intention.</p>
        </div>
    </footer>

    <script>
        const nav = document.getElementById("main-nav");
        const menuButton = document.getElementById("menu-button");
        menuButton.addEventListener("click", () => {
            const isOpen = nav.classList.toggle("open");
            menuButton.setAttribute("aria-expanded", String(isOpen));
            menuButton.setAttribute("aria-label", isOpen ? "Close navigation menu" : "Open navigation menu");
        });
        document.querySelectorAll(".nav-links a").forEach((link) => {
            link.addEventListener("click", () => {
                nav.classList.remove("open");
                menuButton.setAttribute("aria-expanded", "false");
            });
        });
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll(".reveal").forEach((element) => observer.observe(element));
    </script>
</body>
</html>