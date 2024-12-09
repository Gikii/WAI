<!DOCTYPE html>
<html>
<head>
    <title>Galeria wybranych</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
<?php dispatch($routing, '/menu') ?>


<?php if (count($cart)): ?>
    <div class="gallery-container">
        <?php foreach ($cart as $id => $product): ?>
            <div class="gallery">
                <a target="_blank" href="<?php echo 'images/' . $product['title'] . '_Watermark'.'.' . $product['file_extension']; ?>">
                <img src="<?php echo 'images/' . $product['title'] . '_Scaled'.'.' . $product['file_extension']; ?>" alt="<?php echo $product['title']; ?>">
                </a>
                <div class="desc"><?php echo $product['author'];?>, <?php echo $product['title'];?>
              </div>
            </div>
        <?php endforeach ?>
    </div>
        </form>
<?php else: ?>
        Brak produktów
        
<?php endif ?>







</body>
</html>
