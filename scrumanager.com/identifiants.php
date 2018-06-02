<?php
const dbname = 'scrumanager';
const host = 'localhost';
const charset = 'utf8mb4';
const DBusername = 'ensa';
const DBpassword = 'ILoveLinux';
const SITE_ROOT = '/home/toumani/PHP/ENSASProjects/scrumanager.com/';

$PDOException = false;

try {
	$database = new PDO('mysql:host=' . host . ';dbname=' . dbname . ';charset=' . charset, DBusername, DBpassword);
}
catch (PDOException $ex) {
	$PDOException = true;
}