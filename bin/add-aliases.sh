#!/bin/env bash

# +--------------------------------------------------------------------+
# |                                                                    |
# |    Change what the PLUGIN_NAME and SITE_NAME contain to reflect    |
# |    your development environment.                                   |
# |                                                                    |
# +--------------------------------------------------------------------+

PLUGIN_NAME="plugin-name"
SITE_PATH="${HOME}/path/to/site/public_html"

# EDITING STOPS HERE. DO NOT CHANGE ANY OF THE FOLLOWING.

# +------------------+
# |                  |
# |    Setting up    |
# |                  |
# +------------------+

# Directory used to develop the plugin.
SOURCE=$(readlink --canonicalize "$(basename ${PLUGIN_NAME})")
# Plugin directory of development enviroment.
DESTINATION="${SITE_PATH}/wp-content/plugins/"

# Directories/Files to exclude. Comma-separated.
EXCLUDE='{node_modules,.git,.vscode}'

# rsync switches:
# a: archive mode; equals -rlptgoD (no -H,-A,-X)
# u: skip files that are newer on the receiver
# v: increase verbosity
# h: output numbers in a human-readable format
# p: set destination permissions to be the same as source permissions
RSYNC="rsync -auvhp --exclude=${EXCLUDE} ${SOURCE} ${DESTINATION}"

# +----------------------+
# |                      |
# |    Define aliases    |
# |                      |
# +----------------------+

# shellcheck disable=SC2086,SC2139
alias $PLUGIN_NAME="echo 'uploading plugin source code files to development environment';echo -e '  source:    \t${SOURCE}';echo -e '  destination:\t${DESTINATION}\n';${RSYNC}"

# shellcheck disable=SC2139
alias "${PLUGIN_NAME}-debug-log"="open ${SITE_PATH}/wp-content/debug.log"

# +-----------------------+
# |                       |
# |    Display aliases    |
# |                       |
# +-----------------------+

printf "Added the following aliases:\n\n"
alias | grep "alias $PLUGIN_NAME"
printf "\n"
