<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>ReSURP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
<div class="min-h-full">
  <nav class="bg-emerald-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0">
                <img src="https://up.edu.ph/wp-content/uploads/2020/01/UP-Seal.png" alt="Your Company" class="size-16" />
                </div>
          <div class="shrink-0">
                <img src="https://www.surp.upd.edu.ph/_img/SURP%20Logo.png" alt="Your Company" class="size-16" />
                </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white" -->
                                  @if (Route::has('login'))
                            <livewire:welcome.navigation />
                    @endif
            </div>
          </div>
        </div>
        <div class="hidden md:block">
  </nav>
  <header class="relative bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">ReSURP: A Collaborative Space Reservation System</h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Your content -->
        <div class="py-16 bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-10">Latest Anonymous Feedback</h2>

                            @if ($latestFeedback->isEmpty())
                                <p class="text-center text-gray-600 dark:text-gray-400">No feedback submitted yet.</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    @foreach ($latestFeedback as $feedback)
                                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-indigo-500">
                                            <blockquote class="text-gray-700 dark:text-gray-300 italic mb-4">
                                                "{{ $feedback->user_feedback }}"
                                            </blockquote>
                                            <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                                                — Anonymous User ({{ $feedback->created_at->format('M d, Y') }})
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
    </div>
  </main>
</div>
</body>
</html>
