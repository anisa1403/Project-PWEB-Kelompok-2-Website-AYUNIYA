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
    <script src="https://unpkg.com/feather-icons"></script>
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

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            overflow-x: hidden;
            color: var(--ink);
            background: var(--background);
            font-family: "DM Sans", sans-serif;
            line-height: 1.6;
        }
        .snowfall { position: fixed; z-index: 1; inset: 78px 0 0; overflow: hidden; pointer-events: none; }
        .snowflake { position: absolute; top: -18px; width: 7px; height: 7px; border-radius: 50%; background: rgba(255,255,255,.82); box-shadow: 0 0 12px rgba(255,255,255,.9); animation: snowfall linear infinite; }
        @keyframes snowfall { to { transform: translate3d(var(--drift), 105vh, 0) rotate(360deg); } }
        main, footer { position: relative; z-index: 2; }
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
        .hero-grid { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr); align-items: center; gap: 70px; }
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
        .art-orbit { position: absolute; z-index: 0; width: 410px; height: 410px; top: 22px; right: 15px; border: 1px solid rgba(150, 119, 197, .2); border-radius: 50%; }
        .art-orbit::before, .art-orbit::after { position: absolute; border-radius: 50%; background: var(--white); content: ""; }
        .art-orbit::before { width: 11px; height: 11px; top: 70px; left: 9px; background: #e3cce7; }
        .art-orbit::after { width: 8px; height: 8px; right: 24px; bottom: 76px; background: #c9b6e4; }
        .art-card { position: absolute; z-index: 2; top: 62px; right: 47px; width: 330px; padding: 27px; border: 1px solid rgba(255,255,255,.8); border-radius: 28px; background: rgba(255,255,255,.92); box-shadow: var(--shadow); backdrop-filter: blur(12px); transform: rotate(4deg); animation: card-float 5s ease-in-out infinite; }
        @keyframes card-float { 50% { transform: rotate(2deg) translateY(-9px); } }
        .art-card small { color: var(--muted); font-size: 12px; }
        .art-card h3 { margin: 6px 0 22px; font-family: "Playfair Display", serif; font-size: 25px; }
        .mood-row { display: flex; justify-content: space-between; padding: 15px 0; border-top: 1px solid var(--line); font-size: 13px; }
        .mood-row b { color: var(--deep-purple); }
        .art-flower { position: absolute; z-index: 1; right: -3px; bottom: 20px; width: 145px; height: 145px; border-radius: 48% 52% 55% 45%; background: linear-gradient(140deg, #cbb7e9, #886ab7); opacity: .72; transform: rotate(30deg); animation: flower-float 6s ease-in-out infinite; }
        @keyframes flower-float { 50% { transform: rotate(38deg) translateY(-8px); } }
        .art-flower::before, .art-flower::after { position: absolute; width: 85px; height: 130px; border-radius: 50%; background: #ac90d2; content: ""; }
        .art-flower::before { top: -50px; left: 16px; transform: rotate(-35deg); }
        .art-flower::after { right: -40px; bottom: 27px; transform: rotate(60deg); }
        .art-sticker { position: absolute; z-index: 3; bottom: 69px; left: 11px; display: grid; width: 82px; height: 82px; place-items: center; border-radius: 50%; color: var(--deep-purple); background: #fff5d8; box-shadow: 0 14px 30px rgba(112,83,156,.12); font-size: 37px; transform: rotate(-12deg); }

        section { padding: 105px 0; }
        .section-heading { max-width: 580px; margin-bottom: 48px; }
        .section-heading.center { margin-right: auto; margin-left: auto; text-align: center; }
        .section-heading h2 { font-size: clamp(34px, 4vw, 49px); }
        .section-heading p { margin: 16px 0 0; color: var(--muted); }
        .features { background: var(--white); }
        .feature-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .feature-card { padding: 27px 23px; border: 1px solid var(--line); border-radius: 20px; background: var(--white); transition: transform .25s ease, box-shadow .25s ease; }
        .feature-card:hover { box-shadow: var(--shadow); transform: translateY(-7px); }
        .feature-icon { display: grid; width: 49px; height: 49px; margin-bottom: 25px; place-items: center; border-radius: 15px; color: var(--deep-purple); background: var(--lavender); font-size: 22px; }
        .feature-icon svg, .art-sticker svg { width: 23px; height: 23px; stroke-width: 2; }
        .feature-card:nth-child(2) .feature-icon { background: var(--pink); }
        .feature-card:nth-child(3) .feature-icon { background: #e4f0e5; }
        .feature-card:nth-child(4) .feature-icon { background: #fff1d2; }
        .feature-card h3 { margin: 0 0 9px; font-size: 17px; }
        .feature-card p { margin: 0; color: var(--muted); font-size: 13px; }

        .about { background: var(--background); }
        .about-grid { display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); align-items: center; gap: 90px; }
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
        .modal-backdrop { position: fixed; z-index: 50; inset: 0; display: grid; padding: 22px; place-items: center; background: rgba(48, 37, 65, .48); opacity: 0; pointer-events: none; transition: opacity .2s ease; }
        .modal-backdrop.open { opacity: 1; pointer-events: auto; }
        .auth-modal { position: relative; width: min(100%, 440px); max-height: min(680px, calc(100vh - 44px)); overflow: auto; padding: 34px; border: 1px solid rgba(255,255,255,.8); border-radius: 25px; background: var(--white); box-shadow: 0 25px 70px rgba(48,37,65,.25); transform: translateY(12px); transition: transform .2s ease; }
        .modal-backdrop.open .auth-modal { transform: translateY(0); }
        .modal-close { position: absolute; top: 16px; right: 17px; display: grid; width: 34px; height: 34px; place-items: center; border: 0; border-radius: 50%; color: var(--muted); background: var(--lavender); cursor: pointer; font-size: 22px; line-height: 1; }
        .modal-close:hover { color: var(--deep-purple); background: var(--pink); }
        .modal-kicker { margin: 0 0 8px; color: var(--deep-purple); font-size: 12px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
        .auth-modal h2 { margin: 0; font-family: "Playfair Display", serif; font-size: 34px; line-height: 1.15; }
        .modal-description { margin: 10px 0 24px; color: var(--muted); font-size: 14px; }
        .modal-form { display: grid; gap: 14px; }
        .modal-form[hidden] { display: none; }
        .modal-form label { display: grid; gap: 6px; color: var(--ink); font-size: 13px; font-weight: 700; }
        .modal-form input { width: 100%; min-height: 46px; padding: 0 13px; border: 1px solid var(--line); border-radius: 11px; color: var(--ink); background: #fff; font: inherit; outline: none; }
        .modal-form input:focus { border-color: var(--purple); box-shadow: 0 0 0 4px rgba(150,119,197,.14); }
        .modal-form .button { width: 100%; margin-top: 5px; }
        .modal-switch { margin: 20px 0 0; color: var(--muted); text-align: center; font-size: 13px; }
        .modal-switch button { padding: 0; border: 0; color: var(--deep-purple); background: transparent; cursor: pointer; font: inherit; font-weight: 700; }
        body.modal-open { overflow: hidden; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        @media (max-width: 1000px) {
            .hero-grid { gap: 35px; }
            .about-grid { gap: 45px; }
            .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 850px) {
            .nav-links, .nav-actions { display: none; }
            .menu-button { display: block; }
            .nav.open { position: relative; }
            .nav.open .nav-links { position: absolute; top: 68px; right: 0; left: 0; display: flex; flex-direction: column; align-items: stretch; gap: 0; padding: 12px; border: 1px solid var(--line); border-radius: 17px 17px 0 0; background: var(--white); box-shadow: var(--shadow); }
            .nav.open .nav-links a { padding: 11px 13px; }
            .nav.open .nav-actions { position: absolute; top: 224px; right: 0; left: 0; display: flex; justify-content: center; gap: 12px; padding: 12px; border: 1px solid var(--line); border-top: 0; border-radius: 0 0 17px 17px; background: var(--white); box-shadow: var(--shadow); }
            .nav.open .nav-actions .button { flex: 1; }
            .hero-grid, .about-grid { grid-template-columns: 1fr; gap: 30px; }
            .hero { padding-top: 65px; }
            .hero-art { min-height: 390px; }
            .about-visual { min-height: 300px; }
            .cta-inner { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 520px) {
            .container { width: min(100% - 34px, 1120px); }
            .nav { height: 68px; }
            .brand { gap: 7px; font-size: 13px; letter-spacing: .1em; }
            .brand-mark { width: 31px; height: 31px; font-size: 17px; }
            .nav.open .nav-links { top: 58px; }
            .nav.open .nav-actions { top: 214px; }
            .hero { padding: 58px 0 78px; }
            h1 { font-size: clamp(43px, 14vw, 62px); }
            .hero-copy { margin: 20px 0 26px; font-size: 15px; }
            .hero-actions { align-items: stretch; flex-direction: column; }
            .hero-actions .button { width: 100%; }
            .hero-note { align-items: flex-start; }
            .hero-art { width: 100%; min-height: 300px; margin: 8px 0 0; }
            .art-orbit { width: 285px; height: 285px; top: 8px; right: 50%; transform: translateX(50%); }
            .art-card { top: 37px; right: 50%; width: min(300px, calc(100% - 18px)); padding: 22px 18px; transform: translateX(50%) rotate(3deg); }
            .art-card h3 { margin-bottom: 15px; font-size: 21px; }
            .mood-row { padding: 11px 0; font-size: 11px; }
            .art-flower { right: -10px; bottom: 8px; width: 115px; height: 115px; }
            .art-flower::before, .art-flower::after { width: 58px; height: 88px; }
            .art-flower::before { top: -34px; left: 11px; }
            .art-flower::after { right: -28px; bottom: 18px; }
            .art-sticker { bottom: 34px; left: 2px; width: 62px; height: 62px; font-size: 28px; }
            .feature-grid { grid-template-columns: 1fr; }
            section { padding: 76px 0; }
            .section-heading { margin-bottom: 34px; }
            .section-heading h2, .cta h2 { font-size: 34px; }
            .about-panel { inset: 12px 18px 12px 0; padding: 24px; }
            .quote p { font-size: 20px; }
            .cta { padding: 68px 0; }
            .cta .button { width: 100%; }
            .footer-inner { align-items: flex-start; flex-direction: column; }
            .auth-modal { max-height: calc(100vh - 24px); padding: 28px 20px 24px; border-radius: 20px; }
            .auth-modal h2 { padding-right: 28px; font-size: 29px; }
            .modal-backdrop { padding: 12px; }
        }
    </style>
</head>
<body>
    <div class="snowfall" id="snowfall" aria-hidden="true"></div>
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
                <a class="button" href="register.php" data-auth-trigger="register">Get started <span aria-hidden="true">→</span></a>
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
                        <a class="button" href="register.php" data-auth-trigger="register">Start your journey <span aria-hidden="true">→</span></a>
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
                    <div class="art-sticker" aria-hidden="true"><i data-feather="sun"></i></div>
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
                    <article class="feature-card reveal"><div class="feature-icon"><i data-feather="smile"></i></div><h3>Understand your feelings</h3><p>Check in with your mood and get to know what you need today.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><i data-feather="edit-3"></i></div><h3>Express your thoughts</h3><p>Keep your memories and honest thoughts in a private daily journal.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><i data-feather="target"></i></div><h3>Grow at your own pace</h3><p>Turn your intentions into small goals and celebrate every step.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><i data-feather="music"></i></div><h3>Discover your soundtrack</h3><p>Find a personal vibe and music to accompany every kind of day.</p></article>
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
                    <a class="button" href="register.php" data-auth-trigger="register">Create your space <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="cta-inner container">
                <div><h2>Start where you are.<br>Grow from there.</h2><p>Your story is always worth making space for.</p></div>
                <a class="button" href="register.php" data-auth-trigger="register">Get started <span aria-hidden="true">→</span></a>
            </div>
        </section>
    </main>

    <div class="modal-backdrop" id="auth-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-hidden="true">
        <section class="auth-modal">
            <button class="modal-close" id="modal-close" type="button" aria-label="Close dialog">&times;</button>
            <p class="modal-kicker" id="modal-kicker">Begin gently</p>
            <h2 id="modal-title">Create your space</h2>
            <p class="modal-description" id="modal-description">A calm corner for your feelings, memories, and everyday growth.</p>
            <form class="modal-form" id="register-form" action="register.php" method="post">
                <label for="modal-name">Your name</label>
                <input id="modal-name" name="name" type="text" autocomplete="name" placeholder="What should we call you?" required>
                <label for="modal-register-email">Email address</label>
                <input id="modal-register-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                <label for="modal-register-password">Create a password</label>
                <input id="modal-register-password" name="password" type="password" autocomplete="new-password" placeholder="At least 8 characters" minlength="8" required>
                <label for="modal-confirm-password">Confirm password</label>
                <input id="modal-confirm-password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Repeat your password" minlength="8" required>
                <button class="button" type="submit">Create account <span aria-hidden="true">→</span></button>
            </form>
            <form class="modal-form" id="login-form" action="login.php" method="post" hidden>
                <label for="modal-login-email">Email address</label>
                <input id="modal-login-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                <label for="modal-login-password">Password</label>
                <input id="modal-login-password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
                <button class="button" type="submit">Log in <span aria-hidden="true">→</span></button>
            </form>
            <p class="modal-switch" id="modal-switch">Already have an account? <button type="button" data-auth-switch="login">Log in</button></p>
        </section>
    </div>

    <footer>
        <div class="footer-inner container">
            <a class="brand footer-brand" href="#"><span class="brand-mark">A</span> AYUNIYA</a>
            <p class="footer-tagline">Your space, your story, your aura.</p>
            <p class="footer-copy">&copy; <?php echo date('Y'); ?> AYUNIYA. Made with intention.</p>
        </div>
    </footer>

    <script>
        feather.replace();
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
        const authModal = document.getElementById("auth-modal");
        const modalClose = document.getElementById("modal-close");
        const registerForm = document.getElementById("register-form");
        const loginForm = document.getElementById("login-form");
        const modalKicker = document.getElementById("modal-kicker");
        const modalTitle = document.getElementById("modal-title");
        const modalDescription = document.getElementById("modal-description");
        const modalSwitch = document.getElementById("modal-switch");

        const showAuthModal = (mode) => {
            const isLogin = mode === "login";
            registerForm.reset();
            loginForm.reset();
            registerForm.hidden = isLogin;
            loginForm.hidden = !isLogin;
            modalKicker.textContent = isLogin ? "Welcome back" : "Begin gently";
            modalTitle.textContent = isLogin ? "Welcome back" : "Create your space";
            modalDescription.textContent = isLogin ? "Log in to return to your personal universe." : "A calm corner for your feelings, memories, and everyday growth.";
            modalSwitch.innerHTML = isLogin
                ? 'New to AYUNIYA? <button type="button" data-auth-switch="register">Create an account</button>'
                : 'Already have an account? <button type="button" data-auth-switch="login">Log in</button>';
            authModal.classList.add("open");
            authModal.setAttribute("aria-hidden", "false");
            document.body.classList.add("modal-open");
            (isLogin ? document.getElementById("modal-login-email") : document.getElementById("modal-name")).focus();
        };
        const closeAuthModal = () => {
            authModal.classList.remove("open");
            authModal.setAttribute("aria-hidden", "true");
            document.body.classList.remove("modal-open");
        };
        document.querySelectorAll("[data-auth-trigger]").forEach((trigger) => {
            trigger.addEventListener("click", (event) => {
                event.preventDefault();
                showAuthModal(trigger.dataset.authTrigger);
            });
            const snowfall = document.getElementById("snowfall");
            for (let index = 0; index < 28; index += 1) {
                const flake = document.createElement("span");
                flake.className = "snowflake";
                flake.style.left = `${Math.random() * 100}%`;
                flake.style.setProperty("--drift", `${Math.round(Math.random() * 130 - 65)}px`);
                flake.style.animationDuration = `${8 + Math.random() * 10}s`;
                flake.style.animationDelay = `${Math.random() * -15}s`;
                flake.style.opacity = `${0.3 + Math.random() * 0.6}`;
                flake.style.transform = `scale(${0.5 + Math.random() * 1.1})`;
                snowfall.appendChild(flake);
            }
        });
        modalClose.addEventListener("click", closeAuthModal);
        authModal.addEventListener("click", (event) => {
            if (event.target === authModal) closeAuthModal();
            const switchButton = event.target.closest("[data-auth-switch]");
            if (switchButton) showAuthModal(switchButton.dataset.authSwitch);
        });
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && authModal.classList.contains("open")) closeAuthModal();
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