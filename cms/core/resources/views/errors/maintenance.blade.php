<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance - {{ function_exists('cms_option') ? cms_option('site_name', config('app.name', 'Laravel')) : config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">
    <div class="max-w-xl w-full mx-auto p-8 md:p-12 bg-white shadow-xl shadow-gray-200/50 rounded-3xl text-center border border-gray-100">
        <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-8 relative">
            <div class="absolute inset-0 rounded-full border-4 border-orange-50 animate-ping opacity-75"></div>
            <svg class="w-12 h-12 text-orange-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">We'll be back soon!</h1>
        <p class="text-gray-500 mb-8 text-lg md:text-xl leading-relaxed max-w-md mx-auto">
            Sorry for the inconvenience, but we're performing some maintenance at the moment. We'll be back online shortly!
        </p>
        <div class="inline-block bg-gray-50 rounded-xl px-6 py-4 border border-gray-100 text-sm font-semibold text-gray-700 shadow-inner">
            &mdash; The {{ function_exists('cms_option') ? cms_option('site_name', config('app.name', 'Laravel')) : config('app.name', 'Laravel') }} Team
        </div>
    </div>
</body>
</html>
