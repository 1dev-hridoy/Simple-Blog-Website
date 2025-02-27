<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Blog</title>
    <meta name="description" content="Welcome to Personal Blog - a place where ideas, stories, and knowledge come to life. Explore insightful articles on technology, lifestyle, and more.">
    <meta name="keywords" content="personal blog, technology, lifestyle, travel, insights, stories, knowledge">
    <meta name="author" content="Your Name">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Personal Blog - Insights, Stories & Thoughts">
    <meta property="og:description" content="Explore articles on technology, lifestyle, travel, and more.">
    <meta property="og:image" content="https://yourwebsite.com/og-image.jpg">
    <meta property="og:url" content="https://yourwebsite.com">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Personal Blog - Insights, Stories & Thoughts">
    <meta name="twitter:description" content="Explore articles on technology, lifestyle, travel, and more.">
    <meta name="twitter:image" content="https://yourwebsite.com/twitter-image.jpg">
    <link rel="canonical" href="https://yourwebsite.com">
    <link rel="icon" href="https://yourwebsite.com/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': {
                                transform: 'translate(0px, 0px) scale(1)',
                            },
                            '33%': {
                                transform: 'translate(30px, -50px) scale(1.1)',
                            },
                            '66%': {
                                transform: 'translate(-20px, 20px) scale(0.9)',
                            },
                            '100%': {
                                transform: 'translate(0px, 0px) scale(1)',
                            },
                        },
                    },
                },
            },
        }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Blog",
        "name": "Personal Blog",
        "url": "https://yourwebsite.com",
        "author": {
            "@type": "Person",
            "name": "Your Name"
        },
        "description": "Explore articles on technology, lifestyle, travel, and more.",
        "image": "https://yourwebsite.com/og-image.jpg"
    }
    </script>
</head>

</head>
<body class="bg-gray-900 text-gray-100">
    <nav class="fixed top-0 w-full bg-gray-800/95 backdrop-blur-sm z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold">Blog<span class="text-blue-500">.</span></div>
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="hover:text-blue-500 transition-colors">Home</a>
                    <a href="#" class="hover:text-blue-500 transition-colors">Blog</a>
                    <a href="#" class="hover:text-blue-500 transition-colors">About</a>
                    <a href="#" class="hover:text-blue-500 transition-colors">Contact</a>
                </div>
                <button class="md:hidden" id="mobile-menu-button" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div class="md:hidden" id="mobile-menu">
                <div class="hidden px-2 pt-2 pb-3 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-md hover:bg-gray-700 hover:text-blue-500 transition-colors">Home</a>
                    <a href="#" class="block px-3 py-2 rounded-md hover:bg-gray-700 hover:text-blue-500 transition-colors">Blog</a>
                    <a href="#" class="block px-3 py-2 rounded-md hover:bg-gray-700 hover:text-blue-500 transition-colors">About</a>
                    <a href="#" class="block px-3 py-2 rounded-md hover:bg-gray-700 hover:text-blue-500 transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </nav>