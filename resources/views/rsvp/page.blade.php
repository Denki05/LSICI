<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Open Graph & Twitter Card for Sharing -->
    <!-- MS Tile - for Microsoft apps-->
    <meta name="msapplication-TileImage" content="{{ route('rsvp.image', ['filename' => 'thumnailcic.png']) }}">    
    
    <!-- fb & Whatsapp -->
    
    <!-- Site Name, Title, and Description to be displayed -->
    <meta property="og:site_name" content="Invitation - Indonesia Cosmetics Ingredients 2025">
    <meta property="og:title" content="Invitation - Indonesia Cosmetics Ingredients 2025">
    <meta property="og:description" content="Join us at JIExpo Kemayoran, Jakarta — May 14-16, 2025. Booth S4 (17 - 20). Tap to RSVP and be part of the exclusive event!">
    
    <!-- Image to display -->
    <!-- Replace   «example.com/image01.jpg» with your own -->
    <meta property="og:image" content="{{ route('rsvp.image', ['filename' => 'thumnailcic.png']) }}">
    
    <!-- No need to change anything here -->
    <meta property="og:type" content="website" />
    <meta property="og:image:type" content="image/png">
    
    <!-- Size of image. Any size up to 300. Anything above 300px will not work in WhatsApp -->
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">
    

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <title>Invitation - Indonesia Cosmetics Ingredients 2025</title>
  <link rel="icon" href="{{ route('rsvp.image', ['filename' => 'thumnailcic.png']) }}" type="image/x-icon">
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
      font-size: 45px;
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
      font-size: 18px;
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

    .jumbotron {
      background-color: #f8f9fa;
      border-left: 5px solid #ffc107;
      border-right: 5px solid #ffc107;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      font-weight: bold;
      font-size: 12px;
      color: #555;
      max-width: 600px;
      margin: -20px auto 0; /* Naikkan sedikit dengan margin negatif */
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .rsvp-button-group {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin: -20px auto 0;
    }

    .rsvp-button-group form {
      margin: 0;
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
        font-size: 40px;
        margin-top: 15px;
      }
      .date-highlight {
        font-size: 16px;
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
      
    <div style="margin-bottom: 10px;"><strong>Dear,</strong></div>
    <div class="dear-invite">
      <div class="dear-text"><i>{{ $guest->name }}</i></div>
    </div>
    <div class="date-highlight">YOU'RE INVITED TO</div>
    <div class="title">INDONESIA<br>COSMETICS<br>INGREDIENTS<br>2025</div>
    <div class="date-highlight">May, 14-16 2025</div>

    <div class="detail-rsvp-box">
      <div class="event-left-row">
        <div class="event-details">
          <div class="date"> 
          <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 0 24 24" fill="#d19e1d" style="vertical-align: middle; margin-right: 5px;">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 
                    0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 
                    2.5 2.5-1.12 2.5-2.5 2.5z"/>
          </svg>
            JIExpo Kemayoran, Jakarta <br> Booth S4 (17 - 20)</div>
        </div>

        <div class="rsvp-buttons">
          @if ($guest->attendance == 0)
          <div class="rsvp-button-group">
            <form action="{{ route('admin.updateInvitation', ['id' => $guest->slug]) }}" method="POST">
              @csrf
              <input type="hidden" name="is_attending" value="1">
              <button type="submit" class="attend">Attend</button>
            </form>

            <form action="{{ route('admin.updateInvitation', ['id' => $guest->slug]) }}" method="POST">
              @csrf
              <input type="hidden" name="is_attending" value="2">
              <button type="submit" class="not-attend">Not Attend</button>
            </form>
          </div>
          @elseif ($guest->attendance == 1 || $guest->attendance == 2)
          <div class="jumbotron">
            Thank you for your RSVP. We're glad you're attending!
          </div>
          @endif
        </div>
      </div>  
        <div class="event-right-row">
          <img src="{{ route('qr.show', ['filename' => $guest->qr_code_path]) }}" alt="QR Code untuk {{ $guest->name }}">
        </div>
      </div>
  
      
    </div>
      </div>
  <div class="footer-logos">
    <img src="{{ route('rsvp.image', ['filename' => 'footer-logo.png']) }}" alt="Senses-GCF-JN">
  </div>
</body>
</html>