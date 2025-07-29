document.addEventListener('DOMContentLoaded', () => {
    const breedSelect = document.getElementById('breed-select');
    const dogNameInput = document.getElementById('dog-name-input');
    const colorSelect = document.getElementById('color-select');
    const fontSelect = document.getElementById('font-select');
    const dogImage = document.getElementById('dog-image');
    const dogNameDisplay = document.getElementById('dog-name-display');
    const saveButton = document.getElementById('save-button');
    const successMessage = document.getElementById('success-message');
    const dogAppForm = document.getElementById('dog-app-form'); 

    const DOG_API_IMAGE_URL_BASE = 'https://dog.ceo/api/breed/';

    /**
     * @brief 
     */
    function populateBreeds() {
        if (ERROR_FETCHING_BREEDS_PHP) {
            breedSelect.innerHTML = '<option value="">Erro ao carregar raças</option>';
            return;
        }
        
        breedSelect.innerHTML = '<option value="">Selecione uma raça</option>';
        INITIAL_BREEDS.forEach(breed => {
            const option = document.createElement('option');
            option.value = breed.value;
            option.textContent = breed.text;
            breedSelect.appendChild(option);
        });
    }

    /**
     * @brief 
     * @param {string} breed 
     */
    async function fetchDogImage(breed) {
        if (!breed) {
            dogImage.src = 'https://via.placeholder.com/400?text=Selecione+uma+raça';
            return;
        }

        let imageUrl;
        if (breed.includes('-')) {
            const [mainBreed, subBreed] = breed.split('-');
            imageUrl = `${DOG_API_IMAGE_URL_BASE}${mainBreed}/${subBreed}/images/random`;
        } else {
            imageUrl = `${DOG_API_IMAGE_URL_BASE}${breed}/images/random`;
        }

        try {
            const response = await fetch(imageUrl);
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }
            const data = await response.json();
            if (data.status === 'success') {
                dogImage.src = data.message;
            } else {
                console.warn(`API returned failure for breed ${breed}: ${data.message}`);
                dogImage.src = 'https://via.placeholder.com/400?text=Imagem+não+encontrada';
            }
        } catch (error) {
            console.error(`Error fetching image for ${breed}:`, error);
            dogImage.src = 'https://via.placeholder.com/400?text=Erro+ao+carregar+imagem';
        }
    }

    /**
     * @brief 
     */
    function applyStylesToDogName() {
        dogNameDisplay.textContent = dogNameInput.value.trim() !== '' ? dogNameInput.value : 'Nome do Cachorro';
        dogNameDisplay.style.color = colorSelect.value;
        dogNameDisplay.style.fontFamily = `'${fontSelect.value}', sans-serif`;
    }

    /**
     * @brief
     */
    function initializeFormWithPreferences() {
        if (INITIAL_PREFERENCES.breed) {

            const optionExists = Array.from(breedSelect.options).some(
                option => option.value === INITIAL_PREFERENCES.breed
            );
            if (optionExists) {
                breedSelect.value = INITIAL_PREFERENCES.breed;
            } else {
                breedSelect.value = ''; 
            }
        }
        
        dogNameInput.value = INITIAL_PREFERENCES.dogName || '';
        colorSelect.value = INITIAL_PREFERENCES.color || 'black';
        fontSelect.value = INITIAL_PREFERENCES.font || 'Roboto';

        applyStylesToDogName();
        fetchDogImage(breedSelect.value);
    }

    /**
     * @brief 
     * @param {string} message 
     */
    function displaySuccessMessage(message) {
        if (successMessage) { 
            successMessage.textContent = message;
            successMessage.classList.remove('hidden');
            successMessage.style.display = 'block';

            setTimeout(() => {
                successMessage.classList.add('hidden');
                successMessage.style.display = 'none';
            }, 3000);
        }
    }

    breedSelect.addEventListener('change', (event) => {
        fetchDogImage(event.target.value);
    });

    dogNameInput.addEventListener('input', applyStylesToDogName);
    colorSelect.addEventListener('change', applyStylesToDogName);
    fontSelect.addEventListener('change', applyStylesToDogName);

    populateBreeds(); 
    initializeFormWithPreferences();

    if (successMessage && successMessage.textContent.trim() !== '' && !successMessage.classList.contains('hidden')) {
        displaySuccessMessage(successMessage.textContent);
    }
});