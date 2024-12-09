<?php


use MongoDB\BSON\ObjectID;


function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}

function get_products()
{
    $db = get_db();
    return $db->products->find()->toArray();
}

function get_products_by_category($cat)
{
    $db = get_db();
    $products = $db->products->find(['cat' => $cat]);
    return $products;
}

function get_product($id)
{
    $db = get_db();
    return $db->products->findOne(['_id' => new ObjectID($id)]);
}

function save_product($id, $product)
{
    $db = get_db();

    if ($id == null) {
        $db->products->insertOne($product);
    } else {
        $db->products->replaceOne(['_id' => new ObjectID($id)], $product);
    }

    return true;
}

function delete_product($id)
{
    $db = get_db();
    $db->products->deleteOne(['_id' => new ObjectID($id)]);
}

function AddNewUser($log, $pass){
	$db=get_db();
	$wynik =$db->users->insertOne([ 'login' => $log, 'password' => $pass ]);
	return $wynik;	 
}

function LoginExist($log){
	$db=get_db();
	$query = ['login' => $log];
	//dokument jeśli jest, null jeśli nie znaleziony
	$user = $db->users->findOne($query);
	if ($user) {return true; }
	 else { return false;}
}

function AllUsers(){
	$db=get_db();
	if ($db->users->count()>0) {
	  return $db->users->find();
	}
	else return false;
}

function DeleteUser($idU){
 try{
	$db = get_db();
    $db->users->deleteOne(['_id' => new ObjectID($idU)]);
	return true;
 }
 catch( Exception $e) { return $e; }
}

function ReadUser($login, $password){
	try {
	$db = get_db();
	$user = $db->users->findOne(['login' => $login]);
	//weryfikacja hasła
	if($user !== null && password_verify($password,
	$user['password'])){
	//hasło poprawne
	session_regenerate_id();
	$_SESSION['user_id'] = $user['_id'];
	return true;
	}
	else { return false; }
	}
	catch( Exception $e) { return $e; }
	}


