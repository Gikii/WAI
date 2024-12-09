<?php
require_once 'business.php';
require_once 'controller_utils.php';


function products(&$model)
{
    $products = get_products();
    $model['products'] = $products;

    return 'products_view';
}

function product(&$model)
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        if ($product = get_product($id)) {
            $model['product'] = $product;
            return 'selected_view';
        }
    }

    http_response_code(404);
    exit;
}

function edit(&$model)
{
    $product = [
        'name' => null,
        'price' => null,
        'description' => null,
        '_id' => null
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['name']) &&
            !empty($_POST['price']) /* && ...*/
        ) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;

            $product = [
                'name' => $_POST['name'],
                'price' => (int)$_POST['price'],
                'description' => $_POST['description']
            ];

            if (save_product($id, $product)) {
                return 'redirect:products';
            }
        }
    } elseif (!empty($_GET['id'])) {
        $product = get_product($_GET['id']);
    }

    $model['product'] = $product;

    return 'edit_view';
}

function delete(&$model)
{
    if (!empty($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            delete_product($id);
            return 'redirect:products';

        } else {
            if ($product = get_product($id)) {
                $model['product'] = $product;
                return 'delete_view';
            }
        }
    }

    http_response_code(404);
    exit;
}
function menu()
{
    return 'partial/menu_view';
}
function cart(&$model)
{
    $model['cart'] = get_cart();
    return 'partial/cart_view';
}
function selected(&$model){
    $model['cart'] = get_cart();
    return 'selected_view';
}

function add_to_cart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $product = get_product($id);

        $cart = &get_cart();

        $cart[$id] = ['title' => $product['title'], 'author' => $product['author']];

        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function clear_cart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['cart'] = [];
        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function upload(&$model)
{
    $product = [
        'title' => null,
        'author' => null,
        'watermark' => null,
        'file_extension' => null,
        '_id' => null
    ];
    $model['blad'] = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['title']) &&
            !empty($_POST['author']) &&
            !empty($_POST['watermark'])
        ) {
            if ((isset($_FILES["obrazek"])) && (isset($_POST['title'])) && (isset($_POST['author'])) && (isset($_POST['watermark']))) {
                // Check for file upload errors
                if ($_FILES["obrazek"]["error"] == UPLOAD_ERR_OK) {
                    // Check if the uploaded file is a JPEG image
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $fileType = $_FILES['obrazek']['type'];
              
                    if (!in_array($fileType, $allowedTypes)) {
                        $model['blad'] = "Only JPG or PNG files are allowed";
                    } else {
                        // Rest of your code for handling successful file upload
              
                        // Move the uploaded file to the server
                      //  $uploadfile = $uploaddir . $path . basename($_FILES['obrazek']['name']);
                        $uploaddir= $_SERVER['DOCUMENT_ROOT'].'/images/';
                        $fileExtension = ($fileType === 'image/png') ? 'png' : 'jpg';
                        $file_name= $_POST['title']. '.' .$fileExtension;
                        $target = $uploaddir . $file_name;
                        if (move_uploaded_file($_FILES['obrazek']['tmp_name'], $target)) {
                          //echo('Success');
                          $new_photo_name=$_POST['title'];
                          $watermarkText = $_POST['watermark'];
                          addWatermark($uploaddir, $watermarkText,$fileExtension, $new_photo_name);
                          thumbnail($uploaddir,$fileExtension, $new_photo_name);
                          $id = isset($_POST['id']) ? $_POST['id'] : null;

                          $product = [
                              'title' => $_POST['title'],
                              'author' => $_POST['author'],
                              'watermark' => $_POST['watermark'],
                              'file_extension' => $fileExtension,
                          ];
              
                          if (save_product($id, $product)) {
                              return 'redirect:products';
                          }
                        } else {
                            $model['blad'] = "Possible file upload attack!";
                        }
                    }
                } else {
                    // Handle file upload errors
                    switch ($_FILES["obrazek"]["error"]) {
                       // jest większy niż domyślny maksymalny rozmiar,
                       // podany w pliku konfiguracyjnym
                        case 1:
                            $model['blad'] = "Rozmiar pliku jest zbyt duży";
                            break;
                       // jest większy niż wartość pola formularza 
                       // MAX_FILE_SIZE
                        case 2:
                            $model['blad'] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                            break;
                        case 3:
                            $$model['blad'] = "Plik wysłany częściowo";
                            break;
                        case 4:
                            $model['blad'] = "Nie wysłano żadnego pliku";
                            break;
                        default:
                        $model['blad'] = "Wystąpił błąd podczas wysyłania";
                            break;
                    }
                }
            }

        }
    } elseif (!empty($_GET['id'])) {
        $product = get_product($_GET['id']);
    }

    $model['product'] = $product;

    return 'upload_view';
}
function register(&$model) {
    $model['blad'] = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && isset($_POST['pass']) && isset($_POST['repeat_pass'])) {
        $login = $_POST['login'];
        $password = $_POST['pass'];
        $repeat_password = $_POST['repeat_pass'];

        // Weryfikacja zgodności
        if ($password === $repeat_password) {
            // Czy login istnieje w bazie?
            if (!LoginExist($login)) { // Wolny
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // Dopisz nowego
                if (AddNewUser($login, $hash)) { // Dodano do bazy poprawnie
                    return 'redirect:login';
                } else {
                    $model['blad'] = "Problem z bazą na etapie dodawania użytkownika";
                }
            } else {
                $model['blad'] = "Login zajęty";
            }
        } else {
            // Hasła niezgodne - wróć do formularza
            $model['blad'] = "Hasła nie są zgodne";
        }
    }

    return 'register_view';
}
function login(&$model) {
    $model['blad'] = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && isset($_POST['pass'])) {
        $login = $_POST['login'];
        $password = $_POST['pass'];
        $done = ReadUser($login, $password);

        if ($done !== true) {
            $model['blad'] = "Problem z logowaniem";
        } else {
            return 'redirect:products';
        }
    }

    return 'login_view';
}

function logout(){
session_destroy();
session_unset();
setcookie(session_name(), "", -100);
//usunięcie cookies ustawianych na określony czas
// (jeśli były ustawiane jakieś ciasteczka niesesyjne)
return 'redirect:products';
}

function addWatermark($uploaddir, $watermarkText,$fileExtension, $new_photo_name){
    if($fileExtension=="png" ? $image = imagecreatefrompng($uploaddir . $new_photo_name. '.' .$fileExtension) : $image = imagecreatefromjpeg($uploaddir . $new_photo_name. '.' .$fileExtension));
    $textColor = imagecolorallocate($image, 255, 51, 153);

    for ($i = 0; $i < imagesy($image); $i+=15) {
        imagestring($image, 256, 0, $i, $watermarkText, $textColor);
    }

    $outputImage = $uploaddir . $new_photo_name. '_Watermark' .'.' .$fileExtension;
    if($fileExtension=="png" ? imagepng($image, $outputImage) : imagejpeg($image, $outputImage));
    imagedestroy($image);
}

function thumbnail($uploaddir,$fileExtension, $new_photo_name){
    if($fileExtension=="png" ? $image = imagecreatefrompng($uploaddir . $new_photo_name. '.' .$fileExtension) : $image = imagecreatefromjpeg($uploaddir . $new_photo_name. '.' .$fileExtension));
   $thumbnailImage= imagescale($image,200,125);
   $outputImage = $uploaddir . $new_photo_name. '_Scaled' .'.' .$fileExtension;
   if($fileExtension=="png" ? imagepng($thumbnailImage, $outputImage) : imagejpeg($thumbnailImage, $outputImage));

   imagedestroy($image);
   imagedestroy($thumbnailImage);
}