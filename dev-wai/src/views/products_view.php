<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
<?php dispatch($routing, '/menu') ?>


<?php if (count($products)): ?>
  <form action="cart/add" method="post" class="wide">
    <div class="gallery-container">
        <?php foreach ($products as $product): ?>
          
            <div class="gallery">
                <a target="_blank" href="<?php echo 'images/' . $product['title'] . '_Watermark'.'.' . $product['file_extension']; ?>">
                <img src="<?php echo 'images/' . $product['title'] . '_Scaled'.'.' . $product['file_extension']; ?>" alt="<?php echo $product['title']; ?>">
                </a>
                <div class="desc"><?php echo $product['author'];?>, <?php echo $product['title'];?>
                <input type="checkbox" id="saved_<?php echo $product['_id']; ?>" name="saved[]" value="<?= $product['_id'] ?>">
              </div>
            </div>
        <?php endforeach ?>
    </div>
    <input type="hidden" name="id" value="<?= $product['_id'] ?>"/>
    <input type="submit" name="add_to_cart" value="Zapamiętaj wybrane"/>
        </form>
<?php else: ?>
        Brak produktów
        
<?php endif ?>







</body>
</html>
