<?php

$conn = new mysqli("localhost", "root", "jordan88", "discord-servers");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}