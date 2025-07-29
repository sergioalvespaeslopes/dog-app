<?php
declare(strict_types=1);

require_once __DIR__ . '/config/app_logic.php';

use DogApp\Config\DogApp;

$app = new DogApp();

// Prepare initial data for JavaScript
$initialPreferences = [
    'breed'   => $app->getPreference('breed'),
    'dogName' => $app->getPreference('dogName'),
    'color'   => $app->getPreference('color'),
    'font'    => $app->getPreference('font')
];

$initialBreeds = [];
if (!$app->hasErrorFetchingBreeds()) {
    foreach ($app->getBreedsData() as $breed => $subBreeds) {
        if (empty($subBreeds)) {
            $initialBreeds[] = ['value' => $breed, 'text' => $app->formatBreedNameForDisplay($breed)];
        } else {
            foreach ($subBreeds as $subBreed) {
                $fullBreedName = "{$breed}-{$subBreed}";
                $initialBreeds[] = ['value' => $fullBreedName, 'text' => $app->formatBreedNameForDisplay($fullBreedName)];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Breeds App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&family=Lato:wght@400;700&family=Montserrat:wght@400;700&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center text-primary mb-4">Seu Pet com Estilo</h1>

        <div class="card p-4 shadow-sm">
            <form id="dog-app-form" method="POST" action="">
                <input type="hidden" name="action" value="savePreferences">

                <div class="mb-3">
                    <label for="breed-select" class="form-label">Selecione a Raça:</label>
                    <select id="breed-select" name="breed-select" class="form-select">
                        <option value="">Selecione uma raça</option>
                        </select>
                </div>

                <div class="mb-3">
                    <label for="dog-name-input" class="form-label">Nome do seu Cachorro:</label>
                    <input type="text" id="dog-name-input" name="dog-name-input" class="form-control" placeholder="Digite o nome aqui..." value="<?= htmlspecialchars($app->getPreference('dogName')) ?>">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="color-select" class="form-label">Cor da Fonte:</label>
                        <select id="color-select" name="color-select" class="form-select">
                            <?php foreach ($app->getAvailableColors() as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>" <?= ($app->getPreference('color') === $value) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="font-select" class="form-label">Estilo da Fonte:</label>
                        <select id="font-select" name="font-select" class="form-select">
                            <?php foreach ($app->getAvailableFonts() as $font): ?>
                                <option value="<?= htmlspecialchars($font) ?>" <?= ($app->getPreference('font') === $font) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($font) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="image-preview-container position-relative text-center mt-4 mb-4">
                    <img id="dog-image" src="<?= htmlspecialchars($app->getDogImageUrl()) ?>" alt="Imagem do Cachorro" class="img-fluid w-100 h-100 object-fit-cover">
                    <p id="dog-name-display" class="dog-name-overlay position-absolute top-50 start-50 translate-middle fw-bold"></p>
                </div>

                <button type="submit" id="save-button" class="btn btn-success btn-lg w-100">Salvar Preferências</button>
            </form>

            <?php if (!empty($app->getSuccessMessage())): ?>
                <div id="success-message" class="alert alert-success mt-3" role="alert">
                    <?= htmlspecialchars($app->getSuccessMessage()) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        const INITIAL_PREFERENCES = <?= json_encode($initialPreferences) ?>;
        const INITIAL_BREEDS = <?= json_encode($initialBreeds) ?>;
        const ERROR_FETCHING_BREEDS_PHP = <?= json_encode($app->hasErrorFetchingBreeds()) ?>;
    </script>
    <script src="js/script.js"></script>
</body>
</html>