<!DOCTYPE html>
<html>
<head>
    <title>Pembaruan Status Konseling</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 40px; }
        .card { max-width: 600px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 40px; margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .logo { font-size: 20px; font-weight: 800; color: #0f2942; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px; }
        .greeting { font-size: 16px; font-weight: 700; color: #0f2942; margin-bottom: 16px; }
        .text { font-size: 14px; color: #475569; margin-bottom: 24px; }
        .details { background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 20px; margin-bottom: 30px; }
        .detail-row { display: flex; margin-bottom: 12px; font-size: 14px; }
        .detail-row:last-child { margin-bottom: 0; }
        .label { font-weight: 700; color: #0f2942; width: 120px; }
        .val { color: #334155; flex: 1; }
        .footer { border-top: 1px solid #e2e8f0; padding-top: 20px; font-size: 12px; color: #94a3b8; text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">FILKOM Sebaya</div>
        <div class="greeting">Halo {{ $name }},</div>
        <div class="text">Status pengajuan konseling Anda telah diperbarui.</div>
        
        <div class="details">
            <div class="detail-row">
                <div class="label">Topik:</div>
                <div class="val">{{ $topic }}</div>
            </div>
            <div class="detail-row">
                <div class="label">Status:</div>
                <div class="val">{{ $status }}</div>
            </div>
        </div>
        
        <div class="text">Silakan login ke sistem FILKOM Sebaya untuk melihat detail lebih lanjut.</div>
        
        <div class="footer">
            &copy; 2025 FILKOM Sebaya.
        </div>
    </div>
</body>
</html>
