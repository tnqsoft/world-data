<?php
function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Nguyễn Như Tuấn - tuanquynh0508@gmail.com - http://tnqsoft.com</title>
	</head>

	<body>
        <h1>Extract Countries List - Version 1.0.0</h1>
        <p>Update: 2016-12-22</p>
        <p>Download Data Countries From: <a href="https://countrycode.org/customer/countryCode/downloadCountryCodes" target="_blank">https://countrycode.org/customer/countryCode/downloadCountryCodes</a></p>
        <p>For French Language: <a href="http://www.nationsonline.org/oneworld/countries_of_the_world.htm" target="_blank">http://www.nationsonline.org/oneworld/countries_of_the_world.htm</a></p>

		<?php
            $worldCountries = array();
            $language = 'en';

            $i = 0;
            if (($handle = fopen("countrycode-20161222.csv", "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    $i++;
                    if ($i === 1) {
                        continue;
                    }
                    $name = $data[0];

                    if($language !== 'en') {
                        if ($file = fopen("./langs/{$language}.txt", "r")) {
                            while(!feof($file)) {
                                $line = fgets($file);
                                $names = explode(',', $line);
                                if (trim(strtolower($name)) === trim(strtolower($names[0]))) {
                                    $name = trim($names[1]);
                                }
                            }
                            fclose($file);
                        }
                    }

                    $worldCountries[] = array(
                        'name' => $name,
                        'iso1' => $data[1],
                        'iso2' => $data[2],
                        'domain' => $data[3],
                        'fips' => $data[4],
                        'isoNumeric' => $data[5],
                        'geoNameID' => $data[6],
                        'e164' => $data[7],
                        'phoneCode' => $data[8],
                        'continent' => $data[9],
                        'capital' => $data[10],
                        'timeZone' => $data[11],
                        'currency' => $data[12],
                        'languageCode' => $data[13],
                        'languages' => $data[14],
                        'areaKm2' => $data[15],
                        'internetHost' => $data[16],
                        'internetUsers' => $data[17],
                        'phonesMobile' => $data[18],
                        'phonesLandline' => $data[19],
                        'gdp' => $data[20],
                    );
                }
                fclose($handle);
            }

            $content = json_encode($worldCountries, JSON_PRETTY_PRINT);
            $fp = fopen("countries-in-{$language}.json", 'w');
            fwrite($fp, unicode_decode($content));
            fclose($fp);

            echo 'Exstract Success.';
        ?>
	</body>
</html>
