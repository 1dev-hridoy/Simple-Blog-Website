<?php include '../includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Edit Profile</h3>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form action="save-profile.php" method="POST" enctype="multipart/form-data">
                <div class="flex flex-col items-center text-center mb-6">
                    <img id="profile_image_preview" src="path/to/profile/picture.jpg" class="w-32 h-32 rounded-full shadow mb-4" alt="Profile Picture">
                    <input type="file" id="profile_image" name="profile_image" class="hidden" onchange="previewImage(event, 'profile_image_preview')">
                    <label for="profile_image" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Change Profile Picture
                    </label>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="John Doe" required>
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="Web Developer & Technical Writer" required>
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Bio:</label>
                    <textarea id="bio" name="bio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required>I'm a passionate web developer with over 5 years of experience in creating modern web applications. I love sharing my knowledge through writing and helping others learn about web development.</textarea>
                </div>
                <div class="mb-4">
                    <label for="skills" class="block text-gray-700 text-sm font-bold mb-2">Skills & Expertise:</label>
                    <textarea id="skills" name="skills" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" required>Frontend, Backend, UI/UX, DevOps</textarea>
                </div>
                <div class="mb-4">
                    <label for="experience" class="block text-gray-700 text-sm font-bold mb-2">Experience:</label>
                    <textarea id="experience" name="experience" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required>Senior Web Developer
Tech Company - 2020-Present

Leading the frontend development team and implementing modern web solutions.

Web Developer
Digital Agency - 2018-2020

Developed and maintained multiple client websites and web applications.</textarea>
                </div>
                <div class="mb-4">
                    <label for="interests" class="block text-gray-700 text-sm font-bold mb-2">Interests:</label>
                    <textarea id="interests" name="interests" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" required>Web Development, Technical Writing, Open Source, UI Design</textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function previewImage(event, previewId) {
    const reader = new FileReader();
    const preview = document.getElementById(previewId);
    reader.onload = function() {
        if (reader.readyState === 2) {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include '../includes/__footer__.php'; ?>