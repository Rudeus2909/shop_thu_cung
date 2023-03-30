<?php

// Chuyển hướng đến một trang khác
function redirect($location)
{
	header('Location: ' . $location);
	exit();
}
