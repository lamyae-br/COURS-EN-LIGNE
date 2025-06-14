<?php
session_start();
session_destroy();
header("Location: Espace_etudiant");
exit();
