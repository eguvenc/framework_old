
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- <link rel="icon" href="favicon.ico"> -->

    <title>Öğretici Örnekler</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/examples.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a href="/">Hoşgeldiniz</a></li>
            <li role="presentation"><a href="#">Dökümentasyon</a></li>
          </ul>
        </nav>
        
        <h2 class="text-muted"><a href="/">Öğretici Örnekler</a></h2>
      </div>

      <!---
      <div class="jumbotron">
        <p class="lead">Aşağıdaki örnekleri test ederek en çok kullanılan özellikleri deneyimleyebilirsiniz.</p>
      </div>
      -->

      <div class="row description">

        <div class="col-lg-6">

        <div class = "panel panel-default">
           <div class = "panel-body">
              <h4><a href="/examples/helloWorld">Merhaba Dünya</a></h4>
              <p>Http paketi ile bir kontrolör içerisinden response nesnesini kullanarak ilk çıktınızı üretin.</p>
           </div>
        </div>

        <div class = "panel panel-default">
           <div class = "panel-body">
              <h4><a href="/examples/errors/Errors">Hata Sayfaları</a></h4>
              <p>Önceden tanımlı hata şablonlarını kullanarak özelleştirebilir hata çıktıları üretin.</p>
           </div>
        </div>

        <div class = "panel panel-default">
           <div class = "panel-body">
            <h4><a href="/examples/membership/login">Üyelik</a></h4>
            <p>Kullanıcı kimliklerini saklayabilen yetkilendirme servisi ile bir üyelik modülünün nasıl işlediğini test edin.</p>
           </div>
        </div>

        <div class = "panel panel-default">
           <div class = "panel-body">
            <h4><a href="/examples/logger">Loglama</a></h4>
            <p>Loglarınızı dosyaya yazın ve konsoldan takip edin.</p>
           </div>
        </div>

        </div>

        <div class="col-lg-6">

          <div class = "panel panel-default">
             <div class = "panel-body">
              <h4><a href="/examples/layers">Arayüz Katmanları</a></h4>
              <p>Katmanlar oluşturarak bir sayfayı; gezinti çubuğu, alt sayfa, yan sayfa gibi bölümlere ayırıp kullanıcı arayüzünü ( GUI ) kolayca oluşturun.</p>
             </div>
          </div>

          <div class = "panel panel-default">
             <div class = "panel-body">
              <h4><a href="/examples/forms">Formlar</a></h4>
              <p>Form, form element ve form doğrulayıcı nesneleri ile http, ajax türünde formlar oluşturun. Csrf ve Captcha servisleri ile uygulamanızın girdi güvenliğini yükseltin.</p>
             </div>
          </div>

          <div class = "panel panel-default">
             <div class = "panel-body">
              <h4><a href="/examples/console">Konsol Görevleri</a></h4>
              <p>Konsoldan çalıştırılabilen php dosyaları oluşturun.</p>
             </div>
          </div>

          <div class = "panel panel-default">
             <div class = "panel-body">
              <h4><a href="/examples/debugger">Çıktı Görüntüleme</a></h4>
              <p>Debugger eklentisini etkinleştirerek logları, istekleri, girdileri ve çevre bileşenlerini denetleyin.</p>
             </div>
          </div>

          <!--      
          <div class = "panel panel-default">
             <div class = "panel-body">
              <h4><a href="">Çerezler</a></h4>
              <p>Çerez nesnesi ile çerezleri kolayca yönetin.</p>             
             </div>
          </div>

          <div class = "panel panel-default">
             <div class = "panel-body">
              <h4><a href="">Oturumlar</a></h4>
              <p>Kullanıcı oturumları açın ve oturum verilerini yönetin.</p>             
             </div>
          </div>
          -->

        </div>
      </div>

      <footer class="footer">
            <p>&copy; 2016 Obullo.</p>
      </footer>

    </div> <!-- /container -->


  </body>
</html>


