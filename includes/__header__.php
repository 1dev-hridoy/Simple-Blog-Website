<?php
$current_datetime = "2025-03-01 19:15:06";
$current_user = "hridoy09bg";

include 'server/dbcon.php';

try {
    $stmt = $pdo->query("SELECT * FROM seo LIMIT 1");
    $seo_data = $stmt->fetch();
} catch(PDOException $e) {
    $seo_data = null;
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo isset($seo_data['title']) ? htmlspecialchars($seo_data['title']) : 'Personal Blog'; ?></title>
    
    <meta name="description" content="<?php echo isset($seo_data['description']) ? htmlspecialchars($seo_data['description']) : 'Welcome to Personal Blog'; ?>">
    <meta name="keywords" content="<?php echo isset($seo_data['keywords']) ? htmlspecialchars($seo_data['keywords']) : ''; ?>">
    <meta name="author" content="<?php echo isset($seo_data['author']) ? htmlspecialchars($seo_data['author']) : ''; ?>">
    <meta name="robots" content="index, follow">
    
    <meta property="og:title" content="<?php echo isset($seo_data['og_title']) ? htmlspecialchars($seo_data['og_title']) : ''; ?>">
    <meta property="og:description" content="<?php echo isset($seo_data['og_description']) ? htmlspecialchars($seo_data['og_description']) : ''; ?>">
    <meta property="og:image" content="<?php echo isset($seo_data['og_image']) ? './admin/' . htmlspecialchars($seo_data['og_image']) : ''; ?>">
    <meta property="og:url" content="<?php echo isset($seo_data['og_url']) ? htmlspecialchars($seo_data['og_url']) : ''; ?>">
    <meta property="og:type" content="website">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($seo_data['twitter_title']) ? htmlspecialchars($seo_data['twitter_title']) : ''; ?>">
    <meta name="twitter:description" content="<?php echo isset($seo_data['twitter_description']) ? htmlspecialchars($seo_data['twitter_description']) : ''; ?>">
    <meta name="twitter:image" content="<?php echo isset($seo_data['twitter_image']) ? './admin/' . htmlspecialchars($seo_data['twitter_image']) : ''; ?>">
    
    <link rel="canonical" href="<?php echo isset($seo_data['canonical_url']) ? htmlspecialchars($seo_data['canonical_url']) : ''; ?>">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
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
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' }
                        }
                    }
                }
            }
        }
    </script>
    
    <script type="application/ld+json">
    <?php echo isset($seo_data['schema_data']) ? $seo_data['schema_data'] : '{}'; ?>
    </script>
</head>
<body>
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