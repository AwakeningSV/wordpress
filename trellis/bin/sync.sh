#!/bin/bash
shopt -s nullglob

ENVIRONMENTS=( hosts/* )
ENVIRONMENTS=( "${ENVIRONMENTS[@]##*/}" )
NUM_ARGS=4

show_usage() {
  echo "Usage: sync <environment> <site name> <type> <mode>

<environment> is the environment to deploy to ("staging", "production", etc)
<site name> is the WordPress site to deploy (name defined in "wordpress_sites")
<type> is what we go to sync ("uploads", "database" or "all")
<mode> is the sync mode ("pull" or "push")

Available environments:
`( IFS=$'\n'; echo "${ENVIRONMENTS[*]}" )`

Aliases:
  staging - stag - s
  production - prod - p
  uploads - media
  database - db
  pull - down
  push - up

Examples:
  sync staging example.com database push
  sync production example.com db pull
  sync staging example.com uploads pull
  sync prod example.com all up
"
}

[[ $# -lt NUM_ARGS ]] && { show_usage; exit 127; }

for arg
do
  [[ $arg = -h ]] && { show_usage; exit 0; }
done

ENV="$1";
SITE="$2";
TYPE="$3";
MODE="$4";

# allow use of abbreviations of environments
if [[ $ENV = p || $ENV = prod ]]; then
  ENV="production"
elif [[ $ENV = s || $ENV = stag ]]; then
  ENV="staging"
fi

# allow use of alias of types
if [[ $TYPE = db ]]; then
  TYPE="database"
elif [[ $TYPE = media ]]; then
  TYPE="uploads"
fi

# allow use of alias of modes
if [[ $MODE = down ]]; then
  MODE="pull"
elif [[ $MODE = up ]]; then
  MODE="push"
fi

# Add ansible_python_interpreter to fix the following error:
#   The module failed to execute correctly, you probably need to set the interpreter.
#   /bin/sh: /usr/bin/python: No such file or directory
# See: https://docs.ansible.com/ansible/latest/reference_appendices/python_3_support.html#using-python-3-on-the-managed-machines-with-commands-and-playbooks
DATABASE_CMD="ansible-playbook database.yml -e env=$ENV -e site=$SITE -e mode=$MODE -e 'ansible_python_interpreter=/usr/bin/python3'"
UPLOADS_CMD="ansible-playbook uploads.yml -e env=$ENV -e site=$SITE -e mode=$MODE -e 'ansible_python_interpreter=/usr/bin/python3'"

HOSTS_FILE="hosts/$ENV"

if [[ ! -e $HOSTS_FILE ]]; then
  echo "Error: '$ENV' is not a valid environment ($HOSTS_FILE does not exist)."
  echo
  echo "Available environments:"
  ( IFS=$'\n'; echo "${ENVIRONMENTS[*]}" )
  exit 1
fi

if [[ $TYPE != "database" && $TYPE != "uploads" && $TYPE != "all" ]]; then
  echo "Error: '$TYPE' is not a valid type (uploads or media, database or db, all)."
  exit 1
fi

if [[ $MODE != "pull" && $MODE != "push" ]]; then
  echo "Error: '$MODE' is not a valid sync mode (pull or down, push or up)."
  exit 1
fi

if [[ $TYPE = database ]]; then
  $DATABASE_CMD
elif [[ $TYPE = uploads ]]; then
  $UPLOADS_CMD
else
  $UPLOADS_CMD
  $DATABASE_CMD
fi
