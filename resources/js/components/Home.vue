<template>
    <div class="container mx-auto p-6">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-blue-600">JobPortal</h1>
            <div v-if="isLoggedIn" class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, {{ user.name }}</span>
                <button
                    @click="logout"
                    class="text-sm text-blue-600 hover:underline"
                >
                    Logout
                </button>
            </div>
            <div v-else class="space-x-4">
                <button
                    @click="showLoginModal = true"
                    class="text-blue-600 hover:underline"
                >
                    Login
                </button>
                <button
                    @click="showRegisterModal = true"
                    class="bg-blue-600 text-white py-1 px-4 rounded hover:bg-blue-700"
                >
                    Register
                </button>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="text-center my-10">
            <h1 class="text-4xl font-bold text-blue-600">
                Find Your Dream Job
            </h1>
            <p class="text-gray-600 mt-2">
                Browse and apply for jobs or post a job opening.
            </p>
        </section>

        <!-- Job Listings -->
        <div>
            <!-- Optional search and filter UI -->
            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search jobs..."
                    class="p-2 border rounded flex-grow"
                />
                <select v-model="selectedCategory" class="p-2 border rounded">
                    <option value="">All Categories</option>
                    <option value="Development">Development</option>
                    <option value="Design">Design</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Sales">Sales</option>
                    <option value="Management">Management</option>
                </select>
            </div>

            <!-- Loading state -->
            <div v-if="loading" class="flex justify-center py-10">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"
                ></div>
            </div>

            <!-- Error state -->
            <div
                v-else-if="error"
                class="p-4 border rounded-lg bg-red-50 text-red-600"
            >
                {{ error }}
                <button @click="fetchJobs" class="ml-4 underline">
                    Try Again
                </button>
            </div>

            <!-- Empty state -->
            <div
                v-else-if="filteredJobs.length === 0"
                class="p-8 text-center text-gray-500"
            >
                <p>No jobs found matching your criteria.</p>
            </div>

            <!-- Jobs grid -->
            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                <div
                    v-for="job in filteredJobs"
                    :key="job.id"
                    class="p-6 border rounded-lg shadow-lg"
                >
                    <h3 class="text-lg font-bold">{{ job.title }}</h3>
                    <p class="text-gray-600">{{ job.company }}</p>
                    <p class="text-sm text-gray-500 mt-2">{{ job.location }}</p>
                    <p v-if="job.category" class="mt-2">
                        <span
                            class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800"
                        >
                            {{ job.category }}
                        </span>
                    </p>
                    <p class="mt-2 text-sm text-gray-700 line-clamp-2">
                        {{
                            job.description
                                ? truncateText(job.description, 100)
                                : ""
                        }}
                    </p>
                    <div class="mt-4 flex space-x-2">
                        <button
                            @click="viewJobDetails(job)"
                            class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300"
                        >
                            View Details
                        </button>
                        <button
                            @click="handleApplyClick(job)"
                            class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
                        >
                            Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Post a Job Section -->
        <section class="mt-16 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Post a Job</h2>
            <form v-if="isLoggedIn" @submit.prevent="postJob">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <input
                        v-model="newJob.title"
                        type="text"
                        placeholder="Job Title"
                        class="p-2 border rounded"
                        required
                    />
                    <input
                        v-model="newJob.company"
                        type="text"
                        placeholder="Company Name"
                        class="p-2 border rounded"
                        required
                    />
                    <input
                        v-model="newJob.location"
                        type="text"
                        placeholder="Location"
                        class="p-2 border rounded"
                        required
                    />
                    <select
                        v-model="newJob.category"
                        class="p-2 border rounded"
                        required
                    >
                        <option value="" disabled>Select Category</option>
                        <option value="Development">Development</option>
                        <option value="Design">Design</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Management">Management</option>
                    </select>
                </div>
                <textarea
                    v-model="newJob.description"
                    placeholder="Job Description"
                    class="w-full p-2 border rounded mb-4 h-32"
                    required
                ></textarea>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <input
                        v-model="newJob.salary"
                        type="text"
                        placeholder="Salary Range (optional)"
                        class="p-2 border rounded"
                    />
                    <input
                        v-model="newJob.employmentType"
                        type="text"
                        placeholder="Employment Type (e.g., Full-time)"
                        class="p-2 border rounded"
                    />
                </div>
                <button
                    type="submit"
                    class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700"
                >
                    Post Job
                </button>
            </form>
            <div v-else class="text-center py-4">
                <p class="text-gray-700 mb-2">
                    You need to sign in to post a job
                </p>
                <button
                    @click="showLoginModal = true"
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
                >
                    Sign In to Post a Job
                </button>
            </div>
        </section>

        <!-- Login Modal -->
        <div
            v-if="showLoginModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Log In</h2>
                    <button
                        @click="showLoginModal = false"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="login">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-1"
                            >Email</label
                        >
                        <input
                            id="email"
                            v-model="loginForm.email"
                            type="email"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-1"
                            >Password</label
                        >
                        <input
                            id="password"
                            v-model="loginForm.password"
                            type="password"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
                    >
                        Log In
                    </button>
                    <p v-if="authError" class="text-red-500 mt-2">
                        {{ authError }}
                    </p>
                </form>
                <div class="mt-4 text-center">
                    <p class="text-gray-600">
                        Don't have an account?
                        <button
                            @click="switchToRegister"
                            class="text-blue-600 hover:underline"
                        >
                            Register
                        </button>
                    </p>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div
            v-if="showRegisterModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Create Account</h2>
                    <button
                        @click="showRegisterModal = false"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="registerUser">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-1"
                            >Name</label
                        >
                        <input
                            id="name"
                            v-model="registerForm.name"
                            type="text"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label for="reg-email" class="block text-gray-700 mb-1"
                            >Email</label
                        >
                        <input
                            id="reg-email"
                            v-model="registerForm.email"
                            type="email"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            for="reg-password"
                            class="block text-gray-700 mb-1"
                            >Password</label
                        >
                        <input
                            id="reg-password"
                            v-model="registerForm.password"
                            type="password"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            for="confirm-password"
                            class="block text-gray-700 mb-1"
                            >Confirm Password</label
                        >
                        <input
                            id="confirm-password"
                            v-model="registerForm.confirmPassword"
                            type="password"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
                    >
                        Register
                    </button>
                    <p v-if="authError" class="text-red-500 mt-2">
                        {{ authError }}
                    </p>
                </form>
                <div class="mt-4 text-center">
                    <p class="text-gray-600">
                        Already have an account?
                        <button
                            @click="switchToLogin"
                            class="text-blue-600 hover:underline"
                        >
                            Log In
                        </button>
                    </p>
                </div>
            </div>
        </div>

        <!-- Apply Job Modal -->
        <div
            v-if="showApplyModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">
                        Apply for {{ selectedJob.title }}
                    </h2>
                    <button
                        @click="showApplyModal = false"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitApplication">
                    <div class="mb-4">
                        <label
                            for="cover-letter"
                            class="block text-gray-700 mb-1"
                            >Cover Letter</label
                        >
                        <textarea
                            id="cover-letter"
                            v-model="application.coverLetter"
                            class="w-full p-2 border rounded h-32"
                            required
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="resume" class="block text-gray-700 mb-1"
                            >Resume URL (link to your PDF or Word
                            document)</label
                        >
                        <input
                            type="url"
                            id="resumeUrl"
                            v-model="application.resumeUrl"
                            placeholder="https://example.com/my-resume.pdf"
                            required
                        />
                        <small class="form-text">
                            Please provide a direct link to your resume (PDF or
                            Word document)
                        </small>
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
                    >
                        Submit Application
                    </button>
                </form>
            </div>
        </div>

        <!-- Job Details Modal -->
        <div
            v-if="showJobDetailsModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl max-h-screen overflow-y-auto"
            >
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Job Details</h2>
                    <button
                        @click="showJobDetailsModal = false"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-blue-600">
                        {{ selectedJob.title }}
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="text-lg text-gray-700">{{
                            selectedJob.company
                        }}</span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-gray-500">{{
                            selectedJob.location
                        }}</span>
                    </div>
                    <div class="mt-2 flex items-center">
                        <span
                            v-if="selectedJob.category"
                            class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800 mr-2"
                        >
                            {{ selectedJob.category }}
                        </span>
                        <span
                            v-if="selectedJob.employmentType"
                            class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800"
                        >
                            {{ selectedJob.employmentType }}
                        </span>
                    </div>
                    <div v-if="selectedJob.salary" class="mt-2">
                        <span class="font-semibold">Salary Range:</span>
                        {{ selectedJob.salary }}
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-2">Job Description</h4>
                    <div class="text-gray-700 whitespace-pre-line">
                        {{ selectedJob.description }}
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        @click="showJobDetailsModal = false"
                        class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 mr-2"
                    >
                        Close
                    </button>
                    <button
                        @click="applyFromDetails(job)"
                        class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
                    >
                        Apply Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
const jobs = ref([]);
const loading = ref(true);
const error = ref(null);

// Search and filter state (if needed)
const searchQuery = ref("");
const selectedCategory = ref("");

// Fetch jobs from API
const fetchJobs = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await fetch("http://127.0.0.1:8000/api/fetch-jobs");
        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        const data = await response.json();
        if (Array.isArray(data)) {
            jobs.value = data;
        } else if (data && typeof data === "object") {
            jobs.value = Array.isArray(data.data) ? data.data : [];
            console.log(
                "API returned object instead of array, extracted:",
                jobs.value
            );
        } else {
            console.error("API returned unexpected format:", data);
            jobs.value = [];
            error.value = "Received invalid data format from server";
        }

        loading.value = false;
        localStorage.setItem("fetchedJobs", JSON.stringify(data));
    } catch (err) {
        console.error("Error fetching jobs:", err);
        error.value = "Failed to load jobs. Please try again later.";
        loading.value = false;
        jobs.value = [];
    }
};

// Filter jobs based on search and category filters
const filteredJobs = computed(() => {
    let result = jobs.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(
            (job) =>
                job.title.toLowerCase().includes(query) ||
                job.company.toLowerCase().includes(query) ||
                job.description.toLowerCase().includes(query)
        );
    }

    if (selectedCategory.value) {
        result = result.filter(
            (job) => job.category === selectedCategory.value
        );
    }
    return result;
});

onMounted(() => {
    fetchJobs();
});

// Authentication state
const isLoggedIn = ref(false);
const user = ref({});
const authError = ref("");

// Modal states
const showLoginModal = ref(false);
const showRegisterModal = ref(false);
const showApplyModal = ref(false);
const showJobDetailsModal = ref(false);
const selectedJob = ref({});

// Search and filter states
const categoryFilter = ref("");
const locationFilter = ref("");

// Form data
const loginForm = ref({ email: "", password: "" });
const registerForm = ref({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
});
const application = ref({ coverLetter: "", resumeFile: null });

// Function to filter jobs based on search and filters
const filterJobs = () => {
    const query = searchQuery.value.trim().toLowerCase();
    const category = categoryFilter.value.trim().toLowerCase();
    const location = locationFilter.value.trim().toLowerCase();

    filteredJobs.value = jobs.value.filter((job) => {
        const jobTitle = job.title?.toLowerCase() || "";
        const jobCompany = job.company?.toLowerCase() || "";
        const jobLocation = job.location?.toLowerCase() || "";
        const jobCategory = job.category?.toLowerCase() || "";

        // Search query filter
        const matchesQuery =
            !query ||
            jobTitle.includes(query) ||
            jobCompany.includes(query) ||
            jobLocation.includes(query);
        const matchesCategory = !category || jobCategory === category;
        // Location filter (exact match)
        const matchesLocation = !location || jobLocation === location;
        return matchesQuery && matchesCategory && matchesLocation;
    });
};

watch([jobs, searchQuery, categoryFilter, locationFilter], filterJobs, {
    deep: true,
});

filterJobs();
// Watch for changes in jobs array to update filtered jobs
watch(jobs, filterJobs, { deep: true });
const truncateText = (text, maxLength) => {
    if (!text) return "";
    if (text.length <= maxLength) return text;
    return text.slice(0, maxLength) + "...";
};

// Function to view job details
const viewJobDetails = (job) => {
    selectedJob.value = job;
    showJobDetailsModal.value = true;
};

// Function to apply from details page
const applyFromDetails = (job) => {
    // Store the selected job in local state
    selectedJob.value = job;

    // Store the job_id in localStorage for persistence
    localStorage.setItem("selectedJobId", job.id);

    // Close the job details modal
    showJobDetailsModal.value = false;

    // Call the application handler
    handleApplyClick(selectedJob.value);
};

// Function to handle job application
const handleApplyClick = (job) => {
    // Update selected job and ensure we have the job_id
    selectedJob.value = job;
    const jobId = job.id;

    // Store the job_id in localStorage as a backup
    localStorage.setItem("selectedJobId", jobId);

    if (!isLoggedIn.value) {
        // Show login modal if user is not logged in
        showLoginModal.value = true;

        // Store the intent to apply after login with the job_id
        localStorage.setItem(
            "pendingAction",
            JSON.stringify({ action: "apply", jobId: jobId })
        );
    } else {
        // User is logged in, show application modal
        // Make sure the job_id is available for the form submission
        localStorage.setItem("currentApplicationJobId", jobId);
        showApplyModal.value = true;
    }
};

// Function to handle file upload
const handleFileUpload = (event) => {
    application.value.resumeFile = event.target.files[0];
};
//submit Application
const submitApplication = async () => {
    const user = JSON.parse(localStorage.getItem("user"));

    if (!user || !user.id) {
        alert("You must be logged in to apply.");
        return;
    }


    // Debug: Check what's in selectedJob
    console.log("Selected Job:", selectedJob.value);

    // Get job ID from selectedJob
    let jobId = selectedJob.value?.id;
    console.log("Initial jobId from selectedJob:", jobId);

    // If no job ID found, try localStorage
    if (!jobId) {
        jobId = localStorage.getItem("selectedJobId");
        console.log("JobId from localStorage:", jobId);
    }

    // Ensure jobId is a number, not a string "undefined"
    if (!jobId || jobId === "undefined" || jobId === undefined) {
        alert("Error: No job selected!");
        return;
    }

    // Convert to number if it's stored as a string
    jobId = Number(jobId) || null;

    // Final check
    if (!jobId) {
        alert("Error: Invalid job ID!");
        return;
    }

    console.log("Final jobId to be used:", jobId);

    if (!application.value.resumeUrl) {
        alert("Please upload a resume before applying.");
        return;
    }

    const token = localStorage.getItem("token");
    if (!token) {
        alert("No token found. Please log in.");
        return;
    }

    try {
        const payload = {
            job_id: jobId,
            user_id: user.id,
            cover_letter: application.value.coverLetter,
            resume_url: application.value.resumeUrl,
        };
        console.log("Sending payload:", payload);

        const response = await axios.post(
            "http://localhost:8000/api/applications",
            payload,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            }
        );

        if (response.status === 201) {
            alert("Application submitted successfully!");
            showApplyModal.value = false;

            // Reset form
            application.value = { coverLetter: "", resumeUrl: "" };

            // Clean up localStorage after successful submission
            localStorage.removeItem("selectedJobId");
        } else {
            alert("Something went wrong. Please try again.");
        }
    } catch (error) {
        console.error(
            "Error submitting application:",
            error.response?.data || error.message
        );
        alert(
            "Failed to submit application. Please check your inputs and try again."
        );
    }
};

import { reactive } from "vue";
// const isLoggedIn = ref(true);
const newJob = reactive({
    title: "",
    company: "",
    location: "",
    category: "",
    description: "",
    salary: "",
    employmentType: "",
});

// Form submission function
const postJob = () => {
    const userDataString = localStorage.getItem("user");

    if (!userDataString) {
        alert("User not logged in. Please log in again.");
        return;
    }

    try {
        // Parse the JSON string to get the user object
        const userData = JSON.parse(userDataString);

        if (!userData.id) {
            alert("User ID not found. Please log in again.");
            return;
        }

        // Format the data according to your API requirements
        const jobData = {
            title: newJob.title,
            description: newJob.description,
            company: newJob.company,
            location: newJob.location,
            salary: newJob.salary ? parseFloat(newJob.salary) : null,
            type: newJob.employmentType.toLowerCase(),
            status: "active",
            category: newJob.category,
            user_id: userData.id.toString(),
        };

        // POST JOB
        fetch("http://127.0.0.1:8000/api/jobs", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(jobData),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Job posted successfully:", data);
                resetForm();
                alert("Job posted successfully!");
            });
    } catch (error) {
        console.error("Error parsing user data:", error);
        alert("Error accessing user information. Please log in again.");
    }
    const { fetchedJobs } = response.data;
    // Store user data & token in localStorage
    localStorage.setItem("fetchedJobs", JSON.stringify(fetchedJobs));
};
const resetForm = () => {
    newJob.title = "";
    newJob.company = "";
    newJob.location = "";
    newJob.category = "";
    newJob.description = "";
    newJob.salary = "";
    newJob.employmentType = "";
};

const login = async () => {
    authError.value = "";

    if (!loginForm.value.email || !loginForm.value.password) {
        authError.value = "Please fill in all fields";
        return;
    }

    try {
        const response = await axios.post("http://localhost:8000/api/login", {
            email: loginForm.value.email,
            password: loginForm.value.password,
        });

        const { user, token } = response.data;
        localStorage.setItem("user", JSON.stringify(user));
        localStorage.setItem("token", token);

        isLoggedIn.value = true;
        user.value = user;
        showLoginModal.value = false;

        // Handle pending actions
        const pendingAction = localStorage.getItem("pendingAction");
        if (pendingAction) {
            const { action, jobId } = JSON.parse(pendingAction);
            if (action === "apply") {
                const job = jobs.value.find((j) => j.id === jobId);
                if (job) {
                    selectedJob.value = job;
                    showApplyModal.value = true;
                }
            }
            localStorage.removeItem("pendingAction");
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            authError.value = "Invalid email or password";
        } else {
            authError.value = "Something went wrong. Please try again.";
        }
    }
};

import axios from "axios";

const register = ref({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
});

const registerUser = async () => {
    try {
        // Send POST request to Laravel API
        const response = await axios.post(
            "http://localhost:8000/api/register",
            {
                name: registerForm.value.name,
                email: registerForm.value.email,
                password: registerForm.value.password,
                password_confirmation: registerForm.value.confirmPassword,
            }
        );

        alert("User registered successfully!");
        console.log(response.data);
    } catch (error) {
        if (error.response && error.response.data) {
            authError.value =
                error.response.data.message || "Registration failed!";
        } else {
            authError.value = "Something went wrong!";
        }
    }
};

const logout = () => {
    isLoggedIn.value = false;
    user.value = {};
};

// Modal navigation
const switchToLogin = () => {
    showRegisterModal.value = false;
    showLoginModal.value = true;
};

const switchToRegister = () => {
    showLoginModal.value = false;
    showRegisterModal.value = true;
};
</script>

<style scoped>
.container {
    max-width: 1200px;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
