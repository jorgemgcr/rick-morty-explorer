<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\TranslationHelper::t('title') }} Explorer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-8px) scale(1.02); }
        .skeleton { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .toast { transform: translateX(100%); transition: transform 0.3s ease-in-out; }
        .toast.show { transform: translateX(0); }
        .modal-backdrop { backdrop-filter: blur(8px); }
        
        /* Language Selector Styles */
        .language-selector {
            position: relative;
        }
        
        .language-dropdown {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .language-option {
            position: relative;
            overflow: hidden;
        }
        
        .language-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        
        .language-option:hover::before {
            left: 100%;
        }
        
        .flag-emoji {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 shadow-2xl">
                <i class="fas fa-rocket text-3xl text-white"></i>
            </div>
            <h1 class="text-6xl font-black text-white mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                {{ \App\Helpers\TranslationHelper::t('title') }}
            </h1>
            <p class="text-xl text-gray-300 font-light">{{ \App\Helpers\TranslationHelper::t('subtitle') }}</p>
            <div class="mt-6 flex justify-center items-center space-x-4">
                <div class="flex items-center text-gray-400">
                    <i class="fas fa-users mr-2"></i>
                    <span id="totalCharacters">0</span>&nbsp;{{ \App\Helpers\TranslationHelper::t('characters_count') }}
                </div>
                <div class="flex items-center text-gray-400">
                    <i class="fas fa-globe mr-2"></i>
                    <span>{{ \App\Helpers\TranslationHelper::t('multiverse') }}</span>
                </div>
                <!-- Language Selector -->
                <div class="relative group language-selector">
                    <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-lg border border-white/30 rounded-xl px-4 py-2 hover:bg-white/20 transition-all duration-300 cursor-pointer shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-language text-blue-400"></i>
                        <span class="text-white font-medium">{{ \App\Helpers\TranslationHelper::getLanguage() === 'es' ? 'Español' : 'English' }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform duration-300 group-hover:rotate-180"></i>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div id="languageDropdown" class="absolute top-full right-0 mt-2 w-48 language-dropdown bg-white/10 backdrop-blur-lg border border-white/30 rounded-xl shadow-2xl opacity-0 invisible transform translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 z-50">
                        <div class="py-2">
                            <button 
                                onclick="changeLanguage('es')" 
                                class="w-full flex items-center space-x-3 px-4 py-3 text-left text-white hover:bg-white/20 transition-colors duration-200 language-option {{ \App\Helpers\TranslationHelper::getLanguage() === 'es' ? 'bg-blue-500/30' : '' }}"
                            >
                                <span class="text-2xl flag-emoji">🇪🇸</span>
                                <div>
                                    <div class="font-medium">Español</div>
                                    <div class="text-xs text-gray-300">Spanish</div>
                                </div>
                                @if(\App\Helpers\TranslationHelper::getLanguage() === 'es')
                                    <i class="fas fa-check text-blue-400 ml-auto"></i>
                                @endif
                            </button>
                            
                            <div class="border-t border-white/20 my-1"></div>
                            
                            <button 
                                onclick="changeLanguage('en')" 
                                class="w-full flex items-center space-x-3 px-4 py-3 text-left text-white hover:bg-white/20 transition-colors duration-200 language-option {{ \App\Helpers\TranslationHelper::getLanguage() === 'en' ? 'bg-blue-500/30' : '' }}"
                            >
                                <span class="text-2xl flag-emoji">🇺🇸</span>
                                <div>
                                    <div class="font-medium">English</div>
                                    <div class="text-xs text-gray-300">Inglés</div>
                                </div>
                                @if(\App\Helpers\TranslationHelper::getLanguage() === 'en')
                                    <i class="fas fa-check text-blue-400 ml-auto"></i>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-12 border border-white/20">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="{{ \App\Helpers\TranslationHelper::t('search_placeholder') }}" 
                        class="w-full pl-12 pr-4 py-4 bg-white/20 border border-white/30 rounded-xl text-white placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-transparent backdrop-blur-sm transition-all duration-300"
                    >
                </div>
                
                <!-- Status Filter -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-heartbeat text-gray-400"></i>
                    </div>
                    <select id="statusFilter" class="pl-12 pr-8 py-4 bg-white/20 border border-white/30 rounded-xl text-white focus:ring-2 focus:ring-blue-400 focus:border-transparent backdrop-blur-sm transition-all duration-300 appearance-none">
                        <option value="" class="bg-gray-800 text-white">{{ \App\Helpers\TranslationHelper::t('all_statuses') }}</option>
                        <option value="Alive" class="bg-gray-800 text-white">🟢 {{ \App\Helpers\TranslationHelper::t('alive') }}</option>
                        <option value="Dead" class="bg-gray-800 text-white">🔴 {{ \App\Helpers\TranslationHelper::t('dead') }}</option>
                        <option value="unknown" class="bg-gray-800 text-white">⚪ {{ \App\Helpers\TranslationHelper::t('unknown') }}</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Species Filter -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-dna text-gray-400"></i>
                    </div>
                    <select id="speciesFilter" class="pl-12 pr-8 py-4 bg-white/20 border border-white/30 rounded-xl text-white focus:ring-2 focus:ring-blue-400 focus:border-transparent backdrop-blur-sm transition-all duration-300 appearance-none">
                        <option value="" class="bg-gray-800 text-white">{{ \App\Helpers\TranslationHelper::t('all_species') }}</option>
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Fetch Button -->
                <button 
                    id="fetchButton" 
                    class="px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 focus:ring-2 focus:ring-blue-400 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center space-x-2"
                >
                    <i class="fas fa-download"></i>
                    <span>{{ \App\Helpers\TranslationHelper::t('fetch_characters') }}</span>
                </button>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="hidden text-center py-12">
            <div class="inline-flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-white/20 border-t-blue-400 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-rocket text-blue-400 text-xl animate-bounce"></i>
                    </div>
                </div>
                <p class="text-white text-lg font-medium">{{ \App\Helpers\TranslationHelper::t('exploring_multiverse') }}</p>
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-pink-400 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>

        <!-- Skeleton Loading -->
        <div id="skeletonLoading" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <!-- Skeleton cards will be generated here -->
        </div>

        <!-- Characters Grid -->
        <div id="charactersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
            <!-- Characters will be loaded here -->
        </div>

        <!-- Pagination -->
        <div id="pagination" class="flex justify-center items-center space-x-4 mb-8">
            <!-- Pagination buttons will be loaded here -->
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden bg-red-500/20 backdrop-blur-lg border border-red-400/50 text-red-200 px-6 py-4 rounded-xl mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <strong>{{ \App\Helpers\TranslationHelper::t('error_loading') }}:</strong> <span id="errorText"></span>
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2">
            <!-- Toast notifications will appear here -->
        </div>

        <!-- Character Detail Modal -->
        <div id="characterModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-black opacity-75"></div>
                </div>
                
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-10">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-gray-900" id="modalCharacterName">{{ \App\Helpers\TranslationHelper::t('character_details') }}</h3>
                            <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-shrink-0">
                                <img id="modalCharacterImage" src="" alt="" class="w-32 h-32 rounded-xl object-cover shadow-lg" id="modalCharacterImageFiltered">
                            </div>
                            
                            <div class="flex-1 space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">{{ \App\Helpers\TranslationHelper::t('status') }}</span>
                                        <p id="modalCharacterStatus" class="text-lg font-semibold text-gray-900">{{ \App\Helpers\TranslationHelper::t('loading_characters') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">{{ \App\Helpers\TranslationHelper::t('species') }}</span>
                                        <p id="modalCharacterSpecies" class="text-lg font-semibold text-gray-900">{{ \App\Helpers\TranslationHelper::t('loading_characters') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">{{ \App\Helpers\TranslationHelper::t('gender') }}</span>
                                        <p id="modalCharacterGender" class="text-lg font-semibold text-gray-900">{{ \App\Helpers\TranslationHelper::t('loading_characters') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">{{ \App\Helpers\TranslationHelper::t('type') }}</span>
                                        <p id="modalCharacterType" class="text-lg font-semibold text-gray-900">{{ \App\Helpers\TranslationHelper::t('loading_characters') }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ \App\Helpers\TranslationHelper::t('origin') }}</span>
                                    <p id="modalCharacterOrigin" class="text-lg font-semibold text-gray-900">{{ \App\Helpers\TranslationHelper::t('loading_characters') }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ \App\Helpers\TranslationHelper::t('location') }}</span>
                                    <p id="modalCharacterLocation" class="text-lg font-semibold text-gray-900">{{ \App\Helpers\TranslationHelper::t('loading_characters') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button id="closeModalBtn" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            {{ \App\Helpers\TranslationHelper::t('close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let currentSearch = '';
        let currentStatus = '';
        let currentSpecies = '';
        let currentLanguage = '{{ \App\Helpers\TranslationHelper::getLanguage() }}';
        let translations = {};
        
        // Load translations from server
        async function loadTranslations() {
            try {
                const response = await fetch('/language/translations');
                if (!response.ok) {
                    throw new Error('Failed to load translations');
                }
                translations = await response.json();
            } catch (error) {
                console.error('Error loading translations:', error);
                // Fallback translations
                translations = {
                    'Human': 'Human',
                    'Alien': 'Alien',
                    'unknown': 'Unknown',
                    'Alive': 'Alive',
                    'Dead': 'Dead',
                    'Male': 'Male',
                    'Female': 'Female'
                };
            }
        }
        
        // Translation function for dynamic values
        function translateValue(value) {
            if (!value) return value;
            return translations[value] || value;
        }
        
        // Translations will be loaded in DOMContentLoaded event

        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const speciesFilter = document.getElementById('speciesFilter');
        const fetchButton = document.getElementById('fetchButton');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const skeletonLoading = document.getElementById('skeletonLoading');
        const charactersGrid = document.getElementById('charactersGrid');
        const pagination = document.getElementById('pagination');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const totalCharacters = document.getElementById('totalCharacters');
        const characterModal = document.getElementById('characterModal');
        const closeModal = document.getElementById('closeModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const toastContainer = document.getElementById('toastContainer');

        // Event Listeners
        searchInput.addEventListener('input', debounce(handleSearch, 500));
        statusFilter.addEventListener('change', handleFilter);
        speciesFilter.addEventListener('change', handleFilter);
        fetchButton.addEventListener('click', handleFetch);
        
        // Language selector (no longer needed as we use onclick buttons)
        
        // Modal event listeners
        closeModal.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            closeCharacterModal();
        });
        
        closeModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            closeCharacterModal();
        });
        
        // Close modal when clicking outside
        characterModal.addEventListener('click', (e) => {
            if (e.target === characterModal) {
                closeCharacterModal();
            }
        });
        
        // Prevent modal content clicks from closing modal
        const modalContent = characterModal.querySelector('.relative');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Handle search
        function handleSearch() {
            currentSearch = searchInput.value;
            currentPage = 1;
            loadCharacters();
        }

        // Handle filters
        function handleFilter() {
            currentStatus = statusFilter.value;
            currentSpecies = speciesFilter.value;
            currentPage = 1;
            loadCharacters();
        }

        // Handle fetch
        async function handleFetch() {
            try {
                showLoading();
                const response = await fetch('/characters/fetch');
                const data = await response.json();
                
                if (data.success) {
                    showMessage(`${data.message}`, 'success');
                    // Ensure translations are loaded before loading characters
                    if (Object.keys(translations).length === 0) {
                        await loadTranslations();
                    }
                    loadCharacters();
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('{{ \App\Helpers\TranslationHelper::t('error_loading') }}');
            } finally {
                hideLoading();
            }
        }

        // Load characters
        async function loadCharacters() {
            try {
                showSkeletonLoading();
                
                // Ensure translations are loaded before loading characters
                if (Object.keys(translations).length === 0) {
                    await loadTranslations();
                }
                
                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: 12
                });
                
                if (currentSearch) params.append('search', currentSearch);
                if (currentStatus) params.append('status', currentStatus);
                if (currentSpecies) params.append('species', currentSpecies);

                const response = await fetch(`/characters/api?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    displayCharacters(data.data);
                    displayPagination(data.pagination);
                    updateTotalCharacters(data.pagination.total);
                    hideError();
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('{{ \App\Helpers\TranslationHelper::t('error_loading') }}');
            } finally {
                hideSkeletonLoading();
            }
        }

        // Load species for filter
        async function loadSpecies() {
            try {
                const response = await fetch('/characters/api?per_page=1000'); // Get all characters to extract species
                const data = await response.json();
                
                if (data.success) {
                    const species = [...new Set(data.data.map(char => char.species))].sort();
                    populateSpeciesFilter(species);
                }
            } catch (error) {
                console.error('Error loading species:', error);
            }
        }

        // Populate species filter
        function populateSpeciesFilter(species) {
            const speciesFilter = document.getElementById('speciesFilter');
            
            // Clear existing options except "All species"
            speciesFilter.innerHTML = '<option value="" class="bg-gray-800 text-white">{{ \App\Helpers\TranslationHelper::t('all_species') }}</option>';
            
            // Add species options
            species.forEach(specie => {
                const option = document.createElement('option');
                option.value = specie;
                option.className = 'bg-gray-800 text-white';
                
                // Add emoji based on species
                let emoji = '🧬'; // Default
                if (specie.toLowerCase().includes('human')) emoji = '👤';
                else if (specie.toLowerCase().includes('alien')) emoji = '👽';
                
                option.textContent = `${emoji} ${specie}`;
                speciesFilter.appendChild(option);
            });
        }

        // Display characters
        function displayCharacters(characters) {
            charactersGrid.innerHTML = characters.map(character => `
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden card-hover border border-white/20 group">
                    <div class="relative overflow-hidden">
                        <img 
                            src="${character.image_url}" 
                            alt="${character.name}"
                            class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500 ${character.status === 'Dead' ? 'grayscale' : ''}"
                            onerror="this.src='https://via.placeholder.com/400x300/1f2937/ffffff?text=No+Image'"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${
                                character.status === 'Alive' ? 'bg-green-500/90 text-white' :
                                character.status === 'Dead' ? 'bg-red-500/90 text-white' :
                                'bg-gray-500/90 text-white'
                            }">
                                ${character.status === 'Alive' ? '🟢' : character.status === 'Dead' ? '🔴' : '⚪'} ${translateValue(character.status)}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-blue-300 transition-colors">
                            ${character.name}
                        </h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-dna w-4 mr-3 text-blue-400"></i>
                                <span class="font-medium">{{ \App\Helpers\TranslationHelper::t('species') }}:</span>
                                <span class="ml-2 text-white">${translateValue(character.species)}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-venus-mars w-4 mr-3 text-pink-400"></i>
                                <span class="font-medium">{{ \App\Helpers\TranslationHelper::t('gender') }}:</span>
                                <span class="ml-2 text-white">${translateValue(character.gender)}</span>
                            </div>
                            
                            ${character.type ? `
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-tag w-4 mr-3 text-purple-400"></i>
                                <span class="font-medium">{{ \App\Helpers\TranslationHelper::t('type') }}:</span>
                                <span class="ml-2 text-white">${translateValue(character.type)}</span>
                            </div>
                            ` : ''}
                            
                            ${character.origin_name ? `
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-globe w-4 mr-3 text-green-400"></i>
                                <span class="font-medium">{{ \App\Helpers\TranslationHelper::t('origin') }}:</span>
                                <span class="ml-2 text-white truncate">${translateValue(character.origin_name)}</span>
                            </div>
                            ` : ''}
                            
                            ${character.location_name ? `
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-map-marker-alt w-4 mr-3 text-orange-400"></i>
                                <span class="font-medium">{{ \App\Helpers\TranslationHelper::t('location') }}:</span>
                                <span class="ml-2 text-white truncate">${translateValue(character.location_name)}</span>
                            </div>
                            ` : ''}
                        </div>
                        
                        <button 
                            onclick="viewCharacter(${character.id})"
                            class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-6 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-eye"></i>
                            <span>{{ \App\Helpers\TranslationHelper::t('view_details') }}</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Display pagination
        function displayPagination(paginationData) {
            const { current_page, last_page, total } = paginationData;
            
            if (last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';
            
            // Previous button
            if (current_page > 1) {
                paginationHTML += `
                    <button 
                        onclick="changePage(${current_page - 1})"
                        class="px-4 py-2 bg-white/10 backdrop-blur-lg border border-white/30 text-white rounded-l-xl hover:bg-white/20 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
                    >
                        <i class="fas fa-chevron-left text-sm"></i>
                        <span>{{ \App\Helpers\TranslationHelper::t('previous') }}</span>
                    </button>
                `;
            }

            // Page numbers
            const startPage = Math.max(1, current_page - 2);
            const endPage = Math.min(last_page, current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <button 
                        onclick="changePage(${i})"
                        class="px-4 py-2 border-t border-b border-white/30 transition-all duration-300 ${
                            i === current_page 
                                ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg transform scale-105' 
                                : 'bg-white/10 backdrop-blur-lg text-white hover:bg-white/20 hover:shadow-lg hover:scale-105'
                        }"
                    >
                        ${i}
                    </button>
                `;
            }

            // Next button
            if (current_page < last_page) {
                paginationHTML += `
                    <button 
                        onclick="changePage(${current_page + 1})"
                        class="px-4 py-2 bg-white/10 backdrop-blur-lg border border-white/30 text-white rounded-r-xl hover:bg-white/20 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
                    >
                        <span>{{ \App\Helpers\TranslationHelper::t('next') }}</span>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                `;
            }

            pagination.innerHTML = `
                <div class="flex items-center space-x-4">
                    <div class="flex">
                        ${paginationHTML}
                    </div>
                    <div class="text-sm text-gray-300 bg-white/10 backdrop-blur-lg px-4 py-2 rounded-xl border border-white/20">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ \App\Helpers\TranslationHelper::t('page') }} <span class="font-semibold text-blue-300">${current_page}</span> {{ \App\Helpers\TranslationHelper::t('of') }} <span class="font-semibold text-purple-300">${last_page}</span> 
                        (<span class="font-semibold text-green-300">${total}</span> {{ \App\Helpers\TranslationHelper::t('characters') }})
                    </div>
                </div>
            `;
        }

        // Change page
        function changePage(page) {
            currentPage = page;
            loadCharacters();
        }

        // View character details
        function viewCharacter(id) {
            console.log('Fetching character with ID:', id); // Debug log
            
            fetch(`/characters/${id}`)
                .then(response => {
                    console.log('Response status:', response.status); // Debug log
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Debug log
                    if (data.success) {
                        const character = data.data;
                        showCharacterModal(character);
                    } else {
                        console.error('API Error:', data.message); // Debug log
                        showToast('{{ \App\Helpers\TranslationHelper::t('error_details') }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error); // Debug log
                    showToast('{{ __('error_details') }}', 'error');
                });
        }

        // Show character modal
        function showCharacterModal(character) {
            console.log('Character data:', character); // Debug log
            
            // Update modal content
            document.getElementById('modalCharacterName').textContent = character.name || '{{ \App\Helpers\TranslationHelper::t('no_name_available') }}';
            const modalImage = document.getElementById('modalCharacterImage');
            modalImage.src = character.image_url || 'https://via.placeholder.com/200x200?text=No+Image';
            modalImage.alt = character.name || 'Personaje';
            
            // Apply grayscale filter for dead characters
            if (character.status === 'Dead') {
                modalImage.classList.add('grayscale');
            } else {
                modalImage.classList.remove('grayscale');
            }
            
            document.getElementById('modalCharacterStatus').textContent = translateValue(character.status) || '{{ \App\Helpers\TranslationHelper::t('unknown_field') }}';
            document.getElementById('modalCharacterSpecies').textContent = translateValue(character.species) || '{{ \App\Helpers\TranslationHelper::t('unknown_field') }}';
            document.getElementById('modalCharacterGender').textContent = translateValue(character.gender) || '{{ \App\Helpers\TranslationHelper::t('unknown_field') }}';
            document.getElementById('modalCharacterType').textContent = translateValue(character.type) || '{{ \App\Helpers\TranslationHelper::t('not_available') }}';
            document.getElementById('modalCharacterOrigin').textContent = translateValue(character.origin_name) || '{{ \App\Helpers\TranslationHelper::t('unknown_field') }}';
            document.getElementById('modalCharacterLocation').textContent = translateValue(character.location_name) || '{{ \App\Helpers\TranslationHelper::t('unknown_field') }}';
            
            // Show modal with proper styling
            characterModal.classList.remove('hidden');
            characterModal.style.display = 'block';
            characterModal.style.pointerEvents = 'auto';
            document.body.style.overflow = 'hidden';
            
            // Force a reflow to ensure the modal is visible
            characterModal.offsetHeight;
            
            // Add event listeners after modal is shown
            setTimeout(() => {
                const modalContent = characterModal.querySelector('.relative');
                if (modalContent) {
                    modalContent.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                }
            }, 100);
        }

        // Close character modal
        function closeCharacterModal() {
            characterModal.classList.add('hidden');
            characterModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Show/hide loading
        function showLoading() {
            loadingSpinner.classList.remove('hidden');
            charactersGrid.innerHTML = '';
        }

        function hideLoading() {
            loadingSpinner.classList.add('hidden');
        }

        // Show/hide skeleton loading
        function showSkeletonLoading() {
            skeletonLoading.classList.remove('hidden');
            charactersGrid.innerHTML = '';
            generateSkeletonCards();
        }

        function hideSkeletonLoading() {
            skeletonLoading.classList.add('hidden');
        }

        // Generate skeleton cards
        function generateSkeletonCards() {
            const skeletonCards = Array(12).fill().map(() => `
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden border border-white/20">
                    <div class="skeleton bg-gray-300 h-64"></div>
                    <div class="p-6 space-y-3">
                        <div class="skeleton bg-gray-300 h-6 w-3/4 rounded"></div>
                        <div class="space-y-2">
                            <div class="skeleton bg-gray-300 h-4 w-full rounded"></div>
                            <div class="skeleton bg-gray-300 h-4 w-5/6 rounded"></div>
                            <div class="skeleton bg-gray-300 h-4 w-4/5 rounded"></div>
                        </div>
                        <div class="skeleton bg-gray-300 h-12 w-full rounded-xl mt-4"></div>
                    </div>
                </div>
            `).join('');
            skeletonLoading.innerHTML = skeletonCards;
        }

        // Show/hide error
        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        function hideError() {
            errorMessage.classList.add('hidden');
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 
                           type === 'error' ? 'bg-red-500' : 
                           type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            toast.className = `toast ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-80`;
            
            const icon = type === 'success' ? 'fas fa-check-circle' :
                        type === 'error' ? 'fas fa-exclamation-circle' :
                        type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            
            toast.innerHTML = `
                <i class="${icon} text-xl"></i>
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Show toast
            setTimeout(() => toast.classList.add('show'), 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Update total characters count
        function updateTotalCharacters(total) {
            totalCharacters.textContent = total;
        }

        // Show success message (legacy)
        function showMessage(message, type) {
            showToast(message, type);
        }

        // Handle language change
        async function changeLanguage(language) {
            try {
                console.log('Changing language to:', language);
                
                // Show loading state
                const languageDropdown = document.getElementById('languageDropdown');
                if (languageDropdown) {
                    languageDropdown.style.opacity = '0.5';
                    languageDropdown.style.pointerEvents = 'none';
                }
                
                const response = await fetch('/language/change', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ lang: language })
                });
                
                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    // Show success message
                    showToast('Idioma cambiado exitosamente / Language changed successfully', 'success');
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    showToast('Error al cambiar idioma / Error changing language', 'error');
                }
            } catch (error) {
                console.error('Error changing language:', error);
                showToast('Error al cambiar idioma / Error changing language', 'error');
            } finally {
                // Reset dropdown state
                if (languageDropdown) {
                    languageDropdown.style.opacity = '1';
                    languageDropdown.style.pointerEvents = 'auto';
                }
            }
        }

        // Load characters on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Load translations first, then characters
            loadTranslations().then(() => {
                loadCharacters();
                loadSpecies(); // Load species for filter
            });
        });
        
        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !characterModal.classList.contains('hidden')) {
                closeCharacterModal();
            }
        });
    </script>
</body>
</html>
