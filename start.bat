@echo off

set frontend_path=.

set frontend_cmd=php artisan serve


:: Start frontend server
start cmd /k "cd %frontend_path% && npm run dev"
start cmd /k "cd %frontend_path% && %frontend_cmd% --host=127.0.0.1"
