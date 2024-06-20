<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Šifrování a dešifrování</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h1>Šifrování a dešifrování textu</h1>
        <form id="cipherForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="textInput">Vstupní text:</label>
            <input type="text" id="textInput" name="textInput" class="vstup" required><br>

            <label for="shiftAmount">Posun:</label>
            <input type="number" id="shiftAmount" name="shiftAmount" class="vstup" value="1" required><br>

            <div class="radioWrap">
                <label for="encrypt">
                    <input type="radio" id="encrypt" name="operation" value="encrypt" checked>
                    Šifrovat
                </label>
                <label for="decrypt">
                    <input type="radio" id="decrypt" name="operation" value="decrypt">
                    Dešifrovat
                </label>
            </div>

            <input type="submit" name="submit" value="Vykonat akci" class="vstup">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $text = $_POST["textInput"];
            $posun = (int)$_POST["shiftAmount"];
            $operace = $_POST["operation"];

            // Funkce pro šifrování textu
            function zasifrujText($text, $posun) {
                $vysledek = "";
                $text = mb_strtoupper($text, 'UTF-8');
                $delka = mb_strlen($text, 'UTF-8');
                for ($i = 0; $i < $delka; $i++) {
                    $znak = mb_substr($text, $i, 1, 'UTF-8');
                    if (ctype_alpha($znak)) {
                        $vysledek .= mb_chr((mb_ord($znak, 'UTF-8') - 65 + $posun) % 26 + 65, 'UTF-8');
                    } else {
                        $vysledek .= $znak;
                    }
                }
                return $vysledek;
            }

            // Funkce pro dešifrování textu
            function desifrujText($text, $posun) {
                $vysledek = "";
                $text = mb_strtoupper($text, 'UTF-8');
                $delka = mb_strlen($text, 'UTF-8');
                for ($i = 0; $i < $delka; $i++) {
                    $znak = mb_substr($text, $i, 1, 'UTF-8');
                    if (ctype_alpha($znak)) {
                        $vysledek .= mb_chr((mb_ord($znak, 'UTF-8') - 65 - $posun + 26) % 26 + 65, 'UTF-8');
                    } else {
                        $vysledek .= $znak;
                    }
                }
                return $vysledek;
            }

            // Volání pro šifrování/dešifrování
            $vysledek = "";
            if ($operace === "encrypt") {
                $vysledek = zasifrujText($text, $posun);
            } elseif ($operace === "decrypt") {
                $vysledek = desifrujText($text, $posun);
            }

            // Výsledek
            echo '<div class="result">';
            echo "<h2>Výsledek:</h2>";
            echo "<p><strong>Text:</strong> " . htmlspecialchars($text) . "</p>";
            echo "<p><strong>Operace:</strong> " . ($operace === "encrypt" ? "Šifrování" : "Dešifrování") . "</p>";
            echo "<p><strong>Posun:</strong> " . $posun . "</p>";
            echo "<p><strong>Výsledek:</strong> " . htmlspecialchars($vysledek) . "</p>";
            echo '</div>';
        }
        ?>
    </main>
</body>
</html>
