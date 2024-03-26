#!/bin/env bash

# +------------------------------------------------------+
# |                                                      |
# |    Change what the PLUGIN_NAME contain to reflect    |
# |    your development environment.                     |
# |                                                      |
# +------------------------------------------------------+

PLUGIN_NAME="plugin-name"

# EDITING STOPS HERE. DO NOT CHANGE ANY OF THE FOLLOWING.

# +---------------------------+
# |                           |
# |    Generating ZIP file    |
# |                           |
# +---------------------------+

PROJECT_PATH=$(readlink --canonicalize "$(basename ${PLUGIN_NAME})")
echo $PROJECT_PATH
BUILD_PATH="${PROJECT_PATH}/build"
DEST_PATH="$BUILD_PATH/$PLUGIN_NAME"

echo "Generating build directory..."
rm -rf "$BUILD_PATH"
mkdir -p "$DEST_PATH"

composer_dependencies="$(find "$PROJECT_PATH" -maxdepth 1 -type f -name 'composer.json' | wc --lines)"
if ((composer_dependencies > 0)); then
    echo "Installing PHP dependencies..."
    composer install -d "$PROJECT_PATH" --no-dev || exit "$?"
fi

# TODO(dependencies): Check for package.json to install JS dependencies.

echo "Syncing files..."
rsync --recursive --checksum --include="$PROJECT_PATH/.env" --exclude-from="$PROJECT_PATH/.distignore" "$PROJECT_PATH/" "$DEST_PATH/" --delete --delete-excluded

echo "Generating zip file..."

cd "$BUILD_PATH" || exit
zip --quiet --recurse-paths "${PLUGIN_NAME}.zip" "$PLUGIN_NAME/"

cd "$PROJECT_PATH" || exit
mv "$BUILD_PATH/${PLUGIN_NAME}.zip" "$(dirname "$PROJECT_PATH")"
echo "${PLUGIN_NAME}.zip file generated!"

echo "Cleaning up..."
rm -r "${BUILD_PATH}"

echo -e "\n\u2714 Build done!\n"
