<?php

function is_administrator($user = 'me') {
	return (isset($_SESSION['user']) && ($_SESSION['user'] === $user));
}
