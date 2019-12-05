<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Your appointment has been approved</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
          a {
            text-decoration: none;
            color: white;
            border-radius: 5px;
            display: inline-block;
            background-color: blue;
            margin: 0.7rem;
            padding: 0.5rem;
            cursor: pointer;
          }
          a:hover {
            background-color: navy;
          }
          .buttons {
            margin:1rem;
          }
        </style>
    </head>
    <body>
        <p>Poštovani,</p>
        <p>Vaš zahtjev za zakazivanje termina je odobren. Kliknite dugme 
        "Prihvati" da potvrdite termin, ili "Odbij" za poništavanje zakazanog 
        termina.</p>
        <div class="buttons">
          <a href="{{ url('/api/appointments/' . $appointment->id . '/accept/' . $appointment->token) }}">
            Prihvati
          </a>
          <a href="{{ url('/api/appointments/' . $appointment->id . '/decline/' . $appointment->token) }}">
            Odbij
          </a>
        </div>
    </body>
</html>