#!/bin/env bash

# +------------------------------------------------------+
# |                                                      |
# |    Change what the PLUGIN_NAME contain to reflect    |
# |    your development environment.                     |
# |                                                      |
# +------------------------------------------------------+

PLUGIN_NAME="plugin-name"

# EDITING STOPS HERE. DO NOT CHANGE ANY OF THE FOLLOWING.

# +------------------+
# |                  |
# |    Setting up    |
# |                  |
# +------------------+

PROJECT_PATH=$(readlink --canonicalize "$(basename ${PLUGIN_NAME})")
echo $PROJECT_PATH
BUILD_PATH="${PROJECT_PATH}/build"
DEST_PATH="$BUILD_PATH/$PLUGIN_SLUG"
BIN_PATH="${PROJECT_PATH}/bin"


echo "Generating build directory..."
rm -rf "$BUILD_PATH"
mkdir -p "$DEST_PATH"

exit

echo "Installing PHP dependencies..."
composer install --no-dev || exit "$?"

# echo "Generating stylesheets..."
# sh "$BIN_PATH/generate-stylesheets.sh"

echo "Syncing files..."
rsync --recursive --checksum --include="$PROJECT_PATH/.env" --exclude-from="$PROJECT_PATH/.distignore" "$PROJECT_PATH/" "$DEST_PATH/" --delete --delete-excluded

echo "Generating zip file..."
cd "$BUILD_PATH" || exit
zip --quiet --recurse-paths "${PLUGIN_SLUG}.zip" "$PLUGIN_SLUG/"

cd "$PROJECT_PATH" || exit
mv "$BUILD_PATH/${PLUGIN_SLUG}.zip" "$PROJECT_PATH"
echo "${PLUGIN_SLUG}.zip file generated!"

echo "Build done!"
