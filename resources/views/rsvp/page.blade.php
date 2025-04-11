<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ICI 2025 Invitation</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">

  <!-- Header -->
  <header class="flex-shrink-0 h-64 flex flex-col justify-center items-center px-6 md:px-12 text-center md:text-left text-gray-800 bg-gradient-to-br from-pink-200 via-indigo-100 to-white">
    <h1 class="text-2xl md:text-4xl font-bold leading-snug">PREMIUM PARFUM INDONESIA - ICI 2025</h1>
    <p class="mt-2 text-base md:text-lg">Dear <b><i>{{ $guest->name }}</i></b>, join us at the biggest fragrance exhibition in Indonesia!</p>
  </header>

  <!-- Main content -->
  <main class="flex-grow">
    <section class="container mx-auto -mt-10 md:-mt-12 py-8 md:py-12 px-4 md:px-6 bg-white shadow-lg rounded-t-3xl">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Event Details -->
        <div>
          <h2 class="text-2xl md:text-3xl font-bold">Event Details</h2>
          <p class="mt-4 text-sm md:text-base">📅 Date: 14-16 May 2025</p>
          <p class="text-sm md:text-base">📍 Location: Jakarta Convention Center</p>
          <p class="text-sm md:text-base">⏰ Time: 10:00 AM - 6:00 PM</p>
        </div>

        <!-- RSVP Form -->
        <div>
          <h2 class="text-xl md:text-2xl font-bold">Confirm Your Attendance</h2>

          <form action="{{ route('admin.updateInvitation', $guest->slug) }}" method="POST" class="mt-4">
            @csrf

            @if($guest->attendance == 0)
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block">
                    <span class="text-gray-700 text-sm">Your Name</span>
                    <input type="text" name="name" class="mt-2 p-2 w-full border rounded text-sm" required readonly value="{{ $guest->name ?? '' }}">
                  </label>
                </div>

                <div>
                  <label class="block">
                    <span class="text-gray-700 text-sm">Will you be attending?</span>
                    <select name="attendance" class="mt-2 p-2 w-full border rounded text-sm" required>
                      <option value="">Choose your presence</option>
                      <option value="1">Yes, I'll be there</option>
                      <option value="0">No, I can't make it</option>
                    </select>
                  </label>
                </div>
              </div>

              <div class="mt-4 text-center md:text-left">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded text-sm md:text-base">RSVP</button>
              </div>
            @else
              <p class="mt-4 text-green-600 text-sm md:text-base">Thank you for confirming your attendance!</p>
              <p class="mt-2 text-sm md:text-base">We look forward to seeing you at the event!</p>
            @endif
          </form>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="flex-shrink-0 bg-gray-900 text-white py-6 text-center text-sm md:text-base">
    <p class="text-base md:text-lg">Visit our booth at ICI 2025</p>
    <p class="mt-4">&copy; 2025 LSFRAGRANCE. All Rights Reserved.</p>
  </footer>

</body>
</html>