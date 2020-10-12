<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->addExternalJS("/s2/handlers/myScript.js");
?>

<div class="oneclick-form-container">
	<form action="order.php" method="POST" id="oneclick-form">
		<input type="number" name="phoneNumber" id="phoneNumber" placeholder="Введите номер телефона">
		<input type="submit" name="submit" id="submit">
	</form>
</div>

