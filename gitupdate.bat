@echo off
set /p UPDATE=Please decribe the update: 
cd /d C:\cablocator
git add --all
git commit -m "%UPDATE%"
git push
pause
