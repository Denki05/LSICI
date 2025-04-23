<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <title>Invitation - Indonesia Cosmetics Ingredients 2025</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      background: #f9f9f9;
      color: #333;
      position: relative;
    }

    .background-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('{{ route('rsvp.image', ['filename' => 'bg.jpg']) }}');
      background-size: cover;
      background-position: center;
      opacity: 10;
      z-index: 0;
    }
    
    .header,
    .content,
    .footer-logos {
      position: relative;
      z-index: 1;
    }
    .header {
      padding: 20px;
      text-align: center;
    }
    .header img {
      max-height: 40px;
      width: auto;
    }
    .content {
      padding-top: 2px;
      padding-bottom: 2px;
      padding-left: 15px;
      padding-right: 15px;
      text-align: center;
    }
    .dear-invite {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 2px;
    }
    .event-left-row {
      display: flex;
      justify-content: start;
      align-items: center;
      flex-wrap: wrap;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 2px;
    }
    .dear-text {
      font-weight: bold;
    }
    .invite-box {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeeba;
      border-radius: 25px;
      padding: 5px 15px;
      font-weight: bold;
      font-size: 14px;
    }
    .title {
      font-family: "League Spartan", sans-serif;
      font-size: 47px;
      font-weight: 900;
      margin: 15px 0 5px;
    }
    .date-highlight {
      background: #d19e1d;
      background: -webkit-linear-gradient(to right, #d19e1d, #ffd86e, #e3a812);
      background: linear-gradient(to right, #d19e1d, #ffd86e, #e3a812);
      display: inline-block;
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 900;
      font-size: 30px;
      -webkit-text-stroke: 0.5px #ccc;
      color: #000000;
      margin: 10px 0 20px;
    }
    .detail-rsvp-box {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: row;
      gap: 10px;
      margin-bottom: 2px;
      background-color: rgba(255, 255, 255, 0.85);
      border: 2px solid #eac27d;
      border-radius: 15px;
      padding: 20px;
      margin: 20px 0;
    }
    .event-details {
      margin-bottom: 20px;
    }
    .date {
      font-weight: bold;
      color: #000;
    }
    .location {
      margin: 15px 0;
      font-size: 14px;
      color: #666;
    }
    .booth {
      color: #000;
      font-size: 15px;
      font-weight: bold;
    }
    .rsvp-buttons {
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
    }
    .rsvp-buttons button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      max-width: 300px;
    }
    .attend {
      background-color: #f4b63d;
      color: #fff;
    }
    .not-attend {
      background-color: #ccc;
      color: #333;
    }
    .footer-logos {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      padding: 10px;
    }
    .footer-logos img {
      height: 60px;
      width: auto;
    }

    .event-right-row img {
      max-height: 120px;
      width: auto;
    }
    @media (max-width: 600px) {
      .title {
        font-size: 47px;
      }
      .date-highlight {
        font-size: 20px;
        padding: 6px 12px;
      }
      .location {
        font-size: 13px;
      }
      .dear-invite {
        flex-direction: row;
        gap: 5px;
      }
    }
  </style>
</head>
<body>
  <div class="background-overlay">
    <!-- <img src="{{ route('rsvp.image', ['filename' => 'bg.jpg']) }}" alt="BG"> -->
  </div>
    <div class="content">
      <div class="header">
      <img src="{{ route('rsvp.image', ['filename' => 'ppi.png']) }}" alt="Premium Parfum Indonesia">
      </div>
    <div class="dear-invite">
      <div class="dear-text">Dear, <strong>________________</strong></div>
      <div class="invite-box">YOU'RE INVITED TO</div>
    </div>
    <div class="title">INDONESIA<br>COSMETICS<br>INGREDIENTS<br>2025</div>
    <div class="date-highlight">May, 14-16 2025</div>

    <div class="detail-rsvp-box">
      <div class="event-left-row">
        <div class="event-details">
          <div class="date">JIExpo Kemayoran, Jakarta <br> Booth S4 (17 - 20)</div>
        </div>
        <div class="rsvp-buttons">
          <button class="attend">Attend</button>
          <button class="not-attend">Not Attend</button>
        </div>
      </div>  
        <div class="event-right-row">
          <img src="{{ route('rsvp.image', ['filename' => 'qr-code.png']) }}" alt="qr-code">
        </div>
      </div>
  
      
    </div>
      </div>
  <div class="footer-logos">
    <img src="{{ route('rsvp.image', ['filename' => 'footer-logo.png']) }}" alt="Senses-GCF-JN">
  </div>
</body>
</html>