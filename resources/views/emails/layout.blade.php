<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f4f5; padding: 32px 16px; color: #18181b; }
        .container { max-width: 560px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #ef4444, #f97316); padding: 32px; text-align: center; }
        .header h1 { color: white; font-size: 24px; font-weight: 900; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin-top: 4px; }
        .body { padding: 32px; }
        .badge { display: inline-block; background: #f4f4f5; border-radius: 999px; padding: 6px 16px; font-size: 13px; font-weight: 700; color: #71717a; margin-bottom: 24px; }
        .info-box { background: #f9fafb; border: 1px solid #e4e4e7; border-radius: 16px; padding: 20px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f4f4f5; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-row span:first-child { color: #71717a; }
        .info-row span:last-child { font-weight: 700; }
        .total-box { background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 16px; padding: 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .total-box span:first-child { font-size: 14px; color: #166534; }
        .total-box span:last-child { font-size: 24px; font-weight: 900; color: #16a34a; }
        .footer { text-align: center; padding: 24px; font-size: 12px; color: #a1a1aa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            @yield('content')
        </div>
        <div class="footer">
            Menu Online • Todos os direitos reservados
        </div>
    </div>
</body>
</html>
