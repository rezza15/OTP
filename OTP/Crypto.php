<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enkripsi dan Dekripsi</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            border: 2px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Encrypt & Decrypt</h2>

        <?php
         function konversiAscii($input_string) {
            $ascii_values = [];
            for ($i = 0; $i < strlen($input_string); $i++) {
                $ascii_value = ord($input_string[$i]);
                $ascii_values[] = $ascii_value;
            }
            return $ascii_values;
        }
    
        function xorBiner($biner1, $biner2) {
            $result = bindec($biner1) ^ bindec($biner2);
            $result_biner = str_pad(decbin($result), 8, "0", STR_PAD_LEFT);
            return $result_biner;
        }
    
        function binerKeDesimal($biner) {
            return bindec($biner);
        }
    
        function kodeAscii($ascii_code) {
            return chr($ascii_code);
        }
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $plaintext = $_POST["plaintext"];
            $kunci = $_POST["kunci"];
    
            // Konversi Plaintext ke ASCII
            $ascii_values_plaintext = konversiAscii($plaintext);
    
            // Konversi Kunci ke ASCII
            $ascii_values_kunci = konversiAscii($kunci);
    
            // XOR antara ASCII Plaintext dan ASCII Kunci
            $hasil_xor = [];
            for ($i = 0; $i < strlen($plaintext); $i++) {
                $bin_ascii_plaintext = decbin($ascii_values_plaintext[$i]);
                $bin_ascii_kunci = decbin($ascii_values_kunci[$i % strlen($kunci)]);
                $hasil_xor[] = xorBiner($bin_ascii_plaintext, $bin_ascii_kunci);
            }
    
            // Konversi hasil XOR ke Desimal
            $hasil_desimal = array_map("binerKeDesimal", $hasil_xor);
    
            // Menampilkan hasil Enkripsi dalam format yang diminta
            $hasil_enkripsi = array_map(function ($desimal, $xor) {
                return ($desimal < 32) ? "ctrl-" . chr($desimal) . " ($xor)" : chr($desimal);
            }, $hasil_desimal, $hasil_xor);
        }
        ?>

        <form method="post" action="">
            <label for="plaintext">Plaintext:</label>
            <input type="text" name="plaintext" id="plaintext" required>

            <label for="kunci">Kunci:</label>
            <input type="text" name="kunci" id="kunci" required>

            <button type="submit">Enkripsi</button>
        </form>

        <?php if (isset($hasil_enkripsi)): ?>
            <h3>Hasil Enkripsi:</h3>
            <p>Plainteks: <?php echo $plaintext; ?></p>
            <p>Kunci: <?php echo $kunci; ?></p>
            <p>Hasil Enkripsi: <?php echo implode(" ", $hasil_enkripsi); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
