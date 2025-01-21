<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?=$title?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="<?=URL_CSS_JS?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=URL_CSS_JS?>/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?=URL_CSS_JS?>/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?=URL_CSS_JS?>/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?=URL_CSS_JS?>/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?=URL_CSS_JS?>/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?=URL_CSS_JS?>/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?=URL_CSS_JS?>/css/style.css" rel="stylesheet">
<?=$this->section("css")?>
</head>

<body>

  

  
  <div class="container">

  <?=$this->load()?>

  </div><!-- End #main -->

  <!-- ======= Footer ======= -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?=URL_CSS_JS?>/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?=URL_CSS_JS?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=URL_CSS_JS?>/vendor/chart.js/chart.umd.js"></script>
  <script src="<?=URL_CSS_JS?>/vendor/echarts/echarts.min.js"></script>
  <script src="<?=URL_CSS_JS?>/vendor/quill/quill.js"></script>
  <script src="<?=URL_CSS_JS?>/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?=URL_CSS_JS?>/vendor/tinymce/tinymce.min.js"></script>
  

  <!-- Template Main JS File -->
  <script src="<?=URL_CSS_JS?>/js/main.js"></script>
  <script src="<?=URL_CSS_JS?>/js/sweetalert2.js"></script>
  <script src="<?=URL_CSS_JS?>/js/jquery.min.js"></script>
  <script src="<?=URL_CSS_JS?>/js/app.js"></script>
<?=$this->section("js")?>
</body>

</html>