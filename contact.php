<?php 
include 'includes/__header__.php';
include 'server/dbcon.php';

$success = $error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $message])) {
                $success = "Thank you! Your message has been sent successfully.";
                // Clear form data after successful submission
                $name = $email = $message = '';
            } else {
                $error = "Sorry, there was an error sending your message.";
            }
        } catch(PDOException $e) {
            $error = "Sorry, there was an error sending your message.";
        }
    }
}
?>

<main class="container mx-auto px-4 py-8 mt-24">
    <div class="max-w-4xl mx-auto">
        <!-- Background gradient effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20 blur-3xl opacity-30"></div>
        
        <div class="relative bg-gray-800/90 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border border-gray-700 p-8 transform hover:scale-[1.01] transition-transform duration-300">
            <!-- Success Message -->
            <?php if ($success): ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg">
                <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if ($error): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Left Column -->
                <div class="space-y-8">
                    <!-- Content remains the same -->
                    <div class="space-y-4">
                        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text">
                            Get in Touch
                        </h1>
                        <p class="text-gray-400 leading-relaxed">
                            Have a question or want to collaborate? Feel free to reach out. I'll get back to you as soon as possible.
                        </p>
                    </div>

                    <!-- Social Links remain the same -->
                    <div class="flex flex-wrap gap-4 pt-6">
                        <!-- Social links code remains the same -->
                    </div>
                </div>

                <!-- Right Column - Form -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6 backdrop-blur-sm bg-gray-900/30 p-6 rounded-xl border border-gray-700/50">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                            <input type="text" id="name" name="name" required
                                value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700/50 border border-gray-600 text-gray-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 transition-all duration-300 placeholder-gray-400"
                                placeholder="Your name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700/50 border border-gray-600 text-gray-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 transition-all duration-300 placeholder-gray-400"
                                placeholder="your@email.com">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full px-4 py-3 rounded-lg bg-gray-700/50 border border-gray-600 text-gray-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 transition-all duration-300 placeholder-gray-400 resize-none"
                                placeholder="Your message here..."><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 font-medium text-sm flex items-center justify-center space-x-2 shadow-lg">
                        <span>Send Message</span>
                        <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/__footer__.php'; ?>