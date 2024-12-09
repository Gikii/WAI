<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Upload</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
</head>
<body>
 
 <form enctype="multipart/form-data" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
    Wskaż fotografie: <input name="obrazek" type="file" /><br>
    Tytuł <input type="text" name="title" value="<?= $product['title'] ?>" required /><br />
    Autor <input type="text" name="author" value="<?= $product['author'] ?>" required /><br />
    Znak wodny <input type="text" name="watermark" value="<?= $product['watermark'] ?>" required /><br />
    <!-- MAX_FILE_SIZE musi poprzedzać pole wprowadzania danych do pliku -->
    
	
    <input type="submit" value="Send File" />
 </form>
 <?= "<p style='color:red'>" . ($blad ?? "") . "</p>" ?>
 <div id="result"> 
 </div>
 
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script> 
 </body>
</html>