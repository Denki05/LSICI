<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ICI 2025 Invitation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

    <style>
      .countdown {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
        text-align: center;
      }

      .time-box {
        font-size: 28px;
        font-weight: 400;
        min-width: 60px;
      }

      .label-countdown {
        font-size: 12px;
        color: #555;
        margin-top: 5px;
      }

      .separator {
        font-size: 28px;
        margin: 0 5px;
      }
    </style>
  </head>
  <body class="bg-gradient-to-r from-[#926F34] via-[#926F34] to-[#926F34] text-gray-800 flex flex-col min-h-screen" style="background: #926F34; background: linear-gradient(144deg, rgba(146, 111, 52, 1) 0%, rgba(223, 189, 105, 1) 35%);">

    <!-- Header -->
    <header class="flex-shrink-0 py-6 px-2 md:px-12 text-center">
        <div class="flex flex-row items-center justify-center gap-6 flex-wrap">
          <img src="{{ route('rsvp.image', ['filename' => 'logo_ppi_2.png']) }}" alt="PPI 2025 Logo" class="max-h-20 md:max-h-32 w-auto object-contain">
        
          <!-- Garis pemisah -->
          <div class="h-16 md:h-28 border-l-4 border-black"></div>
        
          <img src="{{ route('rsvp.image', ['filename' => 'logo_ici.png']) }}" alt="ICI 2025 Logo" class="max-h-20 md:max-h-32 w-auto object-contain">
        </div>

        <p class="mt-4 text-base md:text-sm">
          Kepada <b><i>{{ $guest->name }}</i></b>, Bergabunglah bersama kami dalam pameran wewangian terbesar di Indonesia!
        </p>

        <!-- Countdown -->
        <div class="countdown mt-4" id="countdown"></div>
      </header>


    <!-- Main content -->
    <main class="flex-grow">
      <section class="container mx-auto px-2 py-6 md:py-12 bg-white shadow-lg rounded-t-3xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 md:px-12">

          <!-- 1. Event Details -->
          <div class="text-center md:text-center">
              <h2 class="text-2xl md:text-2xl font-bold">Detail Acara</h2>
              <p class="mt-4 text-black-800 text-sm">📅 <strong>Tanggal:</strong> 14-16 May 2025</p>
              <p class="mt-4 text-black-800 text-sm">📍 <strong>Lokasi:</strong> Hall D & A3, JL Expo Kemayoran, Jakarta, Indonesia</p>
              <p class="mt-4 text-black-800 text-sm">⏰ <strong>Waktu:</strong> 10:00 - 18:00 WIB</p>
          </div>

          <!-- 2. RSVP Form or Confirmation -->
            <div class="text-center md:text-center">
                <h2 class="text-xl md:text-2xl font-bold">Konfirmasi Kehadiran Anda</h2>
                <form action="{{ route('admin.updateInvitation', $guest->slug) }}" method="POST" class="mt-4">
                @csrf

                @if($guest->attendance == 0)
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block">
                          <span class="text-gray-700 text-sm font-bold">Kepada :</span>
                          <input type="text" name="name" class="mt-2 p-2 w-full border rounded text-sm" required readonly value="{{ $guest->name ?? '' }}">
                      </label>
                    </div>
                    <div>
                      <label class="block">
                          <span class="text-gray-700 text-sm font-bold">Kehadiran :</span>
                          <select name="attendance" class="mt-2 p-2 w-full border rounded text-sm" required>
                            <option value="">Silahkan Pilih</option>
                            <option value="1">Ya, Saya hadir</option>
                            <option value="0">Tidak, Saya tidak hadir</option>
                          </select>
                      </label>
                    </div>
                  </div>
                  <div class="mt-4 text-center md:text-left">
                      <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded text-sm md:text-base">RSVP</button>
                  </div>
                @else
                  <div class="mt-4">
                      <p class="text-green-600 text-sm md:text-base">Terima kasih telah mengkonfirmasi kehadiran Anda!</p>
                      <p class="mt-2 text-sm md:text-base">Kami menantikan kehadiran Anda di acara tersebut!</p>
                  </div>
                @endif
              </form>
            </div>

            <!-- 3. QR Code -->
            <div class="flex flex-col items-center text-center">
              @if($guest->qr_code_path)
                <p class="text-sm text-black-700 mb-2"><strong>Scan kode QR ini di acara:</strong></p>
                <!-- <img src="{{ asset($guest->qr_code_path) }}" alt="QR Code" class="w-32 h-32"> -->
                <div id="qrcode" class="w-32 h-32"></div>
              @endif
            </div>
        </div> 
      </section>
    </main>

    <!-- Footer -->
    <footer class="flex-shrink-0 bg-gray-900 text-white py-2 text-center text-sm md:text-base">
      <p class="mt-4">&copy; 2025 LSFRAGRANCE. All Rights Reserved.</p>
    </footer>

    <!-- Countdown Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
      const targetDate = new Date("2025-05-14T10:00:00");

      function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance < 0) {
          document.getElementById("countdown").innerHTML = "<h2>Acara dimulai!</h2>";
          return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = `
          <div class="time-box">
            ${String(days).padStart(2, '0')}
            <div class="label-countdown">Days</div>
          </div>
          <div class="separator">:</div>
          <div class="time-box">
            ${String(hours).padStart(2, '0')}
            <div class="label-countdown">Hours</div>
          </div>
          <div class="separator">:</div>
          <div class="time-box">
            ${String(minutes).padStart(2, '0')}
            <div class="label-countdown">Minutes</div>
          </div>
          <div class="separator">:</div>
          <div class="time-box">
            ${String(seconds).padStart(2, '0')}
            <div class="label-countdown">Seconds</div>
          </div>
        `;
      }

      updateCountdown();
      setInterval(updateCountdown, 1000);

      var qr = new QRCode(document.getElementById("qrcode"), {
        text: "{{ url('/guest-form') }}?slug={{ $guest->slug }}",
        width: 250,
        height: 250
      });
    </script>
  </body>
</html>