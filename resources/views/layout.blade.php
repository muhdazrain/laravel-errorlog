<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ErrorLog</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; background: #0f1115; color: #e2e8f0; font-size: 14px; line-height: 1.5; }
        .el-container { max-width: 1100px; margin: 0 auto; padding: 2rem 1.25rem; }
        .el-header { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 1.5rem; }
        .el-header h1 { font-size: 1.4rem; font-weight: 700; color: #fff; }
        .el-header h1 span { color: #f87171; }
        .el-card { background: #181b23; border: 1px solid #2a2f3a; border-radius: 10px; overflow: hidden; margin-bottom: 1.5rem; }
        table.el-table { width: 100%; border-collapse: collapse; }
        .el-table th { text-align: left; padding: .65rem 1rem; font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #8b93a7; background: #14171e; border-bottom: 1px solid #2a2f3a; }
        .el-table td { padding: .8rem 1rem; border-bottom: 1px solid #232834; vertical-align: top; }
        .el-table tr:last-child td { border-bottom: none; }
        .el-table tr:hover td { background: #1d212b; }
        .el-class { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .82rem; color: #fca5a5; font-weight: 600; }
        .el-message { color: #cbd5e1; font-size: .85rem; margin-top: .15rem; word-break: break-word; }
        .el-meta { color: #8b93a7; font-size: .78rem; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; margin-top: .25rem; }
        .el-count { display: inline-block; min-width: 2.2rem; text-align: center; background: #7f1d1d; color: #fecaca; border-radius: 999px; padding: .1rem .55rem; font-size: .78rem; font-weight: 700; }
        .el-time { color: #94a3b8; font-size: .82rem; white-space: nowrap; }
        .el-link { color: inherit; text-decoration: none; }
        .el-link:hover .el-class { color: #f87171; text-decoration: underline; }
        .el-back { color: #93c5fd; text-decoration: none; font-size: .85rem; }
        .el-back:hover { text-decoration: underline; }
        .el-detail { padding: 1rem 1.25rem; }
        .el-detail dl { display: grid; grid-template-columns: 140px 1fr; gap: .4rem .75rem; margin-top: .75rem; }
        .el-detail dt { color: #8b93a7; font-size: .8rem; }
        .el-detail dd { color: #e2e8f0; font-size: .85rem; word-break: break-word; }
        .el-trace { background: #0b0d11; border: 1px solid #232834; border-radius: 8px; padding: 1rem; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .78rem; overflow-x: auto; color: #a5b4fc; }
        .el-trace .fr { padding: .15rem 0; }
        .el-trace .fr .loc { color: #fbbf24; }
        .el-empty { padding: 3rem 1rem; text-align: center; color: #8b93a7; }
        .el-paginate { margin-top: 1.25rem; }
        .el-paginate nav > div { display: flex; flex-direction: column; gap: .75rem; }
        .el-paginate a, .el-paginate span[aria-disabled] span, .el-paginate span[aria-current] span { display: inline-flex; align-items: center; padding: .35rem .7rem; border-radius: 6px; font-size: .82rem; }
        .el-paginate a { background: #232834; color: #cbd5e1; text-decoration: none; }
        .el-paginate a:hover { background: #2f3644; }
        .el-paginate span[aria-current] span { background: #7f1d1d; color: #fff; }
        .el-paginate span[aria-disabled] span { color: #4b5563; }
        .el-paginate .hidden { display: none; }
        .el-paginate p { color: #8b93a7; font-size: .82rem; }
    </style>
</head>
<body>
    <div class="el-container">
        @yield('content')
    </div>
</body>
</html>
