#!/bin/sh

#Script for create the plugin artifact

if [ "$TAG" = "" ]
then
   TAG='1.0.0'
fi
echo "tag: $TAG"

OUTPUT_DIR="output"
SDK_DIR="src/TransbankSDK"
CONFIG_PLUGIN_FILE="config.json"
OUTPUT_PLUGIN_DIR="WebpayPlus"
PLUGIN_FILE="plugin-scart-webpay-rest-$TAG.zip"

# Install plugin dependencies
cd "$SDK_DIR" || exit
composer install --no-dev
composer update --no-dev
cd ../../

# Create content
mkdir -p "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR"
cp -r src/* "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR"
cd "$OUTPUT_DIR/$OUTPUT_PLUGIN_DIR" || exit

# Change version to config file
sed -i.bkp "s/\"1.0.0\"/\"${TAG#"v"}\"/g" "$CONFIG_PLUGIN_FILE"

cd ../

# Create zip
zip -FSr $PLUGIN_FILE $OUTPUT_PLUGIN_DIR -x "$OUTPUT_PLUGIN_DIR/$CONFIG_PLUGIN_FILE.bkp"

# Delete plugin content
rm -R "$OUTPUT_PLUGIN_DIR"

echo "Plugin version: $TAG"
echo "Plugin file: $PLUGIN_FILE"