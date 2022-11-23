<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <title>Form</title>
</head>

<body>
<a class="back" href="index.php">Terug naar afspraken</a>
<form action="">
    <section class="formfield">
        <label for="naam">Naam:</label>
        <input type="text" name="naam" id="naam" placeholder="Voornaam Achternaam" required autocomplete="off">
    </section>
    <section class="formfield">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" autocomplete="off">
    </section>
    <section class="formfield">
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{2} [0-9]{8}" placeholder="06 12345678"
               autocomplete="off">
    </section>
    <section class="formfield">
        <label for="afspraak">Afspraak:</label>
        <select name="afspraak" id="afspraak" required>
            <option disabled selected hidden>Maak een keuze.</option>
            <option value="kijken">Komen kijken naar vogels.</option>
            <option value="bloed">Bloed afnemen van mijn vogel.</option>
            <option value="nagels">Nagels laten knippen.</option>
            <option value="veren">Veren laten knippen.</option>
            <option value="opvang">Opvang voor mijn vogel regelen.</option>
        </select>
    </section>
    <section class="formfield">
        <button type="submit">Submit</button>
        <input id="reset" type="reset">
    </section>
</form>
</body>
</html>