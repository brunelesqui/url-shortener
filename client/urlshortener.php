<html>
  <head>
     <meta charset="UTF-8">
    <title>🔗 Free URL Shortener</title>
    <style>
      body { font-family: 'Roboto', sans-serif; background: grey; text-align: center; }
      h1 { margin: 48px; }
      table { margin: 0 auto; margin-top: 24px; border-collapse: separate; border-spacing: 0 2px; }
      tr { margin-bottom: 2px;  }
      td { background: white; padding: 12px; white-space: nowrap; }
      a { text-decoration: none; }
      .td-left { border-radius: 8px 0 0 8px; }
      .td-right { border-radius: 0 8px 8px 0; }
      .descrip { color: white; font-size: 20px; }
      .full-descrip { width: 60%; margin: 0 auto; margin-top: 48px; }
      .operation { margin-top: 24px; }
      #list { text-align: left; width: 60%; margin: 0 auto; }
      input { font-size: 18px; padding: 12px; border-radius: 8px; border: none; width: 50%; margin-right: 8px; }
      input:focus { outline: solid 2px black; }
      button { width: 160px; background: black; color: white; font-size: 18px; padding: 12px; border-radius: 8px; border: none; }
      button:hover { background: #333; cursor: pointer; }
      .del-btn { background: none; border: none; margin: 0; padding: 0; width: 32px; }
      .del-btn:hover { background: none; }
    </style>
  </head>
  <body>
    <h1>Brunelesqui Provide You<br>
    Best Free URL Shortener!</h1>
    <div class="descrip">
    Shorten links for free powered by brunelesqui. Create short and memorable links in seconds.
    </div>
    <div class="operation">
      <input type="text" id="urlText" placeholder="URL here..." />
      <button onClick="handleClickShortenUrl()">✨ Shorten URL</button>
    </div>
    <div id="list">
      <table id="urls">
              <?php
        require_once 'db_conn.php'; // Configuración de la base de datos
        require_once 'url_model.php'; // Modelo para la tabla urls
        require_once 'utils.php';
              
        $HOST = "brunelesqui.atwebpages.com";

        try {
          // Obtener la conexión a la base de datos
          $conn = getDBConnection();
                
          $shortener = new UrlModel($conn);
          $urls = $shortener->getAllUrls();

                foreach ($urls as $url) {
                        echo "<tr id='{$url['short_code']}'>
                            <td class='td-left'>🌐 " . getUrl($url['long_url']) . "</td>
                            <td><a href='http://{$HOST}/{$url['short_code']}'>http://{$HOST}/{$url['short_code']}</a></td>
                            <td class='td-right'>
                              <button class='del-btn' onClick=\"handleClickDel('{$url['short_code']}')\">❌</button>
                            </td>
                          </tr>";
                }
          
          } catch (Exception $e) { }
               ?>
      </table>
    </div>
    <div class="full-descrip">
      <h3>A fast, easy, and free link shortener</h3>
      <p>Use this free URL shortener to change long, ugly links into memorable and trackable short URLs. This is the best free link shortener alternative to Bitly, Tinyurl, and Google link shorteners.</p>
    </div>
  </body>
  <script>
    const HOST = "brunelesqui.atwebpages.com"
    
    const handleClickShortenUrl = () => {
      const urlText = document.getElementById('urlText')
      if(!urlText.value) { 
        alert('Insert the URL!')
        return
      }

      fetch('http://add_url.php?url=' + urlText.value)
        .then(response => {
          if (!response.ok) 
            alert('Request error!')

          return response.json()
        }).then(data => {
          if (data.success) {
            const table = document.getElementById('urls')
            
            table.innerHTML += `<tr id="${data.short_code}">
              <td class="td-left">🌐 ${getUrl(urlText.value)}</td>
              <td><a href="http://${HOST}/${data.short_code}">${HOST}/${data.short_code}</a></td>
              <td class="td-right"><button class="del-btn" onClick="handleClickDel('${data.short_code}')">❌</button></td>
            </tr>`
            
            urlText.value = ''
          }
        }).catch(error => console.error('Error: ', error))
    }///////////////////////////////////////////////////////////////
    
    const getUrl = (url) => {
      const LEN = 32
      const INIT = 0
      
      if (url.length > LEN)
        return `${url.substring(INIT, LEN)}...`
      else
        return url
    }//////////////////////////////////////////
    
    const handleClickDel = (short_code) => {
      const trDelete = document.getElementById(short_code)
      if (trDelete)
        trDelete.remove()
    }///////////////////////////////////////
  </script>
</html>