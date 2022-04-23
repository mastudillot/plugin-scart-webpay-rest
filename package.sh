#!/bin/sh

#Script for create the plugin artifact
echo "tag: $TAG"

if [ "$TAG" = "" ]
then
   TAG='1.0.0'
fi

OUTPUT_DIR="output"
SDK_DIR="src/TransbankSDK"
FILE1="src/config.json"
OUTPUT_PLUGIN_DIR="WebpayPlus"

cd "$SDK_DIR" || exit
composer install --no-dev
composer update --no-dev
cd ../../

sed -i.bkp "s/\"1.0.0\"/\"${TAG#"v"}\"/g" "$FILE1"

PLUGIN_FILE="plugin-scart-webpay-rest-$TAG.zip"

mkdir -p "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR"

cp -r public "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR"
cp -r src "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR"

cd "$OUTPUT_DIR" || exit
zip -FSr $PLUGIN_FILE $OUTPUT_PLUGIN_DIR -x "$OUTPUT_PLUGIN_DIR/src/$FILE1.bkp"
cd ../

cp "$FILE1.bkp" "$FILE1"
rm "$FILE1.bkp"
rm -R "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR"

echo "Plugin version: $TAG"
echo "Plugin file: $PLUGIN_FILE"