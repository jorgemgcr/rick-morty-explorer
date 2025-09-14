<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>{{ \App\Helpers\TranslationHelper::t('title') }} Explorer</title>
    <!-- Cargar Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS para estilos adicionales -->
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-8px) scale(1.02); }
        .skeleton { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .toast { transform: translateX(100%); transition: transform 0.3s ease-in-out; }
        .toast.show { transform: translateX(0); }
        .modal-backdrop { backdrop-filter: blur(8px); }
    </style>
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
        
        /* Estilos para el selector de idioma */
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
                <!-- Selector de idioma -->
                <div class="relative group language-selector">
                    <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-lg border border-white/30 rounded-xl px-4 py-2 hover:bg-white/20 transition-all duration-300 cursor-pointer shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-language text-blue-400"></i>
                        <span class="text-white font-medium">{{ \App\Helpers\TranslationHelper::getLanguage() === 'es' ? 'Español' : 'English' }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform duration-300 group-hover:rotate-180"></i>
                    </div>
                    
                    <!-- Menu desplegable -->
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

        <!-- Busqueda y filtros -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-12 border border-white/20">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Input de busqueda -->
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
                
                <!-- Filtro de estado -->
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
                
                <!-- Filtro de especie -->
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
                
                <!-- Boton de fetch -->
                <button 
                    id="fetchButton" 
                    class="px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 focus:ring-2 focus:ring-blue-400 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center space-x-2"
                >
                    <i class="fas fa-download"></i>
                    <span>{{ \App\Helpers\TranslationHelper::t('fetch_characters') }}</span>
                </button>
            </div>
        </div>

        <!-- Spinner de carga -->
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

        <!-- Skeleton de carga -->
        <div id="skeletonLoading" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <!-- Skeleton cards will be generated here -->
        </div>

        <!-- Grid de personajes -->
        <div id="charactersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
            <!-- Characters will be loaded here -->
        </div>

        <!-- Paginacion -->
        <div id="pagination" class="flex justify-center items-center space-x-4 mb-8">
            <!-- Pagination buttons will be loaded here -->
        </div>

            <!-- Mensaje de error -->
        <div id="errorMessage" class="hidden bg-red-500/20 backdrop-blur-lg border border-red-400/50 text-red-200 px-6 py-4 rounded-xl mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <strong>{{ \App\Helpers\TranslationHelper::t('error_loading') }}:</strong> <span id="errorText"></span>
            </div>
        </div>

        <!-- Contenedor de toast -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2">
            <!-- Toast notifications will appear here -->
        </div>

        <!-- Modal de detalle de personaje -->
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
        
        // Cargar traducciones desde el servidor
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
        
        // Funcion de traduccion para valores dinamicos
        function translateValue(value) {
            if (!value) return value;
            return translations[value] || value;
        }
        
        // Las traducciones se cargaran en el evento DOMContentLoaded

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
        
        // Selector de idioma (ya no es necesario ya que usamos botones onclick)
        
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
        
        // Cerrar modal cuando se hace clic fuera
        characterModal.addEventListener('click', (e) => {
            if (e.target === characterModal) {
                closeCharacterModal();
            }
        });
        
        // Prevenir que el contenido del modal cierre el modal
        const modalContent = characterModal.querySelector('.relative');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Funcion debounce
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

        // Manejar busqueda
        function handleSearch() {
            currentSearch = searchInput.value;
            currentPage = 1;
            loadCharacters();
        }

        // Manejar filtros
        function handleFilter() {
            currentStatus = statusFilter.value;
            currentSpecies = speciesFilter.value;
            currentPage = 1;
            loadCharacters();
        }

        // Manejar fetch
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

        // Cargar personajes
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

        // Cargar especies para el filtro
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

        // Llenar el filtro de especies
        function populateSpeciesFilter(species) {
            const speciesFilter = document.getElementById('speciesFilter');
            
            // Limpiar las opciones existentes excepto "Todas las especies"
            speciesFilter.innerHTML = '<option value="" class="bg-gray-800 text-white">{{ \App\Helpers\TranslationHelper::t('all_species') }}</option>';
            
            // Agregar opciones de especies
            species.forEach(specie => {
                const option = document.createElement('option');
                option.value = specie;
                option.className = 'bg-gray-800 text-white';
                
                // Agregar emoji basado en la especie
                let emoji = '🧬'; // Default
                if (specie.toLowerCase().includes('human')) emoji = '👤';
                else if (specie.toLowerCase().includes('alien')) emoji = '👽';
                
                option.textContent = `${emoji} ${specie}`;
                speciesFilter.appendChild(option);
            });
        }

        // Mostrar personajes
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

        // Mostrar paginacion
        function displayPagination(paginationData) {
            const { current_page, last_page, total } = paginationData;
            
            if (last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';
            
            // Boton anterior
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

            // Numeros de pagina
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

            // Boton siguiente
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

        // Cambiar pagina
        function changePage(page) {
            currentPage = page;
            loadCharacters();
        }

        // Ver detalles de personaje
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

            // Mostrar modal de personaje
        function showCharacterModal(character) {
            console.log('Character data:', character); // Debug log
            
            // Actualizar el contenido del modal
            document.getElementById('modalCharacterName').textContent = character.name || '{{ \App\Helpers\TranslationHelper::t('no_name_available') }}';
            const modalImage = document.getElementById('modalCharacterImage');
            modalImage.src = character.image_url || 'https://via.placeholder.com/200x200?text=No+Image';
            modalImage.alt = character.name || 'Personaje';
            
            // Aplicar filtro de escala de grises para personajes muertos
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
            
            // Mostrar modal con estilos adecuados
            characterModal.classList.remove('hidden');
            characterModal.style.display = 'block';
            characterModal.style.pointerEvents = 'auto';
            document.body.style.overflow = 'hidden';
            
            // Forzar un reflow para asegurar que el modal es visible
            characterModal.offsetHeight;
            
            // Agregar event listeners despues de mostrar el modal
            setTimeout(() => {
                const modalContent = characterModal.querySelector('.relative');
                if (modalContent) {
                    modalContent.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                }
            }, 100);
        }

        // Cerrar modal de personaje
        function closeCharacterModal() {
            characterModal.classList.add('hidden');
            characterModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Mostrar/ocultar loading
        function showLoading() {
            loadingSpinner.classList.remove('hidden');
            charactersGrid.innerHTML = '';
        }

        // Ocultar loading
        function hideLoading() {
            loadingSpinner.classList.add('hidden');
        }

        // Mostrar/ocultar skeleton loading
        function showSkeletonLoading() {
            skeletonLoading.classList.remove('hidden');
            charactersGrid.innerHTML = '';
            generateSkeletonCards();
        }

        // Ocultar skeleton loading
        function hideSkeletonLoading() {
            skeletonLoading.classList.add('hidden');
        }

        // Generar skeleton cards
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

        // Mostrar/ocultar error
        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        // Ocultar error
        function hideError() {
            errorMessage.classList.add('hidden');
        }

        // Mostrar toast notification
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
            
                // Mostrar toast
            setTimeout(() => toast.classList.add('show'), 100);
            
            // remover despues de 5 segundos
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Actualizar el total de personajes
        function updateTotalCharacters(total) {
            totalCharacters.textContent = total;
        }

        // Mostrar mensaje de exito (legacy)
        function showMessage(message, type) {
            showToast(message, type);
        }

        // Manejar cambio de idioma
        async function changeLanguage(language) {
            try {
                console.log('Changing language to:', language);
                
                // Mostrar estado de loading
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
                    // Mostrar mensaje de exito
                    showToast('Idioma cambiado exitosamente / Language changed successfully', 'success');
                    
                    // Recargar pagina despues de un breve delay
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
                // Resetear estado del dropdown
                if (languageDropdown) {
                    languageDropdown.style.opacity = '1';
                    languageDropdown.style.pointerEvents = 'auto';
                }
            }
        }

        // Cargar personajes en el load de la pagina
        document.addEventListener('DOMContentLoaded', () => {
            // Load translations first, then characters
            loadTranslations().then(() => {
                loadCharacters();
                loadSpecies(); // Cargar especies para el filtro
            });
        });
        
        // Cerrar modal con la tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !characterModal.classList.contains('hidden')) {
                closeCharacterModal();
            }
        });
    </script>
</body>
</html>
