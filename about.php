<?php 
include 'includes/__header__.php'; 
include 'server/dbcon.php';

$current_datetime = "2025-03-01 18:25:48";
$current_user = "hridoy09bg";

try {
    $stmt = $pdo->query("SELECT * FROM bio LIMIT 1");
    $profile_data = $stmt->fetch();
} catch(PDOException $e) {
    $profile_data = null;
}
?>

<main class="container mx-auto px-4 py-8 mt-24">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700 p-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-12">
                <img src="<?php echo isset($profile_data['image_url']) ? './admin/' . htmlspecialchars($profile_data['image_url']) : 'assets/img/default-profile.jpg'; ?>" 
                     alt="Profile Picture" 
                     class="w-48 h-48 rounded-full object-cover border-4 border-blue-500">
                <div>
                    <h1 class="text-4xl font-bold mb-4">
                        <?php echo isset($profile_data['name']) ? htmlspecialchars($profile_data['name']) : ''; ?>
                    </h1>
                    <p class="text-gray-300 leading-relaxed">
                        <?php echo isset($profile_data['bio']) ? htmlspecialchars($profile_data['bio']) : ''; ?>
                    </p>
                </div>
            </div>

            <?php if(isset($profile_data['skills']) && !empty($profile_data['skills'])): ?>
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Skills & Expertise</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php 
                    $skills = array_map('trim', explode(',', $profile_data['skills']));
                    foreach($skills as $skill): 
                        if(!empty($skill)):
                    ?>
                        <div class="bg-gray-700 rounded-lg p-4 text-center">
                            <span class="text-blue-400"><?php echo htmlspecialchars($skill); ?></span>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($profile_data['experience']) && !empty($profile_data['experience'])): ?>
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Experience</h2>
                <div class="space-y-6">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($profile_data['experience'])); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($profile_data['interests']) && !empty($profile_data['interests'])): ?>
            <div>
                <h2 class="text-2xl font-bold mb-6">Interests</h2>
                <div class="flex flex-wrap gap-3">
                    <?php 
                    $interests = array_map('trim', explode(',', $profile_data['interests']));
                    foreach($interests as $interest): 
                        if(!empty($interest)):
                    ?>
                        <span class="px-4 py-2 bg-gray-700 rounded-full text-sm text-gray-300">
                            <?php echo htmlspecialchars($interest); ?>
                        </span>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/__footer__.php'; ?>