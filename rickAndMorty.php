<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Rick and Morty API</title>
    <style>
        .character {
            border: 5px solid #2dd000;
            margin: 5px;
            padding: 5px;
            display: inline-block;
            vertical-align: top; 
          
        }
        
        .avatar {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body>
    <h1>Characters</h1>

    <?php
    
    function fetchCharacters()
    {

        $characters = [];
        $url = "https://rickandmortyapi.com/api/character/";
        $page = 1;
        
        do {
            $curl = curl_init($url . "?page=$page"); // inicializujemy połączenie cURL
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // ustawienie CURLOPT_RETURNTRANSFER na true aby curl_exec() zwracało pobrane dane
            $response = curl_exec($curl); // żądanie HTTP // teraz dane JSON znajduja sie w $response
            curl_close($curl); // zamkniecie połączenia
            
            $data = json_decode($response, true); // decode danych // true powoduje zwrócenie wyniku jako tablicy zamiast obiektu
            $characters = array_merge($characters, $data['results']); // array_merge aby dodać postacie do juz zawartych postaci
            $page++;
        } while (!empty($data['info']['next'])); // warunek sprawdzający istnienie nastepnej strony
        
        return $characters;
    }

    $charactersData = fetchCharacters();

    foreach ($charactersData as $character) {  //wskazanie na tablice i nazwa poszczególnego elementu
        echo '<div class="character">'; 
        echo '<img class="avatar" src="' . $character['image'] . '" alt="' . $character['name'] . '">'; // kropka łączymy ciągi znaków 
        echo '<h2>' . $character['name'] . '</h2>';
        echo '<strong><p>Status: </strong>' . $character['status'] . '</p>';
        echo '<strong><p>Species: </strong>' . $character['species'] . '</p>';
        echo '<strong><p>Origin: </strong>' . $character['origin']['name'] . '</p>';
        echo '<strong><p>Location: </strong>' . $character['location']['name'] . '</p>';
        echo '<strong><p>Episodes:</strong>';
        echo '<ul>';
        foreach ($character['episode']as $index =>  $episode) { // => operator przypisania
            $lastSlashPosition = strrpos($episode, '/'); // znajduje ostatni ukośnik
            $lastNumber = substr($episode, $lastSlashPosition + 1);
            echo $lastNumber;
            if($index + 1 != count($character['episode']))
            {
                echo ', ';
            }
           // var_dump($character['episode']);
            if(($index + 1)%5 == 0)
            {
                echo '<br>';
            }         
        }
        echo '</ul>';
        echo '</div>';
    }
    ?>
</body>
</html>