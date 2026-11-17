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

        .hero-visuals { display: grid; gap: 22px; }
        .hero-art { position: relative; }
        .hero-illustration {
            position: relative;
            overflow: hidden;
            min-height: 380px;
            border: 8px solid rgba(255,255,255,.72);
            border-radius: 34px;
            background:
                radial-gradient(circle at 78% 24%, rgba(255, 224, 238, .55), transparent 18%),
                linear-gradient(180deg, rgba(49, 32, 78, .08), rgba(49, 32, 78, .18)),
                url('assets/images/moonlit-room.svg') center/cover no-repeat;
            box-shadow: 0 28px 56px rgba(112, 83, 156, .18);
        }
        .hero-illustration::before {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.02), rgba(72, 43, 92, .14));
            content: "";
        }
        .hero-illustration::after {
            position: absolute;
            top: 18px;
            left: 20px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(255,255,255,.92), rgba(247, 215, 236, .78) 42%, rgba(247, 215, 236, 0) 72%);
            opacity: .75;
            filter: blur(1px);
            content: "";
        }
        .hero-mood-card {
            position: relative;
            padding: 28px 30px;
            border: 1px solid rgba(255,255,255,.9);
            border-radius: 28px;
            background: rgba(255,255,255,.94);
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }
        .hero-mood-card small { color: var(--muted); font-size: 12px; }
        .hero-mood-card h3 { margin: 6px 0 22px; font-family: "Playfair Display", serif; font-size: 25px; }
        .mood-row { display: flex; justify-content: space-between; gap: 18px; padding: 15px 0; border-top: 1px solid var(--line); font-size: 13px; }
        .mood-row b { color: var(--deep-purple); text-align: right; }
        .art-sticker {
            position: absolute;
            z-index: 2;
            top: 18px;
            right: 18px;
            display: grid;
            width: 76px;
            height: 76px;
            place-items: center;
            border-radius: 50%;
            color: var(--deep-purple);
            background: #fff5d8;
            box-shadow: 0 14px 30px rgba(112,83,156,.12);
            font-size: 34px;
        }

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
        .modal-password-field { position: relative; display: block; }.modal-password-field input { padding-right: 48px; }.modal-password-toggle { position: absolute; right: 8px; bottom: 7px; display: grid; width: 32px; height: 32px; place-items: center; border: 0; border-radius: 8px; color: var(--muted); background: transparent; cursor: pointer; }.modal-password-toggle:hover { color: var(--deep-purple); background: var(--lavender); }.modal-password-toggle svg { width: 17px; height: 17px; }
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
            .hero-visuals { gap: 18px; }
            .hero-illustration { min-height: 360px; }
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
            .hero-visuals { gap: 16px; }
            .hero-art { width: 100%; margin: 8px 0 0; }
            .hero-illustration { min-height: 280px; border-width: 6px; border-radius: 28px; }
            .hero-mood-card { padding: 22px 18px; border-radius: 22px; }
            .hero-mood-card h3 { margin-bottom: 15px; font-size: 21px; }
            .mood-row { padding: 11px 0; font-size: 11px; }
            .art-sticker { top: 12px; right: 12px; width: 62px; height: 62px; font-size: 28px; }
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
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/landing-storybook.css">

    <style id="ayuniya-hero-overrides">
      /* AYUNIYA landing: celestial artwork and accessible controls. */
      html { scroll-padding-top: 2rem; }
      .site-header { position:absolute; top:0; left:0; right:0; z-index:30; background:transparent!important; border:0!important; box-shadow:none!important; backdrop-filter:none!important; }
      .site-header .nav { height:88px; width:min(1380px, calc(100% - 64px)); }
      .site-header .brand { color:#fff1f9!important; font-size:26px; letter-spacing:.06em; text-shadow:0 2px 12px #38235466; }
      .site-header .brand-mark { width:47px; height:47px; border-radius:15px; background:linear-gradient(145deg,#c696e2,#9a62c7); box-shadow:0 8px 24px #39255433; }
      .site-header .nav-links { gap:50px; font-size:15px; color:#f9eafa; }
      .site-header .nav-links a:hover,.site-header .login-link:hover { color:#ffe2e9!important; }
      .site-header .nav-actions { gap:31px; }
      .site-header .login-link { color:#fff1f9!important; font-size:15px; font-weight:600; }
      .site-header .nav-actions .button { min-height:47px; border-radius:17px; padding:0 25px; background:#965cc8; box-shadow:0 12px 23px #49266140; }
      .site-header .menu-button { color:#fff0fb; }
      .ayuniya-hero { position:relative; isolation:isolate; display:flex; align-items:center; justify-content:center; min-height:100vh; min-height:100svh; overflow:hidden; padding:125px 24px 95px; background:linear-gradient(180deg,#4b327a 0%,#71509c 35%,#a76ab0 65%,#eea5cf 100%); text-align:center; }
      .ayuniya-sky { position:absolute; z-index:-1; inset:0; width:100%; height:100%; pointer-events:none; }
      .ayuniya-hero-content { position:relative; z-index:1; width:min(1100px,100%); padding:0; transform:translateY(6vh); }
      .ayuniya-hero-content h1 { max-width:none; margin:0; color:#ffe6f6!important; font-family:'Playfair Display',Georgia,serif; font-size:clamp(72px,10.6vw,151px)!important; line-height:1; font-weight:600; letter-spacing:.055em!important; text-shadow:0 10px 42px #56316833; }
      .ayuniya-tagline { margin:23px auto 0; max-width:1000px; color:#fff1fc; font-family:'Poppins','DM Sans',sans-serif; font-size:clamp(11px,1.43vw,20px); letter-spacing:.17em; line-height:1.65; font-weight:400; text-shadow:0 2px 15px #4c2f704a; }
      .ayuniya-actions { display:flex; flex-wrap:wrap; gap:25px; justify-content:center; margin-top:51px; }
      .ayuniya-action { display:inline-flex; justify-content:center; align-items:center; min-width:256px; min-height:66px; padding:15px 30px; border-radius:999px; font-family:'Poppins','DM Sans',sans-serif; font-size:18px; font-weight:700; text-align:center; text-decoration:none; transition:background .2s,transform .2s,box-shadow .2s; box-shadow:0 14px 26px #512b7040; }
      .ayuniya-action:hover { transform:translateY(-3px); box-shadow:0 17px 31px #42275f66; }
      .ayuniya-action:focus-visible, .site-header a:focus-visible, .site-header button:focus-visible { outline:3px solid #fff; outline-offset:4px; }
      .ayuniya-action-primary { color:#fff!important; background:linear-gradient(125deg,#8041b4,#68369c); }
      .ayuniya-action-primary:hover { background:#643196; }
      .ayuniya-action-secondary { color:#79459e!important; background:#fff1fb; }
      .ayuniya-action-secondary:hover { background:#fff; }
      @media(max-width:850px) {
        .site-header .nav { width:calc(100% - 36px); height:70px; }
        .site-header .brand { font-size:20px; }
        .site-header .brand-mark { width:37px; height:37px; font-size:23px; }
        .site-header .nav.open .nav-links { top:64px; }
        .site-header .nav.open .nav-actions { top:222px; }
        .site-header .nav.open .nav-links a, .site-header .nav.open .login-link { color:#684987!important; }
        .ayuniya-hero { min-height:100svh; padding:130px 20px 80px; }
        .ayuniya-hero-content { transform:translateY(3vh); }
        .ayuniya-hero-content h1 { font-size:clamp(55px,12vw,102px)!important; letter-spacing:.035em!important; }
        .ayuniya-tagline { max-width:650px; font-size:clamp(11px,2vw,17px); }
        .ayuniya-action { min-width:218px; min-height:59px; font-size:16px; }
      }
      @media(max-width:540px) {
        .site-header .nav { height:67px; }
        .site-header .brand { font-size:17px; }
        .site-header .nav.open .nav-links { top:61px; }
        .site-header .nav.open .nav-actions { top:217px; }
        .ayuniya-hero { padding:125px 20px 65px; min-height:100svh; }
        .ayuniya-hero-content { transform:translateY(3vh); }
        .ayuniya-hero-content h1 { font-size:clamp(47px,13.5vw,78px)!important; letter-spacing:.025em!important; }
        .ayuniya-tagline { max-width:350px; margin-top:17px; font-size:clamp(10px,2.9vw,12px); letter-spacing:.075em; }
        .ayuniya-actions { gap:13px; margin-top:34px; }
        .ayuniya-action { width:min(100%,310px); min-width:0; min-height:54px; font-size:15px; }
      }
      @media(prefers-reduced-motion:reduce) { .ayuniya-action { transition:none; } }
    </style>
</head>
<body>
    <header class="site-header">
        <nav class="nav container" id="main-nav">
            <a class="brand" href="#" aria-label="AYUNIYA home"><span class="brand-mark">☾</span> AYUNIYA</a>
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
        <section class="ayuniya-hero" id="home" aria-labelledby="hero-title">
            <!-- Ilustrasi langit, terpisah dari judul dan tombol agar tetap responsif -->
            <svg class="ayuniya-sky" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice">
 <defs>
    <linearGradient id="sky-grad" x1="0" y1="0" x2="0" y2="1">
      <stop stop-color="#4b327a"/><stop offset=".33" stop-color="#71509c"/><stop offset=".64" stop-color="#aa6bad"/><stop offset=".82" stop-color="#d98bbf"/><stop offset="1" stop-color="#edadd0"/>
    </linearGradient>
    <radialGradient id="moon-halo"><stop stop-color="#ffdfc9" stop-opacity=".45"/><stop offset=".6" stop-color="#ffcde8" stop-opacity=".11"/><stop offset="1" stop-color="#ffcae3" stop-opacity="0"/></radialGradient>
    <radialGradient id="haze"><stop stop-color="#ffd1da" stop-opacity=".48"/><stop offset="1" stop-color="#ffb9cf" stop-opacity="0"/></radialGradient>
    <linearGradient id="cloud-left" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#c981c1"/><stop offset="1" stop-color="#f0a5d0"/></linearGradient>
    <linearGradient id="cloud-right" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#ce8ac8"/><stop offset="1" stop-color="#f6a3cf"/></linearGradient>
    <linearGradient id="front-hills" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#9b64a9"/><stop offset="1" stop-color="#5c3c8f"/></linearGradient>
    <filter id="smallGlow" x="-100%" y="-100%" width="300%" height="300%"><feGaussianBlur in="SourceGraphic" stdDeviation=".65" /></filter>
    <mask id="crescent-mask"><rect width="1600" height="900" fill="white"/><circle cx="836" cy="175" r="55" fill="black"/></mask>
  </defs>
  <rect width="1600" height="900" fill="url(#sky-grad)"/>
  <ellipse cx="800" cy="770" rx="970" ry="250" fill="url(#haze)" />
  <circle cx="807" cy="216" r="146" fill="url(#moon-halo)"/>
  <g><circle cx="376" cy="486" r="1.0" fill="#ffe3fa" opacity="0.43" /><circle cx="253" cy="107" r="0.8" fill="#ffe3fa" opacity="0.81" /><circle cx="1425" cy="408" r="1.6" fill="#ffe3fa" opacity="0.61" /><circle cx="594" cy="260" r="0.8" fill="#ffe3fa" opacity="0.46" /><circle cx="208" cy="374" r="1.9" fill="#ffe3fa" opacity="0.47" /><circle cx="1295" cy="394" r="0.7" fill="#ffe3fa" opacity="0.62" /><circle cx="544" cy="351" r="1.7" fill="#ffe3fa" opacity="0.44" /><circle cx="1407" cy="544" r="1.6" fill="#ffe3fa" opacity="0.8" /><circle cx="1437" cy="434" r="1.2" fill="#ffe3fa" opacity="0.66" /><circle cx="944" cy="252" r="1.0" fill="#ffe3fa" opacity="0.65" /><circle cx="1523" cy="452" r="1.5" fill="#ffe3fa" opacity="0.92" /><circle cx="1525" cy="142" r="1.1" fill="#ffe3fa" opacity="0.42" /><circle cx="44" cy="386" r="2.0" fill="#ffe3fa" opacity="0.6" /><circle cx="422" cy="512" r="0.8" fill="#ffe3fa" opacity="0.59" /><circle cx="473" cy="158" r="2.0" fill="#ffe3fa" opacity="0.81" /><circle cx="1399" cy="583" r="0.9" fill="#ffe3fa" opacity="0.51" /><circle cx="1330" cy="103" r="1.5" fill="#ffe3fa" opacity="0.57" /><circle cx="1336" cy="162" r="1.1" fill="#ffe3fa" opacity="0.42" /><circle cx="575" cy="280" r="1.6" fill="#ffe3fa" opacity="0.74" /><circle cx="409" cy="160" r="1.1" fill="#ffe3fa" opacity="0.91" /><circle cx="552" cy="164" r="2.0" fill="#ffe3fa" opacity="0.8" /><circle cx="1243" cy="435" r="1.4" fill="#ffe3fa" opacity="0.67" /><circle cx="596" cy="600" r="1.1" fill="#ffe3fa" opacity="0.63" /><circle cx="1135" cy="558" r="0.8" fill="#ffe3fa" opacity="0.55" /><circle cx="286" cy="233" r="0.8" fill="#ffe3fa" opacity="0.65" /><circle cx="1348" cy="451" r="1.9" fill="#ffe3fa" opacity="0.46" /><circle cx="1194" cy="234" r="1.1" fill="#ffe3fa" opacity="0.69" /><circle cx="1013" cy="172" r="1.4" fill="#ffe3fa" opacity="0.86" /><circle cx="1432" cy="240" r="2.0" fill="#ffe3fa" opacity="0.65" /><circle cx="284" cy="335" r="1.9" fill="#ffe3fa" opacity="0.61" /><circle cx="246" cy="424" r="2.0" fill="#ffe3fa" opacity="0.89" /><circle cx="312" cy="102" r="0.8" fill="#ffe3fa" opacity="0.89" /><circle cx="1315" cy="510" r="1.2" fill="#ffe3fa" opacity="0.91" /><circle cx="1535" cy="617" r="1.3" fill="#ffe3fa" opacity="0.53" /><circle cx="1431" cy="249" r="1.7" fill="#ffe3fa" opacity="0.42" /><circle cx="652" cy="112" r="1.4" fill="#ffe3fa" opacity="0.53" /><circle cx="279" cy="268" r="0.9" fill="#ffe3fa" opacity="0.43" /><circle cx="262" cy="195" r="1.2" fill="#ffe3fa" opacity="0.68" /><circle cx="1322" cy="413" r="1.2" fill="#ffe3fa" opacity="0.56" /><circle cx="1309" cy="263" r="1.6" fill="#ffe3fa" opacity="0.77" /><circle cx="606" cy="114" r="0.7" fill="#ffe3fa" opacity="0.52" /><circle cx="414" cy="114" r="0.9" fill="#ffe3fa" opacity="0.51" /><circle cx="251" cy="259" r="1.9" fill="#ffe3fa" opacity="0.78" /><circle cx="574" cy="484" r="1.8" fill="#ffe3fa" opacity="0.55" /><circle cx="577" cy="250" r="1.1" fill="#ffe3fa" opacity="0.81" /><circle cx="349" cy="274" r="1.3" fill="#ffe3fa" opacity="0.57" /><circle cx="1013" cy="581" r="1.1" fill="#ffe3fa" opacity="0.57" /><circle cx="1198" cy="553" r="1.3" fill="#ffe3fa" opacity="0.61" /><circle cx="952" cy="212" r="1.7" fill="#ffe3fa" opacity="0.56" /><circle cx="410" cy="355" r="1.8" fill="#ffe3fa" opacity="0.66" /><circle cx="1328" cy="276" r="1.1" fill="#ffe3fa" opacity="0.61" /><circle cx="1118" cy="545" r="0.8" fill="#ffe3fa" opacity="0.54" /><circle cx="1115" cy="406" r="0.7" fill="#ffe3fa" opacity="0.81" /><circle cx="446" cy="430" r="1.6" fill="#ffe3fa" opacity="0.74" /><circle cx="1226" cy="146" r="1.1" fill="#ffe3fa" opacity="0.75" /><circle cx="1567" cy="446" r="1.8" fill="#ffe3fa" opacity="0.42" /><circle cx="727" cy="271" r="0.8" fill="#ffe3fa" opacity="0.76" /><circle cx="114" cy="172" r="1.1" fill="#ffe3fa" opacity="0.48" /><circle cx="69" cy="464" r="0.8" fill="#ffe3fa" opacity="0.83" /><circle cx="126" cy="116" r="1.4" fill="#ffe3fa" opacity="0.78" /><circle cx="1012" cy="128" r="0.8" fill="#ffe3fa" opacity="0.62" /><circle cx="1509" cy="190" r="0.8" fill="#ffe3fa" opacity="0.83" /><circle cx="464" cy="542" r="1.4" fill="#ffe3fa" opacity="0.45" /><circle cx="1004" cy="631" r="1.6" fill="#ffe3fa" opacity="0.89" /><circle cx="172" cy="643" r="1.2" fill="#ffe3fa" opacity="0.75" /><circle cx="288" cy="310" r="0.8" fill="#ffe3fa" opacity="0.87" /><circle cx="586" cy="335" r="1.7" fill="#ffe3fa" opacity="0.89" /><circle cx="718" cy="185" r="1.5" fill="#ffe3fa" opacity="0.61" /><circle cx="1138" cy="125" r="1.1" fill="#ffe3fa" opacity="0.64" /><circle cx="1449" cy="557" r="1.2" fill="#ffe3fa" opacity="0.43" /><circle cx="520" cy="299" r="1.0" fill="#ffe3fa" opacity="0.71" /><circle cx="95" cy="498" r="1.1" fill="#ffe3fa" opacity="0.74" /><circle cx="1115" cy="364" r="1.5" fill="#ffe3fa" opacity="0.5" /><circle cx="101" cy="592" r="1.7" fill="#ffe3fa" opacity="0.78" /><circle cx="1362" cy="207" r="2.0" fill="#ffe3fa" opacity="0.58" /><circle cx="1513" cy="393" r="1.4" fill="#ffe3fa" opacity="0.69" /><circle cx="450" cy="564" r="0.7" fill="#ffe3fa" opacity="0.46" /><circle cx="399" cy="599" r="1.1" fill="#ffe3fa" opacity="0.69" /><circle cx="1395" cy="330" r="1.1" fill="#ffe3fa" opacity="0.56" /><circle cx="60" cy="647" r="0.8" fill="#ffe3fa" opacity="0.92" /><circle cx="1159" cy="344" r="1.5" fill="#ffe3fa" opacity="0.54" /><path d="M280 142.0Q282.85 165.55 307.0 169Q283.3 171.55 280 196.0Q277.75 171.7 253.0 169Q276.7 166.15 280 142.0Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M1037 132.8Q1038.71 146.93 1053.2 149Q1038.98 150.53 1037 165.2Q1035.65 150.62 1020.8 149Q1035.02 147.29 1037 132.8Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M1413 154.2Q1416.04 179.32 1441.8 183Q1416.52 185.72 1413 211.8Q1410.6 185.88 1384.2 183Q1409.48 179.96 1413 154.2Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M144 508.6Q145.52 521.16 158.4 523Q145.76 524.36 144 537.4Q142.8 524.44 129.6 523Q142.24 521.48 144 508.6Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M1250 420.2Q1252.09 437.47 1269.8 440Q1252.42 441.87 1250 459.8Q1248.35 441.98 1230.2 440Q1247.58 437.91 1250 420.2Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M988 315.6Q989.52 328.16 1002.4 330Q989.76 331.36 988 344.4Q986.8 331.44 973.6 330Q986.24 328.48 988 315.6Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M120 141.0Q120.95 148.85 129.0 150Q121.1 150.85 120 159.0Q119.25 150.9 111.0 150Q118.9 149.05 120 141.0Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M489 113.0Q489.95 120.85 498.0 122Q490.1 122.85 489 131.0Q488.25 122.9 480.0 122Q487.9 121.05 489 113.0Z" fill="#ffdcf2" filter="url(#smallGlow)" /><path d="M79 380H101M90 369V391" stroke="#ffe8f4" stroke-width="2" opacity=".8" /><path d="M1523 530H1537M1530 523V537" stroke="#ffe8f4" stroke-width="2" opacity=".8" /><path d="M1086 210H1094M1090 206V214" stroke="#ffe8f4" stroke-width="2" opacity=".8" /></g>
  <path d="M795 149 C753 155 735 202 751 242 C773 293 838 294 858 247 C829 271 789 249 781 219 C775 191 784 165 795 149 Z" fill="#ffe0c6"/>
  <path d="M955 357 Q1080 289 1160 311 Q1194 330 1148 385" fill="none" stroke="#f9c6e7" stroke-width="4.5" stroke-linecap="round" opacity=".88"/>
  <!-- cloud banks at left edge, away from main heading -->
  <g fill="url(#cloud-left)" opacity=".9">
    <path d="M0 201Q11 174 42 180Q34 140 76 130Q113 128 118 162Q156 140 190 168Q221 165 245 199Q292 188 323 227Q357 232 378 258Q416 248 446 289Q481 288 521 321Q315 311 232 277Q109 284 0 307Z"/>
    <path d="M0 378Q26 339 66 355Q77 324 123 335Q145 315 186 341Q215 335 236 366Q282 362 316 404Q358 399 390 431Q461 429 514 471Q261 456 0 476Z" opacity=".7"/>
    <path d="M0 570Q36 534 86 551Q115 507 164 533Q195 498 247 524Q287 512 331 551Q375 542 412 579Q495 571 541 634Q311 615 0 677Z" opacity=".8"/>
  </g>
  <g fill="url(#cloud-right)" opacity=".85">
    <path d="M1600 238Q1548 199 1510 225Q1470 197 1431 233Q1407 221 1365 246Q1324 242 1293 278Q1253 277 1204 312Q1410 318 1600 344Z"/>
    <path d="M1600 386Q1560 362 1537 382Q1519 327 1476 334Q1449 305 1413 341Q1373 330 1343 364Q1318 356 1279 388Q1228 396 1191 437Q1375 438 1600 476Z" opacity=".85"/>
    <path d="M1600 558Q1554 508 1510 537Q1475 488 1428 512Q1404 475 1356 506Q1327 495 1296 528Q1261 533 1236 570Q1194 574 1152 614Q1378 608 1600 662Z" opacity=".89"/>
  </g>
  <!-- distant pastel mountains and landscape -->
  <path d="M0 762Q150 691 269 740T518 761Q648 805 778 767T1066 771T1323 749Q1476 696 1600 754V900H0Z" fill="#c38ac9" opacity=".62"/>
  <path d="M0 790Q169 753 320 787Q402 804 514 783Q639 827 781 793Q887 813 994 793Q1157 750 1294 790Q1426 754 1600 789V900H0Z" fill="#b67dbf" opacity=".8"/>
  <path d="M0 824Q137 799 286 841Q369 813 478 849Q617 831 756 869Q878 831 1005 850Q1134 812 1252 849Q1398 803 1600 835V900H0Z" fill="url(#front-hills)" />
  <path d="M0 869Q183 830 360 873T763 891Q912 867 1123 883Q1390 853 1600 875V900H0Z" fill="#674493" opacity=".9"/>
  <g opacity=".96"><path d="M42 918Q71.5 819.6 119 672" fill="none" stroke="#382765" stroke-width="8" stroke-linecap="round" /><ellipse cx="50.8" cy="830.9" rx="23" ry="38" fill="#3c2b67" transform="rotate(-36 50.8 830.9)"/><ellipse cx="95.2" cy="774.7" rx="20" ry="37" fill="#55367c" transform="rotate(36 95.2 774.7)"/><ellipse cx="84.6" cy="722.0" rx="22" ry="47" fill="#473071" transform="rotate(-36 84.6 722.0)"/><ellipse cx="123.1" cy="681.2" rx="19" ry="44" fill="#3c2b67" transform="rotate(36 123.1 681.2)"/><path d="M72 918Q75.0 836.0 96 713" fill="none" stroke="#382765" stroke-width="6" stroke-linecap="round" /><ellipse cx="69.9" cy="836.9" rx="15" ry="56" fill="#55367c" transform="rotate(-36 69.9 836.9)"/><ellipse cx="96.5" cy="794.0" rx="19" ry="44" fill="#3c2b67" transform="rotate(36 96.5 794.0)"/><ellipse cx="77.1" cy="752.6" rx="20" ry="45" fill="#55367c" transform="rotate(-36 77.1 752.6)"/><ellipse cx="108.5" cy="713.9" rx="24" ry="56" fill="#473071" transform="rotate(36 108.5 713.9)"/><path d="M90 918Q89.0 838.4 106 719" fill="none" stroke="#382765" stroke-width="7" stroke-linecap="round" /><ellipse cx="83.6" cy="843.2" rx="18" ry="43" fill="#55367c" transform="rotate(-36 83.6 843.2)"/><ellipse cx="113.4" cy="792.6" rx="24" ry="57" fill="#473071" transform="rotate(36 113.4 792.6)"/><ellipse cx="93.0" cy="754.2" rx="14" ry="53" fill="#473071" transform="rotate(-36 93.0 754.2)"/><ellipse cx="118.0" cy="728.4" rx="22" ry="30" fill="#3c2b67" transform="rotate(36 118.0 728.4)"/><path d="M129 918Q168.5 842.0 226 728" fill="none" stroke="#382765" stroke-width="11" stroke-linecap="round" /><ellipse cx="146.9" cy="851.2" rx="18" ry="28" fill="#3c2b67" transform="rotate(-36 146.9 851.2)"/><ellipse cx="189.7" cy="800.5" rx="15" ry="48" fill="#55367c" transform="rotate(36 189.7 800.5)"/><ellipse cx="186.8" cy="762.5" rx="21" ry="48" fill="#473071" transform="rotate(-36 186.8 762.5)"/><ellipse cx="226.8" cy="734.0" rx="17" ry="37" fill="#3c2b67" transform="rotate(36 226.8 734.0)"/><path d="M141 918Q161.5 870.8 200 800" fill="none" stroke="#382765" stroke-width="9" stroke-linecap="round" /><ellipse cx="146.3" cy="869.6" rx="20" ry="37" fill="#473071" transform="rotate(-36 146.3 869.6)"/><ellipse cx="185.9" cy="845.0" rx="22" ry="30" fill="#473071" transform="rotate(36 185.9 845.0)"/><ellipse cx="175.4" cy="817.5" rx="14" ry="41" fill="#3c2b67" transform="rotate(-36 175.4 817.5)"/><ellipse cx="203.4" cy="795.0" rx="15" ry="48" fill="#473071" transform="rotate(36 203.4 795.0)"/><path d="M166 945Q197.0 862.2 246 738" fill="none" stroke="#382765" stroke-width="11" stroke-linecap="round" /><ellipse cx="179.5" cy="871.0" rx="17" ry="34" fill="#473071" transform="rotate(-36 179.5 871.0)"/><ellipse cx="220.2" cy="821.3" rx="19" ry="40" fill="#3c2b67" transform="rotate(36 220.2 821.3)"/><ellipse cx="214.5" cy="780.6" rx="16" ry="38" fill="#55367c" transform="rotate(-36 214.5 780.6)"/><ellipse cx="251.6" cy="743.7" rx="22" ry="43" fill="#473071" transform="rotate(36 251.6 743.7)"/><path d="M187 945Q188.5 847.8 208 702" fill="none" stroke="#382765" stroke-width="10" stroke-linecap="round" /><ellipse cx="182.8" cy="855.6" rx="17" ry="47" fill="#473071" transform="rotate(-36 182.8 855.6)"/><ellipse cx="209.3" cy="806.4" rx="18" ry="28" fill="#55367c" transform="rotate(36 209.3 806.4)"/><ellipse cx="188.7" cy="753.3" rx="22" ry="41" fill="#3c2b67" transform="rotate(-36 188.7 753.3)"/><ellipse cx="217.7" cy="706.7" rx="19" ry="56" fill="#3c2b67" transform="rotate(36 217.7 706.7)"/><path d="M217 945Q258.0 837.8 317 677" fill="none" stroke="#382765" stroke-width="6" stroke-linecap="round" /><ellipse cx="232.1" cy="844.6" rx="24" ry="57" fill="#473071" transform="rotate(-36 232.1 844.6)"/><ellipse cx="279.3" cy="787.6" rx="15" ry="44" fill="#3c2b67" transform="rotate(36 279.3 787.6)"/><ellipse cx="280.1" cy="732.9" rx="16" ry="47" fill="#3c2b67" transform="rotate(-36 280.1 732.9)"/><ellipse cx="315.7" cy="686.3" rx="14" ry="50" fill="#3c2b67" transform="rotate(36 315.7 686.3)"/><path d="M224 945Q230.5 896.6 255 824" fill="none" stroke="#382765" stroke-width="7" stroke-linecap="round" /><ellipse cx="222.1" cy="891.9" rx="18" ry="48" fill="#3c2b67" transform="rotate(-36 222.1 891.9)"/><ellipse cx="252.8" cy="867.6" rx="20" ry="38" fill="#3c2b67" transform="rotate(36 252.8 867.6)"/><ellipse cx="236.1" cy="841.3" rx="17" ry="44" fill="#55367c" transform="rotate(-36 236.1 841.3)"/><ellipse cx="260.6" cy="818.2" rx="14" ry="51" fill="#55367c" transform="rotate(36 260.6 818.2)"/><path d="M265 945Q264.5 877.0 282 775" fill="none" stroke="#382765" stroke-width="6" stroke-linecap="round" /><ellipse cx="260.2" cy="883.1" rx="16" ry="31" fill="#473071" transform="rotate(-36 260.2 883.1)"/><ellipse cx="285.8" cy="840.2" rx="19" ry="42" fill="#55367c" transform="rotate(36 285.8 840.2)"/><ellipse cx="266.9" cy="807.2" rx="17" ry="39" fill="#473071" transform="rotate(-36 266.9 807.2)"/><ellipse cx="290.2" cy="772.8" rx="16" ry="55" fill="#55367c" transform="rotate(36 290.2 772.8)"/><path d="M1555 938Q1525.0 828.0 1477 663" fill="none" stroke="#382765" stroke-width="6" stroke-linecap="round" /><ellipse cx="1540.3" cy="841.5" rx="14" ry="40" fill="#3c2b67" transform="rotate(36 1540.3 841.5)"/><ellipse cx="1502.5" cy="774.8" rx="18" ry="50" fill="#473071" transform="rotate(-36 1502.5 774.8)"/><ellipse cx="1510.5" cy="723.6" rx="20" ry="39" fill="#55367c" transform="rotate(36 1510.5 723.6)"/><ellipse cx="1471.8" cy="672.6" rx="21" ry="51" fill="#55367c" transform="rotate(-36 1471.8 672.6)"/><path d="M1534 938Q1506.0 864.8 1460 755" fill="none" stroke="#382765" stroke-width="7" stroke-linecap="round" /><ellipse cx="1523.6" cy="867.0" rx="19" ry="46" fill="#3c2b67" transform="rotate(36 1523.6 867.0)"/><ellipse cx="1483.0" cy="829.8" rx="19" ry="32" fill="#473071" transform="rotate(-36 1483.0 829.8)"/><ellipse cx="1491.1" cy="786.6" rx="18" ry="51" fill="#3c2b67" transform="rotate(36 1491.1 786.6)"/><ellipse cx="1456.9" cy="762.4" rx="17" ry="31" fill="#55367c" transform="rotate(-36 1456.9 762.4)"/><path d="M1509 938Q1468.5 825.6 1410 657" fill="none" stroke="#382765" stroke-width="9" stroke-linecap="round" /><ellipse cx="1490.5" cy="837.6" rx="18" ry="46" fill="#473071" transform="rotate(36 1490.5 837.6)"/><ellipse cx="1444.1" cy="778.9" rx="20" ry="29" fill="#473071" transform="rotate(-36 1444.1 778.9)"/><ellipse cx="1446.7" cy="715.0" rx="16" ry="51" fill="#3c2b67" transform="rotate(36 1446.7 715.0)"/><ellipse cx="1406.9" cy="665.9" rx="21" ry="55" fill="#55367c" transform="rotate(-36 1406.9 665.9)"/><path d="M1471 938Q1470.0 827.6 1451 662" fill="none" stroke="#382765" stroke-width="10" stroke-linecap="round" /><ellipse cx="1474.3" cy="837.4" rx="15" ry="51" fill="#3c2b67" transform="rotate(36 1474.3 837.4)"/><ellipse cx="1449.9" cy="778.1" rx="17" ry="39" fill="#3c2b67" transform="rotate(-36 1449.9 778.1)"/><ellipse cx="1470.7" cy="718.3" rx="23" ry="52" fill="#55367c" transform="rotate(36 1470.7 718.3)"/><ellipse cx="1444.3" cy="677.0" rx="14" ry="36" fill="#3c2b67" transform="rotate(-36 1444.3 677.0)"/><path d="M1433 938Q1393.0 827.6 1335 662" fill="none" stroke="#382765" stroke-width="6" stroke-linecap="round" /><ellipse cx="1417.9" cy="837.7" rx="23" ry="50" fill="#55367c" transform="rotate(36 1417.9 837.7)"/><ellipse cx="1369.3" cy="772.8" rx="19" ry="54" fill="#55367c" transform="rotate(-36 1369.3 772.8)"/><ellipse cx="1372.6" cy="724.3" rx="18" ry="35" fill="#3c2b67" transform="rotate(36 1372.6 724.3)"/><ellipse cx="1334.9" cy="676.6" rx="16" ry="37" fill="#55367c" transform="rotate(-36 1334.9 676.6)"/><path d="M1469 922Q1460.5 835.6 1434 706" fill="none" stroke="#382765" stroke-width="6" stroke-linecap="round" /><ellipse cx="1473.4" cy="844.6" rx="24" ry="36" fill="#473071" transform="rotate(36 1473.4 844.6)"/><ellipse cx="1441.2" cy="789.0" rx="15" ry="53" fill="#3c2b67" transform="rotate(-36 1441.2 789.0)"/><ellipse cx="1453.4" cy="753.5" rx="16" ry="31" fill="#55367c" transform="rotate(36 1453.4 753.5)"/><ellipse cx="1425.1" cy="708.4" rx="20" ry="55" fill="#3c2b67" transform="rotate(-36 1425.1 708.4)"/><path d="M1442 922Q1425.5 838.0 1391 712" fill="none" stroke="#382765" stroke-width="7" stroke-linecap="round" /><ellipse cx="1441.0" cy="848.9" rx="23" ry="29" fill="#55367c" transform="rotate(36 1441.0 848.9)"/><ellipse cx="1404.4" cy="800.6" rx="17" ry="29" fill="#473071" transform="rotate(-36 1404.4 800.6)"/><ellipse cx="1417.2" cy="751.2" rx="20" ry="50" fill="#55367c" transform="rotate(36 1417.2 751.2)"/><ellipse cx="1384.3" cy="714.5" rx="19" ry="53" fill="#3c2b67" transform="rotate(-36 1384.3 714.5)"/><path d="M1413 922Q1394.5 854.0 1358 752" fill="none" stroke="#382765" stroke-width="10" stroke-linecap="round" /><ellipse cx="1405.2" cy="853.1" rx="14" ry="51" fill="#473071" transform="rotate(36 1405.2 853.1)"/><ellipse cx="1373.9" cy="817.5" rx="16" ry="41" fill="#3c2b67" transform="rotate(-36 1373.9 817.5)"/><ellipse cx="1385.9" cy="777.9" rx="21" ry="57" fill="#3c2b67" transform="rotate(36 1385.9 777.9)"/><ellipse cx="1349.2" cy="755.7" rx="23" ry="38" fill="#3c2b67" transform="rotate(-36 1349.2 755.7)"/><path d="M1383 922Q1382.5 869.6 1364 791" fill="none" stroke="#382765" stroke-width="10" stroke-linecap="round" /><ellipse cx="1386.0" cy="862.8" rx="14" ry="57" fill="#473071" transform="rotate(36 1386.0 862.8)"/><ellipse cx="1358.7" cy="838.9" rx="23" ry="39" fill="#3c2b67" transform="rotate(-36 1358.7 838.9)"/><ellipse cx="1381.5" cy="808.5" rx="20" ry="51" fill="#473071" transform="rotate(36 1381.5 808.5)"/><ellipse cx="1353.5" cy="788.0" rx="20" ry="46" fill="#473071" transform="rotate(-36 1353.5 788.0)"/><path d="M1339 922Q1301.5 835.6 1246 706" fill="none" stroke="#382765" stroke-width="10" stroke-linecap="round" /><ellipse cx="1323.5" cy="843.6" rx="20" ry="39" fill="#55367c" transform="rotate(36 1323.5 843.6)"/><ellipse cx="1275.5" cy="788.6" rx="23" ry="54" fill="#55367c" transform="rotate(-36 1275.5 788.6)"/><ellipse cx="1283.5" cy="754.2" rx="20" ry="29" fill="#473071" transform="rotate(36 1283.5 754.2)"/><ellipse cx="1246.6" cy="708.7" rx="14" ry="54" fill="#473071" transform="rotate(-36 1246.6 708.7)"/></g>
</svg>
            <div class="ayuniya-hero-content container">
                <h1 id="hero-title">AYUNIYA</h1>
                <p class="ayuniya-tagline">AWARENESS, YOUR UNIVERSE, NOTES, INTENTIONS, YOUR AURA</p>
                <div class="ayuniya-actions">
                    <a class="ayuniya-action ayuniya-action-primary" href="register.php" data-auth-trigger="register">Start your journey</a>
                    <a class="ayuniya-action ayuniya-action-secondary" href="#features">Explore features</a>
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
                <a class="button" href="register.php" data-auth-trigger="register">Create your space <span aria-hidden="true">→</span></a>
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
                <span class="modal-password-field"><input id="modal-register-password" name="password" type="password" autocomplete="new-password" placeholder="At least 8 characters" minlength="8" required><button class="modal-password-toggle" type="button" aria-label="Show password"><i data-feather="eye"></i></button></span>
                <label for="modal-confirm-password">Confirm password</label>
                <span class="modal-password-field"><input id="modal-confirm-password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Repeat your password" minlength="8" required><button class="modal-password-toggle" type="button" aria-label="Show password confirmation"><i data-feather="eye"></i></button></span>
                <button class="button" type="submit">Create account <span aria-hidden="true">→</span></button>
            </form>
            <form class="modal-form" id="login-form" action="login.php" method="post" hidden>
                <label for="modal-login-email">Email address</label>
                <input id="modal-login-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                <label for="modal-login-password">Password</label>
                <span class="modal-password-field"><input id="modal-login-password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required><button class="modal-password-toggle" type="button" aria-label="Show password"><i data-feather="eye"></i></button></span>
                <button class="button" type="submit">Log in <span aria-hidden="true">→</span></button>
            </form>
            <p class="modal-switch" id="modal-switch">Already have an account? <button type="button" data-auth-switch="login">Log in</button></p>
        </section>
    </div>

    <footer>
        <div class="footer-inner container">
            <a class="brand footer-brand" href="#"><span class="brand-mark">☾</span> AYUNIYA</a>
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
        document.querySelectorAll(".modal-password-toggle").forEach((toggle) => toggle.addEventListener("click", () => {
            const input = toggle.parentElement.querySelector("input"), visible = input.type === "text";
            input.type = visible ? "password" : "text";
            toggle.setAttribute("aria-label", visible ? "Show password" : "Hide password");
            toggle.innerHTML = `<i data-feather="${visible ? "eye" : "eye-off"}"></i>`;
            feather.replace();
        }));

        const showAuthModal = (mode) => {
            const isLogin = mode === "login";
            registerForm.reset();
            loginForm.reset();
            document.querySelectorAll(".modal-password-field input").forEach((input) => { input.type = "password"; });
            document.querySelectorAll(".modal-password-toggle").forEach((toggle) => { toggle.setAttribute("aria-label", "Show password"); toggle.innerHTML = '<i data-feather="eye"></i>'; });
            feather.replace();
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