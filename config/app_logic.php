<?php
declare(strict_types=1);

namespace DogApp\Config;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

const DOG_API_BREEDS_URL = 'https://dog.ceo/api/breeds/list/all';
const DOG_API_IMAGE_URL_BASE = 'https://dog.ceo/api/breed/';
const COOKIE_NAME = 'dogAppPreferences';
const COOKIE_EXPIRATION_DAYS = 30;

class DogApp
{
    private array $breedsData = [];
    private bool $errorFetchingBreeds = false;
    private array $currentPreferences = [];
    private string $dogImageUrl = 'https://via.placeholder.com/400?text=Selecione+uma+raça';
    private string $successMessage = '';

    public function __construct()
    {
        $this->loadBreedsData();
        $this->loadPreferencesFromCookies();
        $this->processFormSubmission();
        $this->updateDogImageUrl();
    }

    private function fetchApiData(string $url): array|string|null
    {
        $response = @file_get_contents($url);
        if ($response === false) {
            return null;
        }
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['status']) || $data['status'] !== 'success') {
            return null;
        }
        return $data['message'];
    }

    private function loadBreedsData(): void
    {
        $data = $this->fetchApiData(DOG_API_BREEDS_URL);
        if (is_array($data)) {
            $this->breedsData = $data;
        } else {
            $this->breedsData = [];
        }
        $this->errorFetchingBreeds = empty($this->breedsData);
    }

    private function loadPreferencesFromCookies(): void
    {
        if (isset($_COOKIE[COOKIE_NAME])) {
            $decodedPreferences = json_decode($_COOKIE[COOKIE_NAME], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedPreferences)) {
                $this->currentPreferences = $decodedPreferences;
            }
        }
    }

    private function processFormSubmission(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'savePreferences') {
            $currentBreed = $_POST['breed-select'] ?? '';
            $currentDogName = htmlspecialchars($_POST['dog-name-input'] ?? '');
            $currentColor = $_POST['color-select'] ?? 'black';
            $currentFont = $_POST['font-select'] ?? 'Roboto';

            $newPreferences = [
                'breed'     => $currentBreed,
                'dogName'   => $currentDogName,
                'color'     => $currentColor,
                'font'      => $currentFont,
                'timestamp' => (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->format('d/m/Y H:i:s')
            ];

            setcookie(COOKIE_NAME, json_encode($newPreferences), time() + (86400 * COOKIE_EXPIRATION_DAYS), "/");
            $this->currentPreferences = $newPreferences;
            $this->successMessage = "Preferências salvas com sucesso!";
            
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    private function updateDogImageUrl(): void
    {
        $currentBreed = $this->getPreference('breed');
        if (!empty($currentBreed)) {
            $breedParts = explode('-', $currentBreed);
            $imageUrlPath = (count($breedParts) > 1) ? "{$breedParts[0]}/{$breedParts[1]}/images/random" : "{$currentBreed}/images/random";
            $apiImageUrl = DOG_API_IMAGE_URL_BASE . $imageUrlPath;

            $imageData = $this->fetchApiData($apiImageUrl);
            if (is_string($imageData)) {
                $this->dogImageUrl = $imageData;
            } else {
                $this->dogImageUrl = 'https://via.placeholder.com/400?text=Erro+ao+carregar+imagem';
            }
        }
    }

    public function formatBreedNameForDisplay(string $breedName): string
    {
        if (str_contains($breedName, '-')) {
            $parts = explode('-', $breedName);
            return ucfirst($parts[1]) . ' ' . ucfirst($parts[0]);
        }
        return ucfirst($breedName);
    }

    public function getPreference(string $key, $default = ''): mixed
    {
        return $this->currentPreferences[$key] ?? $default;
    }

    public function getBreedsData(): array
    {
        return $this->breedsData;
    }

    public function hasErrorFetchingBreeds(): bool
    {
        return $this->errorFetchingBreeds;
    }

    public function getDogImageUrl(): string
    {
        return $this->dogImageUrl;
    }

    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    public function getAvailableColors(): array
    {
        return [
            'black'  => 'Preto',
            'red'    => 'Vermelho',
            'blue'   => 'Azul',
            'green'  => 'Verde',
            'purple' => 'Roxo'
        ];
    }

    public function getAvailableFonts(): array
    {
        return ['Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Raleway'];
    }
}