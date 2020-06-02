#!/bin/bash

# build angular project
ng build --prod --aot

# remove old 
rm -f ../../public/*.js
rm -f ../../public/*.css
rm -f ../../public/*.ico
rm -f ../../public/*.png
rm -rf ../../public/assets

# copy new files
cp dist/frontend/*.js ../../public/
cp dist/frontend/*.css ../../public/
cp dist/frontend/*.ico ../../public/
cp dist/frontend/*.png ../../public/
cp dist/frontend/index.html ../views/index.html
cp -r dist/frontend/assets ../../public/
