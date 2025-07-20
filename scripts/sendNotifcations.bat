@echo off
set PHP_PATH=%~dp0..\..\php\php.exe
set SCRIPT_PATH=%~dp0include\sendNotification.php

"%PHP_PATH%" "%SCRIPT_PATH%"
pause
