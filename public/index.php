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

$error = null;

if ($result === false) {
    $error = "Error al conectar con la API: " . curl_error($ch);
}

curl_close($ch);

if ($error === null) {
    $data = json_decode($result, true);
    if ($data === null) {
        $error = "Error al procesar la respuesta de la API: JSON inválido.";
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

<main>
<?php if ($error !== null): ?>
    <section>
        <h2>La próxima película de Marvel</h2>
        <p style="color: red;"><strong>No se pudieron cargar los datos.</strong> <?= htmlspecialchars($error) ?></p>
    </section>
<?php else: ?>
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
<?php endif; ?>

</main>

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
