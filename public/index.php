<?php 

const API_URL = "https://whenisthenextmcufilm.com/api"; 
# Inicializar una nueva sesión de cURL; ch = cURL handle 
$ch = curl_init(API_URL); 

// Indicar que queremos recibir el resultado de la petición y no mostrarla en pantalla 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

/* Ejecutar la peticion 
y guardar el resultado*/ 
$result = curl_exec($ch); 

// una alternativa es usar file_get_contents
// $result = file_get_contents(API_URL); // si solo quieres hacer un GET de una API

if ($result === false) {
    $curlError = curl_error($ch);
    error_log("MCU API cURL error: " . $curlError);
    curl_close($ch);
    $loadError = "No se pudo conectar con la API. Por favor, inténtalo más tarde.";
} else {
    curl_close($ch);
    $data = json_decode($result, true);

    if ($data === null) {
        error_log("MCU API JSON decode error: " . json_last_error_msg() . " — raw response: " . $result);
        $loadError = "La respuesta de la API no es válida. Por favor, inténtalo más tarde.";
    }
}
?>

<head> 
    <meta charset="UTF-8"/>
    <title>La próxima película de Marvel</title>
    <meta name="description" content="La próxima película de Marvel"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css"
    >
</head>

<?php if (isset($loadError)): ?>

<main>
    <hgroup>
        <h2>La próxima película de Marvel</h2>
        <p>⚠️ <?= htmlspecialchars($loadError) ?></p>
    </hgroup>
</main>

<?php else: ?>

<main>
    <section>
        <h2>La próxima película de Marvel</h2>
        <img src="<?= $data["poster_url"]; ?>" width="300" alt="Poster de <?= $data["title"] ?>"     
        style="border-radius: 16px"
        />
    </section> 

    <hgroup>
        <h3><?= $data["title"] ?> se estrena en <?= $data["days_until"] ?> días</h3>
        <p>Fecha de estreno <?= $data["release_date"] ?></p>
        <p>La siguiente es: <?= $data["following_production"]["title"] ?></p>
    </hgroup>

</main>

<?php endif; ?>

<style>
    :root {
        color-scheme: light dark;
    }

    body {
        display: grid;
        place-content: center;
    }

    section, hgroup {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    img {
        margin: 0 auto;
    }

</style>
