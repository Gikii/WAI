<hr>
<a href="products">Galeria</a>&nbsp;&nbsp;&nbsp;
<a href="upload">Upload</a>&nbsp;&nbsp;&nbsp;
<a href="selected">Selected</a>&nbsp;&nbsp;&nbsp;
<?php
if (!empty($_SESSION['user_id'])){
echo '<a href="logout">Wylogowanie</a>';
}
else echo '<a href="login">Logowanie</a>&nbsp;&nbsp;&nbsp;<a
href="register">Rejestracja</a>';
?>
<hr>