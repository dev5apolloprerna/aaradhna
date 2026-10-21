<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <style>
        /* Colours taken from the Shri Shakti Pratishthan Trust logo */
        :root {
            --brand:#2b3a6e;        /* logo ring navy */
            --brand-dark:#1f2a52;
            --accent:#9b1b1f;       /* trident maroon */
            --saffron:#f2a51c;      /* crown / face gold */
            --ink:#1f2433;
            --muted:#667085;
            --paper:#fff;
            --line:#e3e6ef;
        }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--ink); background:linear-gradient(145deg,#eef1f8 0%,#fff 52%,#fdf4e6 100%); font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif; min-height:100vh; }
        .topbar { background:var(--brand-dark); color:#fff; padding:10px 5vw; display:flex; justify-content:space-between; align-items:center; border-bottom:4px solid var(--saffron); }
        .brand { display:flex; align-items:center; text-decoration:none; }
        .brand img { width:64px; height:64px; border-radius:50%; background:#fff; border:2px solid #fff; display:block; }
        .secure { font-size:.86rem; opacity:.85; }
        .shell { width:min(1120px,92%); margin:44px auto; }
        .intro { text-align:center; max-width:720px; margin:0 auto 30px; }
        .eyebrow { color:var(--accent); font-weight:800; text-transform:uppercase; letter-spacing:.14em; font-size:.76rem; }
        h1 { font-family:Georgia,serif; font-size:clamp(2rem,4vw,3.2rem); margin:10px 0; color:var(--brand-dark); }
        h2,h3 { color:var(--brand-dark); }
        .muted { color:var(--muted); line-height:1.65; }
        .card { background:var(--paper); border:1px solid var(--line); border-top:4px solid var(--brand); border-radius:18px; box-shadow:0 18px 55px rgba(31,42,82,.09); padding:clamp(22px,4vw,42px); }
        .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; }
        .full { grid-column:1/-1; }
        label { display:block; font-weight:700; font-size:.9rem; margin-bottom:8px; }
        input,select { width:100%; border:1px solid #d5d9e4; border-radius:9px; padding:13px 14px; font:inherit; background:#fff; }
        input:focus,select:focus { outline:3px solid rgba(43,58,110,.14); border-color:var(--brand); }
        .plans { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; }
        .plan { cursor:pointer; position:relative; }
        .plan input { position:absolute; opacity:0; }
        .plan-box { display:block; height:100%; border:2px solid var(--line); border-radius:13px; padding:18px; transition:.2s; }
        .plan input:checked + .plan-box { border-color:var(--brand); background:#f3f5fb; box-shadow:0 0 0 3px rgba(43,58,110,.1); }
        .price { display:block; font-size:1.6rem; font-weight:850; margin:8px 0 3px; color:var(--accent); }
        .btn { display:inline-flex; justify-content:center; border:0; border-radius:10px; padding:14px 24px; background:var(--brand); color:white; font-size:1rem; font-weight:800; cursor:pointer; text-decoration:none; }
        .btn:hover { background:var(--brand-dark); }
        .btn:disabled { cursor:wait; opacity:.65; }
        .btn-secondary { background:#f1f3f9; border:1px solid #cdd3e3; color:var(--brand-dark); padding:10px 16px; }
        .btn-secondary:hover { background:#e4e8f3; }
        .btn-block { width:100%; }

        .lookup-status { display:block; font-size:.82rem; line-height:1.45; margin-top:7px; }
        .lookup-success { color:#137a3f; font-weight:700; }
        .lookup-error { color:var(--accent); font-weight:700; }

        .alert { border-radius:10px; padding:14px 17px; background:#fdf0f0; border:1px solid #efc2c2; color:var(--accent); margin-bottom:22px; }
        .alert ul { margin:0; padding-left:20px; }
        .section-title { border-bottom:2px solid var(--saffron); padding-bottom:12px; margin:10px 0 20px; }
        .summary-row { display:flex; justify-content:space-between; gap:20px; padding:13px 0; border-bottom:1px solid var(--line); }
        .summary-row:last-child { border-bottom:0; }
        .check { width:72px;height:72px;border-radius:50%;margin:0 auto 20px;background:#e7f6ed;color:#137a3f;display:grid;place-items:center;font-size:2.2rem; }
        @media(max-width:700px) { .grid { grid-template-columns:1fr; } .full { grid-column:auto; } .shell { margin:25px auto; } .secure { display:none; } .brand img { width:54px; height:54px; } }
    </style>
    @stack('head')
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ url('/') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}"></a>
    <div class="secure">🔒 Secure customer checkout</div>
</header>
<main class="shell">@yield('content')</main>
@stack('scripts')
</body>
</html>