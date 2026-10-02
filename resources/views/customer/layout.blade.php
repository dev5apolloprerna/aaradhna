<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <style>
        :root { --brand:#7b1f35; --brand-dark:#551326; --gold:#d6a84b; --ink:#20242c; --muted:#697180; --paper:#fff; --line:#e7e2df; }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--ink); background:linear-gradient(145deg,#f8f3ee 0%,#fff 52%,#f4eceb 100%); font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif; min-height:100vh; }
        .topbar { background:var(--brand-dark); color:#fff; padding:17px 5vw; display:flex; justify-content:space-between; align-items:center; }
        .brand { font-family:Georgia,serif; font-size:1.45rem; letter-spacing:.02em; }
        .secure { font-size:.86rem; opacity:.85; }
        .shell { width:min(1120px,92%); margin:44px auto; }
        .intro { text-align:center; max-width:720px; margin:0 auto 30px; }
        .eyebrow { color:var(--brand); font-weight:800; text-transform:uppercase; letter-spacing:.14em; font-size:.76rem; }
        h1 { font-family:Georgia,serif; font-size:clamp(2rem,4vw,3.2rem); margin:10px 0; color:var(--brand-dark); }
        h2,h3 { color:var(--brand-dark); }
        .muted { color:var(--muted); line-height:1.65; }
        .card { background:var(--paper); border:1px solid var(--line); border-radius:18px; box-shadow:0 18px 55px rgba(61,35,37,.09); padding:clamp(22px,4vw,42px); }
        .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; }
        .full { grid-column:1/-1; }
        label { display:block; font-weight:700; font-size:.9rem; margin-bottom:8px; }
        input,select { width:100%; border:1px solid #d9d4d1; border-radius:9px; padding:13px 14px; font:inherit; background:#fff; }
        input:focus,select:focus { outline:3px solid rgba(123,31,53,.12); border-color:var(--brand); }
        .plans { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; }
        .plan { cursor:pointer; position:relative; }
        .plan input { position:absolute; opacity:0; }
        .plan-box { display:block; height:100%; border:2px solid var(--line); border-radius:13px; padding:18px; transition:.2s; }
        .plan input:checked + .plan-box { border-color:var(--brand); background:#fff8f8; box-shadow:0 0 0 3px rgba(123,31,53,.08); }
        .price { display:block; font-size:1.6rem; font-weight:850; margin:8px 0 3px; color:var(--brand); }
        .btn { display:inline-flex; justify-content:center; border:0; border-radius:10px; padding:14px 24px; background:var(--brand); color:white; font-size:1rem; font-weight:800; cursor:pointer; text-decoration:none; }
        .btn:hover { background:var(--brand-dark); }
        .btn-block { width:100%; }
        .alert { border-radius:10px; padding:14px 17px; background:#fff0f0; border:1px solid #edbcbc; color:#842029; margin-bottom:22px; }
        .alert ul { margin:0; padding-left:20px; }
        .section-title { border-bottom:1px solid var(--line); padding-bottom:12px; margin:10px 0 20px; }
        .summary-row { display:flex; justify-content:space-between; gap:20px; padding:13px 0; border-bottom:1px solid var(--line); }
        .summary-row:last-child { border-bottom:0; }
        .check { width:72px;height:72px;border-radius:50%;margin:0 auto 20px;background:#e7f6ed;color:#137a3f;display:grid;place-items:center;font-size:2.2rem; }
        @media(max-width:700px) { .grid { grid-template-columns:1fr; } .full { grid-column:auto; } .shell { margin:25px auto; } .secure { display:none; } }
    </style>
    @stack('head')
</head>
<body>
<header class="topbar"><div class="brand">{{ config('app.name') }}</div><div class="secure">🔒 Secure customer checkout</div></header>
<main class="shell">@yield('content')</main>
@stack('scripts')
</body>
</html>
