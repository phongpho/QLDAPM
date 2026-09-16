@echo off

browser-sync start --proxy "localhost/b8" --files "**/*.php, **/*.css, **/*.js"

pause